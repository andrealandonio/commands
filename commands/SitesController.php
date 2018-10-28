<?php
namespace app\commands;

use app\src\Globals;
use app\src\entities\SitesUser;
use app\src\helpers\{Database, Dictionary};
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
	public $role = '';

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
			    if (!empty($site) && !empty($this->username) && !empty($this->mail)) {
				    // Check if site is valid
				    if (!in_array($site, Globals::BRANDS)) {
					    $message->error('Invalid site field');
					    return ExitCode::USAGE;
				    }

				    // Check if role is valid
				    if (!empty($role) && !in_array($role, Globals::ROLES)) {
					    $message->error('Invalid role field');
					    return ExitCode::USAGE;
				    }

				    // Check if mail is valid
				    if (1==0) {
				    	//TODO: check mail
					    $message->error('Invalid mail field');
					    return ExitCode::USAGE;
				    }

					// Create user object
			    	$user = new SitesUser($site, $this->username, $this->mail);
				    $user->setPassword($this->password);
				    $user->setRole($this->role);
				    $user->setFirstName($this->first_name);
				    $user->setLastName($this->last_name);
				    $user->setDisplayName($this->display_name);

			    	// Add user to database
				    echo $this->addUser($site, $user);

				    //TODO: come intercettare se la creazione dell'utente non va a buon fine
				    //TODO: echo recap of inserted data
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
	 * Add user
	 *
	 * @param string $site the site to be used.
	 * @param SitesUser $user the user to add.
	 *
	 * @return string
	 */
	protected function addUser(string $site, SitesUser $user): string
	{
		try {
			// Create a database connection
			$db = Database::getInstance();
			$db->openConnection($site);

			// Get table prefix
			$table_prefix = env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_TABLE_PREFIX');

			// Search if username or email already exists
			$users = $db->selectAll('SELECT * FROM ' . $table_prefix . 'users WHERE user_login LIKE \'%' . $user->getUsername() . '%\' OR user_email LIKE \'%' . $user->getMail() . '%\'');
			if (empty($users)) {
				// User not exists, create it
				$user_id = $db->insert('INSERT INTO ' . $table_prefix . 'users (user_login, user_pass, user_nicename, user_email, user_registered, user_status, display_name) VALUES (\'' . $user->getUsername() . '\', \'' . $user->getPassword() . '\', \'' . $user->getUsername() . '\', \'' . $user->getMail() . '\', \'' . date("Y-m-d H:i:s") . '\', 0, \'' . $user->getDisplayName() . '\')');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'wp_capabilities\', \'' . ROLE . '\'');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'wp_user_level\', \'' . ROLE . '\'');
				if (!empty($user->getFirstName())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'first_name\', \'' . $user->getFirstName() . '\'');
				if (!empty($user->getLastName())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'last_name\', \'' . $user->getLastName() . '\'');
				if (!empty($user->getDisplayName())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'nickname\', \'' . $user->getDisplayName() . '\'');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'description\', \'' . ROLE . '\'');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_job\', \'' . ROLE . '\'');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_type\', \'' . ROLE . '\'');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_avatar_big\', \'' . ROLE . '\'');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_avatar_small\', \'' . ROLE . '\'');
			}
			else {
				//TODO: display values
				return 'User already exists';
			}
		}
		catch (\yii\db\Exception $e) {
			return('Database error: ' . $e->getMessage());
		}

		/*

		INSERT INTO `databasename`.`wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES (NULL, '4', 'wp_capabilities', 'a:1:{s:13:"administrator";s:1:"1";}');
		INSERT INTO `databasename`.`wp_usermeta` (`umeta_id`, `user_id`, `meta_key`, `meta_value`) VALUES (NULL, '4', 'wp_user_level', '10');
*/
		//return shell_exec('aws s3 ' . $method . ' ' . $key_1 . ' ' . $key_2 . ' ' . ((!empty($this->profile)) ? ('--profile ' . $this->profile): ''));
	}
}
