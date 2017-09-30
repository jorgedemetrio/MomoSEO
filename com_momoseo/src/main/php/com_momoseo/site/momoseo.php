<?php
/*------------------------------------------------------------------------
# angelgirls.php - Angel Girls Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 || later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.angelgirls.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') || die('Restricted access');

//define('TRADUZIR_CHAR_DE','�������������������� ');
//define('TRADUZIR_CHAR_POR','AEIOUYAEIOUAEIOUAONC-');

// Added for Joomla 3.0
if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}

if(!defined('VERSAO_ANGELGIRLS')){
	define('VERSAO_ANGELGIRLS','1.0');
}

// Set the component css/js
$document = JFactory::getDocument();


$document->addStyleSheet('components/com_angelgirls/assets/css/bootstrap-theme.css?v='.VERSAO_ANGELGIRLS);
$document->addStyleSheet('components/com_angelgirls/assets/css/bootstrap.css?v='.VERSAO_ANGELGIRLS);
$document->addStyleSheet('components/com_angelgirls/assets/css/jquery.shadow.css?v='.VERSAO_ANGELGIRLS);
$document->addStyleSheet('components/com_angelgirls/assets/css/angelgirls.css?v='.VERSAO_ANGELGIRLS);



// $document->addStyleSheet('components/com_angelgirls/assets/css/jquery-ui.min.css');
// $document->addStyleSheet('components/com_angelgirls/assets/css/jquery-ui.structure.min.css');
// $document->addStyleSheet('components/com_angelgirls/assets/css/jquery-ui.theme.min.css');




JHtml::_('jquery.framework', false);
JHtml::_('jquery.ui');
//JHTML::_('behavior.tooltip');
$document->addScript('components/com_angelgirls/assets/js/jquery.mask.min.js?v='.VERSAO_ANGELGIRLS);
//$document->addScript('components/com_angelgirls/assets/js/bootstrap.min.js');
$document->addScript('components/com_angelgirls/assets/js/angelgirls.js?v='.VERSAO_ANGELGIRLS);
// $document->addScript('components/com_angelgirls/assets/js/jquery-ui.min.js');

// Require helper file
JLoader::register('AngelgirlsHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'angelgirls.php');

$exibirPopup =  $_COOKIE["popads"];
if($exibirPopup==null || !$exibirPopup){
	JFactory::getDocument()->addScriptDeclaration("
		var _pop = _pop || [];
		_pop.push(['siteId', 903837]);
		_pop.push(['minBid', 0.000000]);
		_pop.push(['popundersPerIP', 0]);
		_pop.push(['delayBetween', 0]);
		_pop.push(['default', false]);
		_pop.push(['defaultPerDay', 0]);
		_pop.push(['topmostLayer', false]);
		(function() {
			var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
			var s = document.getElementsByTagName('script')[0];
			pa.src = '//c1.popads.net/pop.js';
			pa.onerror = function() {
				var sa = document.createElement('script'); sa.type = 'text/javascript'; sa.async = true;
				sa.src = '//c2.popads.net/pop.js';
				s.parentNode.insertBefore(sa, s);
			}
			s.parentNode.insertBefore(pa, s);
		})();");
	setcookie("popads","1",(time()+(60*60*24*7)));//Uma semana
}

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Angelgirls
$controller = JControllerLegacy::getInstance('Angelgirls');

// Perform the request task
$controller->execute(JRequest::getCmd('task'));

$document->addScriptDeclaration('document.PathBaseComponent="' . JURI::base( true ) . '/components/com_angelgirls/";
		document.PathBase="' . JURI::base( true ) . '/";
		document.UrlLogin ="' . JRoute::_('index.php?option=com_users&view=login',false) . '";');

echo('
<div id="fb-root"></div>
  <div class="modalwindow fade" id="modalWindow">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="height: 35px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modalWindowtitle"></h4>
      </div>
      <div class="modal-body alert" id="modalWindowbody" style="padding-left:25px">
        
      </div>
      <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">Close</a>
        <a class="btn btn-primary" id="modalWindowok" href="'.JRoute::_('index.php?option=com_users&view=login',false).'"></a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade carregando" id="pleaseWaitDialog" class="display:none" data-backdrop="static" data-keyboard="false"><div class="modal-header"><h1>Processando...</h1></div><div class="modal-body"><div class="progress progress-striped active"><div class="bar" style="width: 100%;"></div></div></div></div>');

// Redirect if set by the controller
$controller->redirect();
///templates/protostar/css/template.css

unset($document->_scripts[JURI::root(true) . '/media/system/js/mootools-more.js']);
//unset($document->_scripts[JURI::root(true) . '/media/system/js/mootools-core.js']);
// unset($document->_scripts[JURI::root(true) . '/media/system/js/core.js']);
// unset($document->_scripts[JURI::root(true) . '/media/system/js/modal.js']);
// unset($document->_scripts[JURI::root(true) . '/media/system/js/caption.js']);
// unset($document->_scripts[JURI::root(true) . '/media/jui/js/jquery.min.js']);
//unset($document->_scripts[JURI::root(true) . '/media/jui/js/jquery-noconflict.js']);
// unset($document->_scripts[JURI::root(true) . '/media/jui/js/bootstrap.min.js']);
