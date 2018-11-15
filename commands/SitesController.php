<?php
namespace app\commands;

use app\src\Globals;
use app\src\entities\SitesUser;
use app\src\helpers\{Database, Dictionary};
use yii\console\{Controller, ExitCode};
use yii\validators\EmailValidator;
use app\components\behaviors\MessageBehavior;

/**
 * Manages sites utilities.
 *
 * Sample calls:
 * - cmd sites show_user_profile_types
 * - cmd sites show_user_profile_images
 * - cmd sites search_user vf --key=test
 * - cmd sites add_user vf --username=test --mail=test@mail.it --password=SECRET --name=Name --surname=Surname --alias="Name Surname" --bio="User bio" --job="Chief of chiefs" --images=1 --type=1
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
	 * @var string $name the user first name
	 */
	public $name = '';

	/**
	 * @var string $surname the user last name
	 */
	public $surname = '';

	/**
	 * @var string $alias the user display name
	 */
	public $alias = '';

	/**
	 * @var string $bio the user bio
	 */
	public $bio = '';

	/**
	 * @var string $job the user job
	 */
	public $job = '';

	/**
	 * @var int $type the user type
	 */
	public $type;

	/**
	 * @var int $images the user profile images count
	 */
	public $images = 1;

	/**
	 * Define controller options.
	 *
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID): array
	{
		return ['role', 'username', 'password', 'mail', 'name', 'surname', 'alias', 'bio', 'job', 'type', 'images'];
	}

	/**
	 * Define controller behaviors.
	 *
	 * @return array
	 */
	public function behaviors(): array
	{
		return [
			// Add named behavior "message" with configurations
			'message' => [
				'class' => MessageBehavior::className(),
				'controller' => $this
			]
		];
	}

	/**
	 * Check controller dependencies.
	 *
	 * @return int
	 */
	protected function checkDependencies(): int {
		return ExitCode::OK;
	}

	/**
	 * This command perform the index action.
	 *
	 * @param string $action the action to be performed. (values: add_user, show_user_profile_types, show_user_profile_images)
	 * @param string $key the site to be used (values: gq, glamour, lci, vf, vogue, wired) or the key to be searched.
	 *
	 * @return int
	 */
    public function actionIndex(string $action, string $key = ''): int
    {
	    /**
	     * @var MessageBehavior $message
	     */
	    $message = $this->getBehavior('message');

	    // Check if mandatory services/packages are installed
	    if ($exit_code = $this->checkDependencies() !== ExitCode::OK) return $exit_code;

	    switch ($action) {
		    case 'search_user': {
			    // Search user
			    if (!empty($key)) {
				    // Search user into database
				    $this->searchUser($key);

				    //TODO: come intercettare se la creazione dell'utente non va a buon fine
				    //TODO: echo recap of inserted data
				    //TODO: mettere esempio chiamata in how-to
			    }
			    else {
				    $message->error('Invalid key field');
				    return ExitCode::USAGE;
			    }
			    break;
		    }
		    case 'add_user': {
			    // Add user
			    if (!empty($key) && !empty($this->username) && !empty($this->mail)) {
				    // Check if site is valid
				    if (!in_array($key, Globals::BRANDS)) {
					    $message->error('Invalid site field');
					    return ExitCode::USAGE;
				    }

				    // Check if role is valid
				    if (!empty($role) && !in_array($role, Globals::ROLES)) {
					    $message->error('Invalid role field');
					    return ExitCode::USAGE;
				    }

				    // Check if mail is valid
				    $emailValidator = new EmailValidator();
				    if (!empty($this->mail) && !$emailValidator->validate($this->mail, $error)) {
				    	$message->error('Invalid mail field');
					    return ExitCode::USAGE;
				    }

					// Create user object
			    	$user = new SitesUser($key, $this->username, $this->mail);
				    $user->setPassword($this->password);
				    $user->setRole($this->role);
				    $user->setName($this->name);
				    $user->setSurname($this->surname);
				    $user->setAlias($this->alias);
				    $user->setBio($this->bio);
				    $user->setJob($this->job);
				    $user->setType($this->type);

			    	// Add user to database
				    $this->addUser($key, $user);

				    //TODO: come intercettare se la creazione dell'utente non va a buon fine
				    //TODO: echo recap of inserted data
				    //TODO: mettere esempio chiamata in how-to
			    }
			    else {
				    $message->error('Missing fields');
				    return ExitCode::USAGE;
			    }
			    break;
		    }
		    case 'show_user_profile_types': {
		    	// Show user profile types
			    $message->info('List of user profile types:');
			    $message->notice(print_r(Globals::USER_PROFILE_TYPES, true));

			    return ExitCode::OK;
		    }
		    case 'show_user_profile_images': {
			    // Show user profile images
			    $message->info('List of user profile images:');
			    $message->notice(print_r(Globals::USER_PROFILE_IMAGES, true));

			    return ExitCode::OK;
		    }
		    default: {
			    $message->error('Invalid action');
			    return ExitCode::USAGE;
		    }
	    }

	    return ExitCode::OK;
    }

	/**
	 * Search user
	 *
	 * @param string $key the key to be searched.
	 *
	 * @return void
	 */
	protected function searchUser(string $key)
	{
		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		try {
			// Create a database connection
			$db = Database::getInstance();
			$db->openConnection($key);

			// Get table prefix
			$table_prefix = env('DB_' . Dictionary::decodeSiteNameByPrefix($key) . '_TABLE_PREFIX');

			// Search if username or email already exists
			$users = $db->selectAll('SELECT ID, user_login, user_email, display_name FROM ' . $table_prefix . 'users WHERE user_login LIKE \'%' . addslashes($key) . '%\' OR user_email LIKE \'%' . $key . '%\'');
			if (empty($users)) {
				// User founded
				$message->info('Users founded');
				$message->row($users);
			}
			else {
				$message->info('No users founded');
			}
		}
		catch (\yii\db\Exception $e) {
			$message->error('Database error: ' . $e->getMessage());
		}
	}

	/**
	 * Add user
	 *
	 * @param string $key the site to be used.
	 * @param SitesUser $user the user to add.
	 *
	 * @return void
	 */
	protected function addUser(string $key, SitesUser $user)
	{
		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		try {
			// Create a database connection
			$db = Database::getInstance();
			$db->openConnection($key);

			// Get table prefix
			$table_prefix = env('DB_' . Dictionary::decodeSiteNameByPrefix($key) . '_TABLE_PREFIX');

			// Search if username or email already exists
			$users = $db->selectAll('SELECT ID, user_login, user_email, user_registered, display_name FROM ' . $table_prefix . 'users WHERE user_login LIKE \'%' . $user->getUsername() . '%\' OR user_email LIKE \'%' . $user->getMail() . '%\'');
			if (empty($users)) {
				// User not exists, create it
				$user_id = $db->insert('INSERT INTO ' . $table_prefix . 'users (user_login, user_pass, user_nicename, user_email, user_registered, user_status, display_name) VALUES (\'' . $user->getUsername() . '\', \'' . $user->getPassword() . '\', \'' . $user->getUsername() . '\', \'' . $user->getMail() . '\', \'' . date("Y-m-d H:i:s") . '\', 0, \'' . addslashes($user->getAlias()) . '\')');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'wp_capabilities\', \'' . Dictionary::decodeSiteCapabilitiesByRole($user->getRole()) . '\')');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'wp_user_level\', \'' . Dictionary::decodeSiteUserLevelByRole($user->getRole()) . '\')');
				if (!empty($user->getName())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'first_name\', \'' . addslashes($user->getName()) . '\')');
				if (!empty($user->getSurname())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'last_name\', \'' . addslashes($user->getSurname()) . '\')');
				if (!empty($user->getAlias())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'nickname\', \'' . addslashes($user->getAlias()) . '\')');
				if (!empty($user->getBio())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'description\', \'' . addslashes($user->getBio()) . '\')');
				if (!empty($user->getJob())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_job\', \'' . addslashes($user->getJob()) . '\')');
				if (!empty($user->getType())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_type\', \'' . $user->getType() . '\')');

				// Retrieve user profile images paths
				$profile_images_paths = Globals::USER_PROFILE_IMAGES[$key];

				// Loop over user profile images paths
				foreach ($profile_images_paths as $profile_images_path_key => $profile_images_path_value) {
					$value = str_replace('#USERNAME', $user->getUsername(), $profile_images_path_value);

					if ($key === 'glamour' && $user->getType() === 7) {
						// Force brandmag image
						$value = str_replace('avatar', 'brandmag', $value);
					}

					// Loop over user profile images count
					for ($i = 1; $i <= $this->images; $i++) {
						$key = str_replace('#COUNT', $i, $profile_images_path_key);
						$value = str_replace('#COUNT', $i, $value);

						// Insert user profile image meta
						$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'' . $key . '\', \'' . $value . '\'');
					}
				}
			}
			else {
				// User already exists
				$message->error('User already exists');
				$message->row($users);
			}
		}
		catch (\yii\db\Exception $e) {
			$message->error('Database error: ' . $e->getMessage());
		}
	}
}
