<?php
// No direct access to this file



error_reporting(E_ALL);

define('_JEXEC', 1);
define('BASEPATH',realpath(dirname(__FILE__).'/../../main/php/com_momoseo'));
define('JOOMLA_PATH',realpath(dirname(__FILE__).'/../../main/php/'));
define('JOOMLA_ADMIN_PATH',realpath(dirname(__FILE__).'/../../main/php/'));
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REQUEST_METHOD'] = 'GET';

if (file_exists(JOOMLA_ADMIN_PATH . '/defines.php'))
{
	include_once JOOMLA_ADMIN_PATH . '/defines.php';
}

if (!defined('_JDEFINES'))
{
	define('JPATH_BASE', BASEPATH);
	require_once 'includes/defines.php';
}

require_once 'includes/framework.php';
require_once 'includes/helper.php';
require_once 'includes/toolbar.php';
define('JPATH_COMPONENT',JOOMLA_ADMIN_PATH.'/components/com_momoseo');
$app = JFactory::getApplication('administrator');
//include BASEPATH.'/controller.php';