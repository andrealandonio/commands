<?php
namespace app\commands;

use yii\console\{Controller, ExitCode};
use app\components\behaviors\MessageBehavior;

/**
 * Manages sites utilities.
 *
 * @author Andrea Landonio <landonio.andrea@gmail.com>
 * @since 1.0
 */
class SitesController extends Controller
{
	/**
	 * @var int $fps the video frame rate
	 */
	public $fps = 30;

	/**
	 * @var string $size the video frame size
	 */
	public $size = '1600x900';

	/**
	 * Define controller options.
	 *
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID): array
	{
		return ['fps', 'size'];
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
	 * Check controller dependencies
	 *
	 * @return int
	 */
	protected function checkDependencies(): int {
		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		//TODO: load properties file in Yii2

		// Check if config file exists
		$check_ffmpeg_dependencies = shell_exec('whereis ffmpeg');
		if (empty($check_ffmpeg_dependencies) || strlen($check_ffmpeg_dependencies) < 9)
		{
			$message->error('Missing ffmpeg, please install it!');
			return ExitCode::SOFTWARE;
		}

		return ExitCode::OK;
	}

	/**
	 * This command perform what you have entered as action.
	 * Valid actions are:
	 * - add_user
	 *
	 * @param string $action the action to be performed.
	 * @param string $input the input file to be managed.
	 * @param string $output the output file to be generated.ss
	 *
	 * @return int
	 */
    public function actionIndex(string $action, string $input = '', string $output = ''): int
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

	    // Check if mandatory services/packages are installed
	    if ($exit_code = $this->checkDependencies() !== ExitCode::OK) return $exit_code;

	    self::env('DB_USER', 'web');

	    switch ($action) {
		    case 'add_user': {
			    // Add user
			    if (!empty($key_1)) {

			    	//TODO: check if role is valid
				    //TODO: check if site is valid
				    //TODO: check if mail is valid

				    echo $this->addUser($site, $role, $user_login, $user_pass, $user_email, $first_name, $last_name, $display_name);

				    //TODO: come intercettare se la creazione dell'utente non va a buon fine
			    }
			    else {
				    $message->error('Missing fields');
				    return ExitCode::USAGE;
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
	 * Add user command
	 *
	 * @param string $method the method to be performed.
	 * @param string $key_1 the first input key.
	 * @param string $key_2 the second input key.
	 *
	 * @return string
	 */
	protected function addUser(string $method, string $key_1, string $key_2): string
	{

		//TODO: how to contact DB

		/*
		INSERT INTO `databasename`.`wp_users` (`ID`, `user_login`, `user_pass`, `user_nicename`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`) VALUES ('4', 'demo', MD5('demo'), 'Your Name', 'test@yourdomain.com', 'http://www.test.com/', '2011-06-07 00:00:00', '', '0', 'Your Name');
		INSERT INTO `databasename`.`wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES (NULL, '4', 'wp_capabilities', 'a:1:{s:13:"administrator";s:1:"1";}');
		INSERT INTO `databasename`.`wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES (NULL, '4', 'wp_user_level', '10');
*/
		return shell_exec('aws s3 ' . $method . ' ' . $key_1 . ' ' . $key_2 . ' ' . ((!empty($this->profile)) ? ('--profile ' . $this->profile): ''));
	}
}
