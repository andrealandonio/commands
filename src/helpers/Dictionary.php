<?php
namespace app\src\helpers;

class Dictionary {

	/**
	 * Decode site name by prefix
	 *
	 * @param string $site
	 *
	 * @return string
	 */
	public static function decodeSiteNameByPrefix(string $site): string {
		return strtoupper($site);
	}
}