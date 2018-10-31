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

	/**
	 * Decode site capabilities by role
	 *
	 * @param string $role
	 *
	 * @return string
	 */
	public static function decodeSiteCapabilitiesByRole(string $role): string {
		switch ($role) {
			case 'administrator': {
				return 'a:1:{s:13:"administrator";b:1;}';
			}
			case 'editor': {
				return 'a:1:{s:6:"editor";b:1;}';
			}
			case 'author': {
				return 'a:1:{s:6:"author";b:1;}';
			}
			case 'contributor': {
				return 'a:1:{s:11:"contributor";b:1;}';
			}
			default: {
				return 'a:1:{s:10:"subscriber";b:1;}';
			}
		}
	}

	/**
	 * Decode site user level by role
	 *
	 * @param string $role
	 *
	 * @return int
	 */
	public static function decodeSiteUserLevelByRole(string $role): int {
		switch ($role) {
			case 'administrator': {
				return 10;
			}
			case 'editor': {
				return 7;
			}
			case 'author': {
				return 2;
			}
			case 'contributor': {
				return 1;
			}
			default: {
				return 0;
			}
		}
	}
}