<?php
namespace app\src\helpers;

use app\src\Globals;

class Utilities {

	/**
	 * Generate random password
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	public static function randomPassword(int $length = Globals::DEFAULT_PASSWORD_LENGTH): string {
		$password = array();

		// Define available chars
		$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

		// Put the alphabet length -1 in cache
		$alphabet_length = strlen($alphabet) - 1;

		// Loop over desired length
		for ($i = 0; $i < $length; $i++) {
			$n = rand(0, $alphabet_length);
			$password[] = $alphabet[$n];
		}

		// Return the password array as string
		return implode($password);
	}

	/**
	 * Remove temporary spacer from texts
	 *
	 * @param string $text
	 *
	 * @return mixed
	 */
	public static function cleanTexts(string $text) {
		return str_replace('+', ' ', $text);
	}
}