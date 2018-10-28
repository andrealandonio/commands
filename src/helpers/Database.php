<?php
namespace app\src\helpers;

use yii\db\Connection;

class Database {

	/**
	 * Class instance
	 *
	 * @var Database $instance
	 */
	private static $instance = null;

	/**
	 * @var Connection $db the db connection
	 */
	private $db;

	/**
	 * Database constructor.
	 */
	private function __construct() {
		// Do nothing
	}

	/**
	 * Return class instance
	 *
	 * @return Database
	 */
	public static function getInstance() {
		if (self::$instance == null) {
			$class = __CLASS__;
			self::$instance = new $class;
		}

		return self::$instance;
	}

	/**
	 * Open connection
	 *
	 * @param string $site
	 *
	 * @throws \yii\db\Exception
	 */
	public function openConnection(string $site) {
		if (!empty(env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_DSN'))) {
			// Create the database connection
			$this->db = new Connection([
				'dsn' => env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_DSN'),
				'username' => env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_USERNAME'),
				'password' => env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_PASSWORD'),
				'charset' => env('DB_' . Dictionary::decodeSiteNameByPrefix($site) . '_CHARSET')
			]);

			// Open the database connection
			$this->db->open();
		}
	}

	/**
	 * Select all
	 *
	 * @param string $query
	 *
	 * @return array
	 * @throws \yii\db\Exception
	 */
	public function selectAll(string $query) {
		$command = $this->db->createCommand($query);
		return $command->queryAll();
	}

	/**
	 * Execute query
	 *
	 * @param string $query
	 *
	 * @return int
	 * @throws \yii\db\Exception
	 */
	public function insert(string $query) {
		$command = $this->db->createCommand($query);
		$command->execute();

		return $this->db->lastInsertID;
	}
}