<?php
namespace app\src;

class Globals {

	// Brands
	const BRANDS = array(
		'gq',
		'glamour',
		'lci',
		'vf',
		'vogue',
		'wired'
	);

	// User roles
	const ROLES = array(
		'administrator',
		'editor',
		'author',
		'contributor',
		'subscriber'
	);

	// Default global and brands roles
	const DEFAULT_GLOBAL_ROLES = 'author';
	const DEFAULT_BRANDS_ROLES = array(
		'gq' => 'contributor',
		'glamour' => 'author',
		'lci' => 'author',
		'vf' => 'author',
		'vogue' => 'author',
		'wired' => 'author'
	);

	// Generic info
	const DEFAULT_PASSWORD_LENGTH = 16;
}