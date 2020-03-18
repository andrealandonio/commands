<?php
namespace app\src;

class Globals {

	// Brands
	const BRANDS = array(
		'ad',
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

	// User profile types
	const USER_PROFILE_TYPES = array(
		'ad' => array(
			1 => 'subscriber',
			2 => 'contributor',
			9 => 'redazione',
		),
		'gq' => array(
			1 => 'subscriber',
			2 => 'contributor',
			9 => 'redazione',
		),
		'glamour' => array(
			2 => 'contributor / beauty reporter',
			4 => 'glam setter',
			7 => 'brandmag',
			9 => 'redazione',
		),
		'lci' => array(
			1 => 'subscriber',
			2 => 'contributor',
			9 => 'redazione',
		),
		'vf' => array(
			1 => 'subscriber',
			2 => 'contributor',
			7 => 'vanity star',
			9 => 'redazione',
		),
		'vogue' => array(
			2 => 'contributor',
			4 => 'photographer',
			7 => 'envoy',
			9 => 'redazione',
		),
		'wired' => array(
			1 => 'subscriber',
			2 => 'contributor',
			9 => 'redazione',
		)
	);

	// User profile images path
	const USER_PROFILE_IMAGES_PATH = array(
		'ad' => array(
			'profile_image' => 'https://adtoday.it/avatar/big/#USERNAME#.jpg'
		),
		'gq' => array(
			'profile_image' => 'https://img.gqitalia.it/avatar/big/#USERNAME#.jpg',
			'profile_avatar' => 'https://img.gqitalia.it/avatar/small/#USERNAME#.jpg',
		),
		'glamour' => array(
			'profile_image_#COUNT#' => 'https://images.glamour.it/users/profile/#USERNAME#/#USERNAME#_#COUNT#.jpg',
			'profile_avatar' => 'https://images.glamour.it/users/avatar/#USERNAME#.jpg',
		),
		'lci' => array(
			'profile_image' => 'https://images.lacucinaitaliana.it/users/profile/#USERNAME#.jpg',
		),
		'vf' => array(
			'profile_image' => 'https://images.vanityfair.it/users/profile/#USERNAME#.jpg',
		),
		'vogue' => array(
			'profile_image' => 'https://images.vogue.it/users/profile/#USERNAME#.jpg',
			'profile_avatar' => 'https://images.vogue.it/users/avatar/#USERNAME#.jpg',
		),
		'wired' => array(
			'profile_image' => 'https://images.wired.it/avatar/big/#USERNAME#.jpg',
		)
	);

	// User profile images size
	const USER_PROFILE_IMAGES_SIZE = array(
		'ad' => array(
			'profile_image' => '300x300',
		),
		'gq' => array(
			'profile_image' => '200x200',
			'profile_avatar' => '44x44',
		),
		'glamour' => array(
			'profile_image_#COUNT#' => '430x430',
			'profile_avatar' => '60x60',
		),
		'lci' => array(
			'profile_image' => '300x300',
		),
		'vf' => array(
			'profile_image' => '280x280',
		),
		'vogue' => array(
			'profile_image' => '300x450',
			'profile_avatar' => '100x100',
		),
		'wired' => array(
			'profile_image' => '300x300',
		)
	);


	// Default global and brands roles
	const DEFAULT_GLOBAL_ROLES = 'author';
	const DEFAULT_BRANDS_ROLES = array(
		'ad' => 'author',
		'gq' => 'author',
		'glamour' => 'author',
		'lci' => 'author',
		'vf' => 'author',
		'vogue' => 'author',
	);

	// Generic info
	const DEFAULT_PASSWORD_LENGTH = 16;
}