<?php
namespace app\commands;

use yii\console\{Controller, ExitCode};
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
	 * @var string $service_fpm the php-fpm service name
	 */
	private $service_fpm;

	/**
	 * @var string $service_nginx the nginx service name
	 */
	private $service_nginx;

	/**
	 * @var string $service_varnish the varnish service name
	 */
	private $service_varnish;

	/**
	 * Define controller behaviors.
	 *
	 * @return array
	 */
	public function behaviors(): array
	{
		return [
			'message' => [
				'class' => MessageBehavior::className(),
				'controller' => $this
			]
		];
	}

	/**
	 * Check controller dependencies
	 *
	 * @return int
	 */
	protected function checkDependencies(): int {
		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		// Check if php-fpm service is installed
		$this->service_fpm = trim(shell_exec('ls /etc/init.d | grep -m 1 fpm'));
		if (empty($this->service_fpm))
		{
			$message->error('Missing php-fpm service, please install it!');
			return ExitCode::SOFTWARE;
		}

		// Check if nginx service is installed
		$this->service_nginx = trim(shell_exec('ls /etc/init.d | grep -m 1 nginx'));
		if (empty($this->service_nginx))
		{
			$message->error('Missing nginx service, please install it!');
			return ExitCode::SOFTWARE;
		}

		// Check if varnish service is installed
		$this->service_varnish = trim(shell_exec('ls /etc/init.d | grep -m 1 varnish'));
		if (empty($this->service_varnish))
		{
			$message->error('Missing varnish service, please install it!');
			return ExitCode::SOFTWARE;
		}

		return ExitCode::OK;
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
    public function actionIndex(string $action): int
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

	    // Check if mandatory services/packages are installed
	    if ($exit_code = $this->checkDependencies() !== ExitCode::OK) return $exit_code;

	    switch ($action) {
		    case 'start': {
			    $message->info('Starting LNMP stack..');
			    echo shell_exec('sudo service ' . $this->service_fpm . ' start');
			    echo shell_exec('sudo service ' . $this->service_nginx . ' start');
			    echo shell_exec('sudo service ' . $this->service_varnish . ' start');
			    break;
		    }
		    case 'stop': {
			    $message->info('Stopping LNMP stack..');
			    echo shell_exec('sudo service ' . $this->service_fpm . ' stop');
			    echo shell_exec('sudo service ' . $this->service_nginx . ' stop');
			    echo shell_exec('sudo service ' . $this->service_varnish . ' stop');
			    break;
		    }
		    case 'restart': {
			    $message->info('Restarting LNMP stack..');
			    echo shell_exec('sudo service ' . $this->service_fpm . ' restart');
			    echo shell_exec('sudo service ' . $this->service_nginx . ' restart');
			    echo shell_exec('sudo service ' . $this->service_varnish . ' restart');
			    break;
		    }
		    case 'status': {
			    $message->info('Status PHP-FPM:');
			    echo shell_exec('sudo service ' . $this->service_fpm . ' status | grep Active');
			    $message->info('Status NGINX:');
			    echo shell_exec('sudo service ' . $this->service_nginx . ' status | grep Active');
			    $message->info('Status VARNISH:');
			    echo shell_exec('sudo service ' . $this->service_varnish . ' status | grep Active');
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
