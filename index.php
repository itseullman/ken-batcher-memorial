<?php

################################
# DEFINE SOME GLOBAL CONSTANTS #
#------------------------------#

/*
	Defined constants:
	TIMEZONE
	
		Url constants:
	BASE_WWW
	CSS_WWW
	JS_WWW
	IMG_WWW
	
		Directory constants:
	BASE_DIR
	CSS_DIR
	JS_DIR
	IMG_DIR
	VIEW_DIR
	MODULE_DIR
*/
	
	
// Define TIMEZONE
define('TIMEZONE', 'America/New_York');

// Define REQUEST_SCHEME
if ( (! empty($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ||
	(! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
	(! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') ) {
	define('REQUEST_SCHEME', 'https');
} else {
	define('REQUEST_SCHEME', 'http');
}

// Define BASE_WWW
if (isset($_SERVER['SERVER_NAME'])) {
	define('BASE_WWW', sprintf('%s://%s', REQUEST_SCHEME, $_SERVER['SERVER_NAME']));
} else {
	define('BASE_WWW', '');
}
// Define CSS_WWW
define('CSS_WWW', BASE_WWW . '/css');

// Define JS_WWW
define('JS_WWW', BASE_WWW . '/scripts');

// Define IMG_WWW
define('IMG_WWW', BASE_WWW . '/img');

// Define BASE_DIR
define('BASE_DIR', getcwd());



// Define CSS_DIR
define('CSS_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'css');

// Define JS_DIR
define('JS_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'scripts');

// Define IMG_DIR
define('IMG_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'img');

// Define VIEW_DIR
define('VIEW_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'views');

// Define MODULE_DIR
define('MODULE_DIR', BASE_DIR . DIRECTORY_SEPARATOR . 'modules');

#-------------------------------------#
# END OF DEFINE SOME GLOBAL CONSTANTS #
#######################################


// basename ensures that people can't sumbit something like
// page = ../../password.txt
// because that would become: page = password.txt
$page = (isset($_REQUEST['page']) && is_string($_REQUEST['page'])) ? basename($_REQUEST['page']) : '';



// Start a buffer to capture the output which
// will be injected into the body (i.e., {{main-body}})
// of the main.html template.
ob_start();

// Look for a $page corresponding to a .php module:
if (strlen($page) > 0 && file_exists(MODULE_DIR . DIRECTORY_SEPARATOR . $page . '.php')) {
	require_once(MODULE_DIR . DIRECTORY_SEPARATOR . $page . '.php');
	
// Look for a $page corresponding to an .html template:
} else {
	
	// The $page must not be empty 
	// and we're allowed to load the main template!
	if (strlen($page) === 0 || $page === 'main') {
		$page === 'home';
	}
	
	$template = '';
	if (file_exists(VIEW_DIR . DIRECTORY_SEPARATOR . $page . '.html')) {
		$template = file_get_contents(VIEW_DIR . DIRECTORY_SEPARATOR . $page . '.html');
	} else {
		$template = file_get_contents(VIEW_DIR . DIRECTORY_SEPARATOR . 'home.html');
	}
	// TODO: do replacements on placeholders in $template
	
	// Now output the template so that the output buffer
	// can grab a hold of it. Later we will insert
	// that output into the main.html template.
	echo $template;
	
}

// If we need to output HTML Headers, this is the place to do it,
// since at this point, no output has been sent to the browser

echo str_replace(
	'{{main-body}}', 	
	// Get the contents of the buffer,
	// and wipe the buffer clean.											// search
	ob_get_clean(), 														// replace
	file_get_contents(VIEW_DIR . DIRECTORY_SEPARATOR . 'main.html'),		// context
);












