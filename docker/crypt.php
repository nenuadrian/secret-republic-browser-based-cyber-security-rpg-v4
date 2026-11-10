<?php
/**
 * Encryption key for Docker deployments (Fuel encrypts session cookies with it).
 *
 * The entrypoint copies this file to fuel/app/config/crypt.php when
 * SR_CRYPT_KEY is set, so sessions survive container rebuilds. Without
 * SR_CRYPT_KEY, Fuel generates a random key inside the container on first run.
 */
return array(
	'sodium' => array(
		'cipherkey' => (string) getenv('SR_CRYPT_KEY'),
	),
);
