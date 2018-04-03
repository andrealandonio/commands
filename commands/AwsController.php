<?php
namespace app\commands;

use yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\components\behaviors\MessageBehavior;
use Aws\Sdk;

/**
 * Manages AWS utilities.
 *
 * Usage examples:
 * cmd aws cs search hello --endpoint=http://name.cloudsearch.amazonaws.com --start=0 --size=100 --sort_field=_score --sort_direction=asc --parser=simple --fq=post_type:\'post\'
 * cmd aws cs status --domain=name
 *
 * @author Andrea Landonio <landonio.andrea@gmail.com>
 * @since 1.0
 */
class AwsController extends Controller
{
	/**
	 * @var string $profile the AWS profile to load
	 */
	public $profile = '';

	/**
	 * @var string $endpoint the AWS CloudSearch endpoint (must be full URIs and include a scheme and host)
	 */
	public $endpoint = '';

	/**
	 * @var string $domain the AWS CloudSearch domain name
	 */
	public $domain = '';

	/**
	 * @var int $start the AWS CloudSearch search starting offset
	 */
	public $start = 0;

	/**
	 * @var int $size the AWS CloudSearch search max items
	 */
	public $size = 100;

	/**
	 * @var string $fq the AWS CloudSearch search filter query (please, escape strings)
	 */
	public $fq = '';

	/**
	 * @var string $parser the AWS CloudSearch search query parser
	 */
	public $parser = 'simple';

	/**
	 * @var string $sort_field the AWS CloudSearch search sort field
	 */
	public $sort_field = '_score';

	/**
	 * @var string $sort_direction the AWS CloudSearch search sort direction
	 */
	public $sort_direction = 'desc';

	/**
	 * Define controller options.
	 *
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID)
	{
		return ['profile', 'endpoint', 'domain', 'start', 'size', 'fq', 'parser', 'sort_field', 'sort_direction'];
	}

	/**
	 * Define controller behaviors.
	 *
	 * @return array
	 */
	public function behaviors()
	{
		return [
			'message' => [
				'class' => MessageBehavior::className(),
				'controller' => $this
			]
		];
	}

    /**
     * This command perform what you have entered as action.
     * Valid actions/methods are:
     * - ec2 (find)
     * - s3 (cp|ls|mb|mv|presign|rb|rm|sync|website)
     * - cs (search|status)
     *
     * @param string $action the action to be performed.
     * @param string $method the method to be performed.
     * @param string $key_1 the first input key.
     * @param string $key_2 the second input key.
     *
     * @return int
     */
    public function actionIndex(string $action, string $method, string $key_1 = '', string $key_2 = '')
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

	    switch ($action) {
		    case 'ec2': {
		    	// EC2 action
			    switch ($method) {
				    case 'find': {
				    	// Find method
				    	if (!empty($key_1)) {
						    echo $this->findEc2Instances($key_1);
					    }
				    	else {
						    $message->error('Missing key');
						    return ExitCode::USAGE;
					    }
					    break;
				    }
				    default: {
					    $message->error('Invalid method');
					    return ExitCode::USAGE;
				    }
			    }
			    break;
		    }
		    case 's3': {
		    	// S3 action
			    if (!empty($key_1)) {
				    echo $this->manageS3Command($method, $key_1, $key_2);
			    }
			    else {
				    $message->error('Missing key');
				    return ExitCode::USAGE;
			    }
			    break;
		    }
		    case 'cs': {
		    	// CS action
			    switch ($method) {
				    case 'search': {
					    // Search method
					    if (empty($key_1)) {
						    $message->error('Missing key');
						    return ExitCode::USAGE;
					    }
					    elseif (empty($this->endpoint)) {
						    $message->error('Missing endpoint');
						    return ExitCode::USAGE;
					    }
					    else {
						    echo $this->searchCSKeyword($key_1);
					    }
					    break;
				    }
				    case 'status': {
					    // Status method
					    if (empty($this->domain)) {
						    $message->error('Missing domain');
						    return ExitCode::USAGE;
					    }
					    else {
						    echo $this->checkCSStatus();
					    }
					    break;
				    }
				    default: {
					    $message->error('Invalid method');
					    return ExitCode::USAGE;
				    }
			    }
			    break;
		    }
		    default: {
			    $message->error('Invalid action');
			    return ExitCode::USAGE;
		    }
	    }

	    return ExitCode::OK;
    }

	/**
	 * Find EC2 instances
	 *
	 * @param string $key the instance name to find.
	 *
	 * @return string
	 */
	protected function findEc2Instances(string $key)
	{
		$output = '';

		/**
		 * @var Sdk $aws
		 */
		$aws = Yii::$app->awssdk->getAwsSdk();
		$ec2 = $aws->createEc2();

		// Prepare search parameters
		$params = [
			'MaxRecords' => 50,
			'Filters' => [
				[
					'Name' => 'tag:Name',
					'Values' => ['*' . $key . '*']
				]
			]
		];

		do {
			$nextToken = null;
			$results = $ec2->describeInstances($params)->toArray();
			if (isset($results['NextToken'])) {
				// Manage cursor for next
				$nextToken = $results['NextToken'];
				$params['NextToken'] = $nextToken;
			}

			// Loop reservations
			foreach ($results['Reservations'] as $reservation) {
				// Loop instances
				foreach ($reservation['Instances'] as $instance) {
					if (isset($instance['PrivateIpAddress'])) {
						$name = 'unknown';
						$type = (!empty($instance['InstanceType'])) ? $instance['InstanceType'] : 'undefined';

						// Retrieve instance name by tag
						if (!empty($instance['Tags'])) {
							foreach ($instance['Tags'] as $tags)  {
								if (isset($tags['Key']) && $tags['Key'] === 'Name') {
									$name = $tags['Value'];
								}
							}
						}

						// Add instance
						$output .= $instance['PrivateIpAddress'] . ' - ' . $name  . ' - ' . $type . ' (' . $instance['State']['Name'] . ')' . "\n";
					}
				}
			}
		}
		while ($nextToken !== null);

		return $output;
	}

	/**
	 * Manage S3 command
	 *
	 * @param string $method the method to be performed.
	 * @param string $key_1 the first input key.
	 * @param string $key_2 the second input key.
	 *
	 * @return string
	 */
	protected function manageS3Command(string $method, string $key_1, string $key_2)
	{
		return shell_exec('aws s3 ' . $method . ' ' . $key_1 . ' ' . $key_2 . ' ' . ((!empty($this->profile)) ? ('--profile ' . $this->profile): ''));
	}

	/**
	 * Search CS keyword
	 *
	 * @param string $key the instance name to find.
	 *
	 * @return string
	 */
	protected function searchCSKeyword(string $key)
	{
		$output = '';

		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		/**
		 * @var Sdk $aws
		 */
		$aws = Yii::$app->awssdk->getAwsSdk();
		$cs = $aws->createCloudSearchDomain(array(
			'endpoint' => $this->endpoint,
		));

		// Prepare search parameters
		$params = array(
			'query' => $key,
			'start' => intval( $this->start ),
			'size' => intval( $this->size ),
			'queryParser' => $this->parser,
			'return' => '_all_fields,_score',
			'sort' => $this->sort_field . ' ' . $this->sort_direction
		);
		if (!empty($this->fq)) $params['filterQuery'] = $this->fq;

		// Result recap data
		$message->data('Query', $key);
		$message->data('Parser', $this->parser);
		$message->data('Filter', $this->fq);
		$message->data('Start', $this->start);
		$message->data('Size', $this->size);
		$message->data('Sort', $this->sort_field . ' ' . $this->sort_direction);

		// Query data
		$results = $cs->search($params)->toArray();

		if (!empty($results['hits']['found']) && !empty($results['hits']['hit'])) {
			$message->data('Items', $results['hits']['found']);

			// Loop results
			foreach ($results['hits']['hit'] as $result) {
				// Show row results
				$message->row(array(
					$result['id'],
					$result['fields']['_score'][0],
					$result['fields']['post_date'][0],
					$result['fields']['post_type'][0],
					$result['fields']['post_status'][0],
					$result['fields']['post_title'][0]
				));
			}
		}
		else {
			// No results
			$message->data('Items', 0);
		}

		return $output;
	}

	/**
	 * Check CS status
	 *
	 * @return string
	 */
	protected function checkCSStatus()
	{
		$output = '';

		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		/**
		 * @var Sdk $aws
		 */
		$aws = Yii::$app->awssdk->getAwsSdk();
		$cs = $aws->createCloudSearch();

		// Prepare parameters
		$params = array(
			'DomainNames' => array($this->domain)
		);

		// Gets information about the search domains
		$results = $cs->describeDomains($params);

		// Manage results
		if (!empty($results) && !empty($results['DomainStatusList'][0])) {
			$status_requires_index_documents = (!empty($results['DomainStatusList'][0]['RequiresIndexDocuments'])) ? 1 : 0;
			$status_processing = (!empty($results['DomainStatusList'][0]['Processing'])) ? 1 : 0;

			// Show results
			$message->data('Requires index documents', $status_requires_index_documents);
			$message->data('Processing', $status_processing);
			$message->data('Instance type', $results['DomainStatusList'][0]['SearchInstanceType']);
			$message->data('Partition count', $results['DomainStatusList'][0]['SearchPartitionCount']);
			$message->data('Instance count', $results['DomainStatusList'][0]['SearchInstanceCount']);
		}
		else {
			// Error describing domains
			$message->error('Unable to describe domains');
		}

		return $output;
	}
}
