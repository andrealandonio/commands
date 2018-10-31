<?php
namespace app\src\entities;

use app\src\Globals;
use app\src\helpers\Utilities;

class SitesUser {

	/**
	 * @var string $site the user site
	 */
	private $site = '';

	/**
	 * @var string $role the user role
	 */
	private $role = '';

	/**
	 * @var string $username the user login
	 */
	private $username = '';

	/**
	 * @var string $password the user password
	 */
	private $password = '';

	/**
	 * @var string $mail the user mail
	 */
	private $mail = '';

	/**
	 * @var string $name the user first name
	 */
	private $name = '';

	/**
	 * @var string $surname the user last name
	 */
	private $surname = '';

	/**
	 * @var string $alias the user display name
	 */
	private $alias = '';

	/**
	 * @var string $bio the user bio
	 */
	private $bio = '';

	/**
	 * @var string $job the user job
	 */
	private $job = '';

	/**
	 * @var int $type the user type
	 */
	private $type = '';

	/**
	 * SitesUser generic constructor.
	 */
	public function __construct() {
		$get_arguments = func_get_args();
		$number_of_arguments = func_num_args();

		if (method_exists($this, $method_name = '__construct' . $number_of_arguments)) {
			call_user_func_array(array($this, $method_name), $get_arguments);
		}
	}

	/**
	 * SitesUser 3 params constructor.
	 * Type:
	 * - use default role
	 * - generate password
	 * - use blank first and last name
	 * - use username as display name
	 *
	 * @param string $site
	 * @param string $username
	 * @param string $mail
	 */
	public function __construct3(string $site, string $username, string $mail) {
		$this->site = $site;
		$this->username = $username;
		$this->mail = $mail;
		$this->alias = $this->username;
	}

	/**
	 * SitesUser 5 params constructor.
	 * Type:
	 * - use blank first and last name
	 * - use username as display name
	 *
	 * @param string $site
	 * @param string $role
	 * @param string $username
	 * @param string $password
	 * @param string $mail
	 */
	public function __construct5(string $site, string $role, string $username, string $password, string $mail) {
		$this->site = $site;
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->alias = $this->username;
	}

	/**
	 * SitesUser 6 params constructor.
	 * Type:
	 * - use blank last name
	 * - use first name as display name
	 *
	 * @param string $site
	 * @param string $role
	 * @param string $username
	 * @param string $password
	 * @param string $mail
	 * @param string $name
	 */
	public function __construct6(string $site, string $role, string $username, string $password, string $mail, string $name) {
		$this->site = $site;
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->name = $name;
		$this->alias = $this->name;
	}

	/**
	 * SitesUser 7 params constructor.
	 * Type:
	 * - use first name + last name as display name
	 *
	 * @param string $site
	 * @param string $role
	 * @param string $username
	 * @param string $password
	 * @param string $mail
	 * @param string $name
	 * @param string $surname
	 */
	public function __construct7(string $site, string $role, string $username, string $password, string $mail, string $name, string $surname) {
		$this->site = $site;
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->name = $name;
		$this->surname = $surname;
		$this->alias = $this->name . ' ' . $this->surname;
	}

	/**
	 * SitesUser 78 params constructor.
	 *
	 * @param string $site
	 * @param string $role
	 * @param string $username
	 * @param string $password
	 * @param string $mail
	 * @param string $name
	 * @param string $surname
	 * @param string $alias
	 */
	public function __construct8(string $site, string $role, string $username, string $password, string $mail, string $name, string $surname, string $alias) {
		$this->site = $site;
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->name = $name;
		$this->surname = $surname;
		$this->alias = $alias;
	}

	/**
	 * Get site
	 *
	 * @return string
	 */
	public function getSite(): string {
		return $this->site;
	}

	/**
	 * Set site
	 *
	 * @param string $site
	 */
	public function setSite(string $site) {
		$this->site = $site;
	}

	/**
	 * Get role
	 *
	 * @return string
	 */
	public function getRole(): string {
		return $this->role;
	}

	/**
	 * Set role or use default
	 *
	 * @param string $role
	 */
	public function setRole(string $role) {
		if (empty($role)) {
			// Use default
			if (empty($this->site)) $this->role = Globals::DEFAULT_GLOBAL_ROLES;
			else $this->role = Globals::DEFAULT_BRANDS_ROLES[$this->site];
		}
		else {
			$this->role = $role;
		}
	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername(): string {
		return $this->username;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 */
	public function setUsername(string $username) {
		$this->username = $username;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword(): string {
		return $this->password;
	}

	/**
	 * Set password or generate it
	 *
	 * @param string $password
	 */
	public function setPassword(string $password) {
		if (empty($password)) {
			// Generate
			$this->password = Utilities::randomPassword();
		}
		else {
			$this->password = $password;
		}
	}

	/**
	 * Get mail
	 *
	 * @return string
	 */
	public function getMail(): string {
		return $this->mail;
	}

	/**
	 * Set mail
	 *
	 * @param string $mail
	 */
	public function setMail(string $mail) {
		$this->mail = $mail;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 */
	public function setName(string $name) {
		$this->name = $name;
	}

	/**
	 * Get surname
	 *
	 * @return string
	 */
	public function getSurname(): string {
		return $this->surname;
	}

	/**
	 * Set surname
	 *
	 * @param string $surname
	 */
	public function setSurname(string $surname) {
		$this->surname = $surname;
	}

	/**
	 * Get alias
	 *
	 * @return string
	 */
	public function getAlias(): string {
		return $this->alias;
	}

	/**
	 * Set alias
	 *
	 * @param string $alias
	 */
	public function setAlias(string $alias) {
		if (!empty($alias)) {
			// Use provide display name
			$this->alias = $alias;
		}
		else {
			// Use first name, last_name or username
			if (!empty($this->name) && !empty($this->surname)) $this->alias = $this->name . ' ' . $this->surname;
			else if (!empty($this->name)) $this->alias = $this->name;
			else if (!empty($this->username)) $this->alias = $this->username;
		}
	}

	/**
	 * Get bio
	 *
	 * @return string
	 */
	public function getBio(): string {
		return $this->bio;
	}

	/**
	 * Set bio
	 *
	 * @param string $bio
	 */
	public function setBio(string $bio) {
		$this->bio = $bio;
	}

	/**
	 * Get job
	 *
	 * @return string
	 */
	public function getJob(): string {
		return $this->job;
	}

	/**
	 * Set job
	 *
	 * @param string $job
	 */
	public function setJob(string $job) {
		$this->job = $job;
	}

	/**
	 * Get type
	 *
	 * @return int
	 */
	public function getType(): int {
		return $this->type;
	}

	/**
	 * Set type
	 *
	 * @param int $type
	 */
	public function setType(int $type) {
		$this->type = $type;
	}
}