<?php
return array(
	'_root_'  => 'welcome/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route

	'create-account' => 'authenticate/register',
	'privacy-policy' => 'pages/index/privacy_policy',
	'terms-of-services' => 'pages/index/tos',

	// Public player profiles
	'profile/(:segment)' => 'profile/view/$1',
);
