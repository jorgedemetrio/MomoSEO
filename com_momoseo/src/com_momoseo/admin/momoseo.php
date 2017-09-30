<?php
/*------------------------------------------------------------------------
# angelgirls.php - Angel Girls Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.angelgirls.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Added for Joomla 3.0
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_angelgirls')){
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// Load cms libraries
JLoader::registerPrefix('J', JPATH_PLATFORM . '/cms');
// Load joomla libraries without overwrite
JLoader::registerPrefix('J', JPATH_PLATFORM . '/joomla',false);

// require helper files
JLoader::register('AngelgirlsHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'angelgirls.php');

// import joomla controller library
jimport('joomla.application.component.controller');

// Add CSS file for all pages
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_angelgirls/assets/css/angelgirls.css');
$document->addScript('components/com_angelgirls/assets/js/angelgirls.js');
$document->addScript('components/com_angelgirls/assets/js/jquery.mask.min.js');



// Get an instance of the controller prefixed by Angelgirls
$controller = JControllerLegacy::getInstance('Angelgirls');

//jimport('joomla.filesystem.file');
//if($controller = JFile::makeSafe(JRequest::getWord('controller'))) {
//    $path = JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controller.'.php';
//    if (file_exists($path)) {
//        require_once $path;
//    } else {
//        $controller = '';
//    }
//}


// Create the controller
//$controller_name = 'AngelGirlsController'.ucfirst($controller);
//$controller	= new $controller_name();


// Perform the Request task
//$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

?>