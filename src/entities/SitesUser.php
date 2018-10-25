<?php
namespace app\src\entities;

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
	 * @var string $first_name the user first name
	 */
	private $first_name = '';

	/**
	 * @var string $last_name the user last name
	 */
	private $last_name = '';

	/**
	 * @var string $display_name the user display name
	 */
	private $display_name = '';

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
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->first_name = '';
		$this->last_name = '';
		$this->display_name = $this->username;
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
		$this->first_name = '';
		$this->last_name = '';
		$this->display_name = $this->username;
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
	 * @param string $first_name
	 */
	public function __construct6(string $site, string $role, string $username, string $password, string $mail, string $first_name) {
		$this->site = $site;
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->first_name = $first_name;
		$this->last_name = '';
		$this->display_name = $this->first_name;
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
	 * @param string $first_name
	 * @param string $last_name
	 */
	public function __construct7(string $site, string $role, string $username, string $password, string $mail, string $first_name, string $last_name) {
		$this->site = $site;
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->display_name = $this->first_name . ' ' . $this->last_name;
	}

	/**
	 * SitesUser 78 params constructor.
	 *
	 * @param string $site
	 * @param string $role
	 * @param string $username
	 * @param string $password
	 * @param string $mail
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $display_name
	 */
	public function __construct8(string $site, string $role, string $username, string $password, string $mail, string $first_name, string $last_name, string $display_name) {
		$this->site = $site;
		$this->role = $role;
		$this->username = $username;
		$this->password = $password;
		$this->mail = $mail;
		$this->first_name = $first_name;
		$this->last_name = $last_name;
		$this->display_name = $display_name;
	}

	/**
	 * Get site
	 *
	 * @return string
	 */
	public function getSite() {
		return $this->site;
	}

	/**
	 * Set site
	 *
	 * @param string $site
	 */
	public function setSite($site) {
		$this->site = $site;
	}

	/**
	 * Get role
	 *
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * Set role or use default
	 *
	 * @param string $role
	 */
	public function setRole($role) {
		$this->role = $role;
	}

	/**
	 * Get username
	 *
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Set username
	 *
	 * @param string $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * Get password
	 *
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Set password or generate it
	 *
	 * @param string $password
	 */
	public function setPassword($password) {
		if (empty($password)) {
			// Generate
			$this->password = 'generate';
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
	public function getMail() {
		return $this->mail;
	}

	/**
	 * Set mail
	 *
	 * @param string $mail
	 */
	public function setMail($mail) {
		$this->mail = $mail;
	}

	/**
	 * Get first_name
	 *
	 * @return string
	 */
	public function getFirstName() {
		return $this->first_name;
	}

	/**
	 * Set first_name
	 *
	 * @param string $first_name
	 */
	public function setFirstName($first_name) {
		$this->first_name = $first_name;
	}

	/**
	 * Get last_name
	 *
	 * @return string
	 */
	public function getLastName() {
		return $this->last_name;
	}

	/**
	 * Set last_name
	 *
	 * @param string $last_name
	 */
	public function setLastName($last_name) {
		$this->last_name = $last_name;
	}

	/**
	 * Get display_name
	 *
	 * @return string
	 */
	public function getDisplayName() {
		return $this->display_name;
	}

	/**
	 * Set display_name
	 *
	 * @param string $display_name
	 */
	public function setDisplayName($display_name) {
		$this->display_name = $display_name;
	}
}