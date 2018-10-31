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

	// User profile types
	const USER_PROFILE_TYPES = array(
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

	// User profile images
	const USER_PROFILE_IMAGES = array(
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
			'profile_image' => 'http://images.vanityfair.it/users/profile/#USERNAME#.jpg',
		),
		'vogue' => array(
			'profile_image' => 'https://images.vogue.it/users/profile/#USERNAME#.jpg',
			'profile_avatar' => 'https://images.vogue.it/users/avatar/#USERNAME#.jpg',
		),
		'wired' => array(
			'profile_image' => 'https://images.wired.it/avatar/big/#USERNAME#.jpg',
			'profile_avatar' => 'https://images.wired.it/avatar/small/#USERNAME#.jpg',
		)
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