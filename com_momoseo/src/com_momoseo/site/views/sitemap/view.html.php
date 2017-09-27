<?php
/*------------------------------------------------------------------------
# view.html.php - Angel Girls Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.angelgirls.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Temas View
 */
class AngelgirlsViewmodelo extends JViewLegacy
{
	/**
	 * Temas view display method
	 * @return void
	 */
	function display($tpl = null) 
	{


		// Set the document
		$this->setDocument();
		
	

		// Set the toolbar
		$this->addToolBar();
		

		// Display the template
		parent::display($tpl);


	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() 
	{
			

		$layout = JRequest::getVar('layout');
		$document = JFactory::getDocument();
		$pathway = JFactory::getApplication()->getPathway();


		$document->setMetadata('APPLICATION-NAME','Angel Girls');
		

		$objeto = JRequest::getVar('usuario');
		$descricao = 'Modelo ' . $objeto->nome;
		$pathway->addItem($descricao,'');
		$document->setTitle($descricao );
		$document->setDescription($objeto->meta_descricao);
		$document->setMetadata('Keywords', 'modelo,'.$objeto->nome.','.str_replace(' ', ',',$objeto->nome));

	}

	/**
	 * Method to set up the document properties
	 *
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$document = JFactory::getDocument();
		//$document->setTitle(JText::_('Angelgirls Manager - Administrator'));
	}
}
?>