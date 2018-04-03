<?php
use Aws\Credentials\CredentialProvider;

// Load credentials from an ini credential file. By default attempt to load the "default" profile from a file located at ~/.aws/credentials
$provider = CredentialProvider::ini();

// Cache the results in a memoize function to avoid loading and parsing the ini file on every API operation
$provider = CredentialProvider::memoize($provider);

return [
    'class' => 'fedemotta\awssdk\AwsSdk',
    'credentials' => $provider,
    'region' => 'eu-west-1',
	'version' => 'latest'
];     
