<?php
namespace app\commands;

use app\src\Globals;
use app\src\entities\SitesUser;
use app\src\helpers\{Database, Dictionary, Utilities};
use yii\console\{Controller, ExitCode};
use yii\validators\EmailValidator;
use app\components\behaviors\MessageBehavior;

/**
 * Manages sites utilities.
 *
 * Sample calls:
 * - cmd sites show_user_profile_types
 * - cmd sites show_user_profile_images_path
 * - cmd sites show_user_profile_images_size
 * - cmd sites search_user vf --key=test
 * - cmd sites add_user vf --username=test --mail=test@mail.it --password=SECRET --name=Name --surname=Surname --alias="Name+Surname" --bio="User+bio" --job="Job+description" --role=author --type=1 --images=1 --avatar=1
 * - cmd sites add_user glamour --username=bm --mail=bm@brandmag.glamour.it --name=Bm --alias="Bm" --role=author --type=7 --images=0 --avatar=1
 *
 * @author Andrea Landonio <landonio.andrea@gmail.com>
 * @since 1.0
 */
class SitesController extends Controller
{
	/**
	 * @var string $key the search keyword (eg: --key=test)
	 */
	public $key = '';

	/**
	 * @var string $role the user role (eg: --role=author)
	 */
	public $role = '';

	/**
	 * @var string $username the user login (eg: --username=test)
	 */
	public $username = '';

	/**
	 * @var string $password the user password (eg: --password=SECRET)
	 */
	public $password = '';

	/**
	 * @var string $mail the user mail (eg: --mail=test@mail.it)
	 */
	public $mail = '';

	/**
	 * @var string $name the user first name (eg: --name=Name)
	 */
	public $name = '';

	/**
	 * @var string $surname the user last name (eg: --surname=Surname)
	 */
	public $surname = '';

	/**
	 * @var string $alias the user display name (eg: --alias="Name+Surname")
	 */
	public $alias = '';

	/**
	 * @var string $bio the user bio (eg: --bio="User+bio")
	 */
	public $bio = '';

	/**
	 * @var string $job the user job (eg: --job="Job+description")
	 */
	public $job = '';

	/**
	 * @var int $type the user type (eg: --type=1)
	 */
	public $type;

	/**
	 * @var int $images the user profile images count (eg: --images=1)
	 */
	public $images = 0;

	/**
	 * @var int $avatar the user profile avatar count (eg: --avatar=1)
	 */
	public $avatar = 0;

	/**
	 * Define controller options.
	 *
	 * @param string $actionID
	 *
	 * @return array|string[]
	 */
	public function options($actionID): array
	{
		return ['key', 'role', 'username', 'password', 'mail', 'name', 'surname', 'alias', 'bio', 'job', 'type', 'images', 'avatar'];
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
	 * Sample calls:
	 * - cmd sites show_user_profile_types
	 * - cmd sites show_user_profile_images_path
	 * - cmd sites show_user_profile_images_size
	 * - cmd sites search_user vf --key=test
	 * - cmd sites add_user vf --username=test --name=Name --surname=Surname --role=author --type=2 --images=1 --avatar=1 (compact version)
	 * - cmd sites add_user vf --username=test --mail=test@mail.it --password=SECRET --name=Name --surname=Surname --alias="Name+Surname" --bio="User+bio" --job="Job+description" --role=author --type=2 --images=1 --avatar=1
	 * - cmd sites add_user glamour --username=bm --mail=bm@brandmag.glamour.it --name=Bm --alias="Bm" --role=author --type=7 --images=0 --avatar=1
	 *
	 * @param string $action the action to be performed. (values: add_user, search_user, show_user_profile_types, show_user_profile_images_path, show_user_profile_images_size)
	 * @param string $site the site to be used (values: gq, glamour, lci, vf, vogue, wired)
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
		    case 'search_user': {
			    // Search user
			    if (!empty($site) && !empty($this->key)) {
				    // Search user into database
				    $this->searchUser($site, $this->key);
			    }
			    else {
				    $message->error('Missing fields');
				    return ExitCode::USAGE;
			    }
			    break;
		    }
		    case 'add_user': {
			    // Add user
			    if (!empty($site) && !empty($this->username)) {
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
				    $emailValidator = new EmailValidator();
				    if (!empty($this->mail)) {
				    	if (!$emailValidator->validate($this->mail, $error)) {
						    $message->error('Invalid mail field');
						    return ExitCode::USAGE;
					    }
				    }
				    else {
				    	// Generate email by site
					    $this->mail = Dictionary::composeMailBySite($site, $this->username);
				    }

					// Create user object
			    	$user = new SitesUser($site, $this->username, $this->mail);
				    $user->setPassword($this->password);
				    $user->setRole($this->role);
				    $user->setName(Utilities::cleanTexts($this->name));
				    $user->setSurname(Utilities::cleanTexts($this->surname));
				    $user->setAlias(Utilities::cleanTexts($this->alias));
				    $user->setBio(Utilities::cleanTexts($this->bio));
				    $user->setJob(Utilities::cleanTexts($this->job));
				    $user->setType($this->type);

			    	// Add user to database
				    $this->addUser($site, $user);
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
		    case 'show_user_profile_images_path': {
			    // Show user profile images path
			    $message->info('List of user profile images path:');
			    $message->notice(print_r(Globals::USER_PROFILE_IMAGES_PATH, true));

			    return ExitCode::OK;
		    }
		    case 'show_user_profile_images_size': {
			    // Show user profile images path
			    $message->info('List of user profile images size:');
			    $message->notice(print_r(Globals::USER_PROFILE_IMAGES_SIZE, true));

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
	 * @param string $site the site to be used.
	 * @param string $key the key to be searched.
	 *
	 * @return void
	 */
	protected function searchUser(string $site, string $key)
	{
		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		try {
			// Create a database connection
			$db = Database::getInstance();
			$db->openConnection($site);

			// Get table prefix
			$table_prefix = env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_TABLE_PREFIX');

			// Search if username or email already exists
			$users = $db->selectAll('SELECT ID, user_login, user_email, display_name FROM ' . $table_prefix . 'users WHERE user_login LIKE \'%' . addslashes($key) . '%\' OR user_email LIKE \'%' . $key . '%\'');
			if (!empty($users)) {
				// User founded
				$message->info('Users founded');
				foreach ($users as $user) $message->row($user);
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
	 * @param string $site the site to be used.
	 * @param SitesUser $user the user to add.
	 *
	 * @return void
	 */
	protected function addUser(string $site, SitesUser $user)
	{
		/**
		 * @var MessageBehavior $message
		 */
		$message = $this->getBehavior('message');

		try {
			// Create a database connection
			$db = Database::getInstance();
			$db->openConnection($site);

			// Get table prefix
			$table_prefix = env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_TABLE_PREFIX');

			// Search if username or email already exists
			$users = $db->selectAll('SELECT ID, user_login, user_email, display_name FROM ' . $table_prefix . 'users WHERE user_login = \'' . $user->getUsername() . '\' OR user_email = \'' . $user->getMail() . '\'');
			if (empty($users)) {
				// User not exists, create it
				$user_id = $db->insert('INSERT INTO ' . $table_prefix . 'users (user_login, user_pass, user_nicename, user_email, user_registered, user_status, display_name) VALUES (\'' . $user->getUsername() . '\', \'' . md5($user->getPassword()) . '\', \'' . $user->getUsername() . '\', \'' . $user->getMail() . '\', \'' . date("Y-m-d H:i:s") . '\', 0, \'' . addslashes($user->getAlias()) . '\')');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'wp_capabilities\', \'' . Dictionary::decodeSiteCapabilitiesByRole($user->getRole()) . '\')');
				$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'wp_user_level\', \'' . Dictionary::decodeSiteUserLevelByRole($user->getRole()) . '\')');
				if (!empty($user->getName())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'first_name\', \'' . addslashes($user->getName()) . '\')');
				if (!empty($user->getSurname())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'last_name\', \'' . addslashes($user->getSurname()) . '\')');
				if (!empty($user->getAlias())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'nickname\', \'' . addslashes($user->getAlias()) . '\')');
				if (!empty($user->getBio())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'description\', \'' . addslashes($user->getBio()) . '\')');
				if (!empty($user->getJob())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_job\', \'' . addslashes($user->getJob()) . '\')');
				if (!empty($user->getType())) $db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'profile_type\', \'' . $user->getType() . '\')');

				// Retrieve user profile images paths
				$profile_images_paths = Globals::USER_PROFILE_IMAGES_PATH[$site];

				// Loop over user profile images paths
				foreach ($profile_images_paths as $profile_images_path_key => $profile_images_path_value) {
					$value = str_replace('#USERNAME#', $user->getUsername(), $profile_images_path_value);

					// Manage avatar
					if ($this->avatar != 0 && strpos($profile_images_path_key, 'profile_avatar') !== false) {
						if ($site === 'glamour' && $user->getType() === 7) {
							// Force brandmag image
							$value = str_replace('avatar', 'brandmag', str_replace('.jpg', '.png', $value));
						}

						// Insert user profile avatar meta
						$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'' . $profile_images_path_key . '\', \'' . $value . '\')');
					}

					// Manage images
					if ($this->images != 0 && strpos($profile_images_path_key, 'profile_image_#COUNT#') !== false) {
						for ($i = 1; $i <= $this->images; $i++) {
							$key = str_replace('#COUNT#', $i, $profile_images_path_key);
							$value = str_replace('#COUNT#', $i, $value);

							// Insert user profile image meta
							$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'' . $key . '\', \'' . $value . '\')');
						}
					}
					elseif ($this->images != 0 && strpos($profile_images_path_key, 'profile_image') !== false) {
						// Insert user profile image meta
						$db->insert('INSERT INTO ' . $table_prefix . 'usermeta (user_id, meta_key, meta_value) VALUES (' . $user_id . ', \'' . $profile_images_path_key . '\', \'' . $value . '\')');
					}
				}

				// User created
				$message->info('User created');
				$message->row(array($user_id, $user->getUsername(), $user->getPassword(), $user->getMail(), $user->getAlias()));
			}
			else {
				// User already exists
				$message->error('User already exists');
				foreach ($users as $user) $message->row($user);
			}
		}
		catch (\yii\db\Exception $e) {
			$message->error('Database error: ' . $e->getMessage());
		}
	}
}
