<?php
namespace app\commands;

use yii\console\{Controller, ExitCode};
use app\components\behaviors\MessageBehavior;

/**
 * Manages VPN utilities.
 *
 * @author Andrea Landonio <landonio.andrea@gmail.com>
 * @since 1.0
 */
class VpnController extends Controller
{
	/**
	 * @var string $config the config file
	 */
	public $config = '~/.openvpn/client.ovpn';

	/**
	 * @var string $auth the auth user pass file
	 */
	public $auth = '~/.openvpn/auth.txt';

	/**
	 * Define controller options.
	 *
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID): array
	{
		return ['config', 'auth'];
	}

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
     * This command perform what you have entered as action.
     * Valid actions are:
     * - start
     * - stop
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

	    // Get current user
	    $current_user = trim(shell_exec('whoami'));

	    switch ($action) {
		    case 'start': {
			    // Check if root
			    if ($current_user === 'root') {
				    $message->info('Starting VPN..');
				    echo shell_exec('sudo /usr/sbin/openvpn --config ' . $this->config . ' --auth-user-pass ' . $this->auth . ' > /var/log/nohup.out 2>&1 &');
			    }
			    else {
				    $message->warning('Not a root user, run with sudo!');
			    }
			    break;
		    }
		    case 'stop': {
			    $message->info('Stopping VPN..');
			    echo shell_exec('sudo killall openvpn');
			    break;
		    }
		    case 'status': {
			    $message->info('Status VPN:');
			    echo shell_exec('ps -eaf | grep openvpn');
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
