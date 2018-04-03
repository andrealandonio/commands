<?php
namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\components\behaviors\MessageBehavior;

/**
 * Manages environment utilities.
 *
 * @author Andrea Landonio <landonio.andrea@gmail.com>
 * @since 1.0
 */
class EnvController extends Controller
{
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
     * Valid actions are:
     * - start
     * - stop
     * - restart
     * - status
     *
     * @param string $action the action to be performed.
     *
     * @return int
     */
    public function actionIndex(string $action)
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

	    switch ($action) {
		    case 'start': {
			    $message->info('Starting LNMP stack..');
			    echo shell_exec('sudo service php7.0-fpm start');
			    echo shell_exec('sudo service nginx start');
			    echo shell_exec('sudo service varnish start');
			    break;
		    }
		    case 'stop': {
			    $message->info('Stopping LNMP stack..');
			    echo shell_exec('sudo service php7.0-fpm stop');
			    echo shell_exec('sudo service nginx stop');
			    echo shell_exec('sudo service varnish stop');
			    break;
		    }
		    case 'restart': {
			    $message->info('Restarting LNMP stack..');
			    echo shell_exec('sudo service php7.0-fpm restart');
			    echo shell_exec('sudo service nginx restart');
			    echo shell_exec('sudo service varnish restart');
			    break;
		    }
		    case 'status': {
			    $message->info('Status PHP-FPM:');
			    echo shell_exec('sudo service php7.0-fpm status | grep Active');
			    $message->info('Status NGINX:');
			    echo shell_exec('sudo service nginx status | grep Active');
			    $message->info('Status VARNISH:');
			    echo shell_exec('sudo service varnish status | grep Active');
		    	break;
		    }
		    default: {
			    $message->error('Invalid action');
			    return ExitCode::USAGE;
		    }
	    }

	    return ExitCode::OK;
    }
}
