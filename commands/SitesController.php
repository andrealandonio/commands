<?php
namespace app\commands;

use app\src\entities\SitesUser;
use app\src\Globals;
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
	 * @var string $role the user role
	 */
	public $role = 'author';

	/**
	 * @var string $username the user login
	 */
	public $username = '';

	/**
	 * @var string $password the user password
	 */
	public $password = '';

	/**
	 * @var string $mail the user mail
	 */
	public $mail = '';

	/**
	 * @var string $first_name the user first name
	 */
	public $first_name = '';

	/**
	 * @var string $last_name the user last name
	 */
	public $last_name = '';

	/**
	 * @var string $display_name the user display name
	 */
	public $display_name = '';

	/**
	 * Define controller options.
	 *
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID): array
	{
		return ['role', 'username', 'password', 'mail', 'first_name', 'last_name', 'display_name'];
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
		return ExitCode::OK;
	}

	/**
	 * This command perform what you have entered as action.
	 * Valid actions are:
	 * - add_user
	 *
	 * @param string $action the action to be performed.
	 * @param string $site the site to be used.
	 *
	 * @return int
	 */
    public function actionIndex(string $action, string $site = ''): int
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

	    // Check if mandatory services/packages are installed
	    if ($exit_code = $this->checkDependencies() !== ExitCode::OK) return $exit_code;

	    switch ($action) {
		    case 'add_user': {
			    // Add user
			    if (!empty($site) && in_array($site, Globals::BRANDS) && !empty($this->username) && !empty($this->mail)) {

			    	$sites_user = new SitesUser($site, $this->username, $this->mail);
			    	$sites_user->setPassword($this->password);
				    $sites_user->setRole($this->role);
				    $sites_user->setFirstName($this->first_name);
				    $sites_user->setLastName($this->last_name);
				    $sites_user->setDisplayName($this->display_name);

			    	//TODO: check if role is valid
				    //TODO: check if site is valid
				    //TODO: check if mail is valid

				    echo $this->addUser($site, $this->role, $this->username, $this->password, $this->mail, $this->first_name, $this->last_name, $this->display_name);

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
