<?php
// No direct access to this file

error_reporting(E_ALL);

define('_JEXEC', 1);
define('BASEPATH',realpath(dirname(__FILE__).'/../'));
define('JOOMLA_PATH',realpath(dirname(__FILE__).'/../'));
define('JOOMLA_ADMIN_PATH',realpath(dirname(__FILE__).'/../administrator'));
define('JDEBUG', 0);

$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';

$parts = explode(DIRECTORY_SEPARATOR, JPATH_BASE);

// Defines.
define('JPATH_ROOT',          implode(DIRECTORY_SEPARATOR, $parts));
define('JPATH_SITE',          JPATH_ROOT);
define('JPATH_CONFIGURATION', JPATH_ROOT);
define('JPATH_ADMINISTRATOR', JPATH_ROOT . DIRECTORY_SEPARATOR . 'administrator');
define('JPATH_LIBRARIES',     JPATH_ROOT . DIRECTORY_SEPARATOR . 'libraries');
define('JPATH_PLUGINS',       JPATH_ROOT . DIRECTORY_SEPARATOR . 'plugins');
define('JPATH_INSTALLATION',  JPATH_ROOT . DIRECTORY_SEPARATOR . 'installation');
define('JPATH_THEMES',        JPATH_BASE . DIRECTORY_SEPARATOR . 'templates');
define('JPATH_CACHE',         JPATH_BASE . DIRECTORY_SEPARATOR . 'cache');
define('JPATH_MANIFESTS',     JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'manifests');



//require_once JPATH_BASE . '/includes/framework.php';
//require_once JPATH_BASE . '/includes/helper.php';
//require_once JPATH_BASE . '/includes/toolbar.php';
//define('JPATH_COMPONENT',JOOMLA_ADMIN_PATH.'/components/com_momoseo');
//$app = JFactory::getApplication('administrator');
//include BASEPATH.'/controller.php';



class JControllerLegacy{
	
}

class JFactory{
	function getDbo (){}
}

function jimport ($p) {
	echo $p;
}