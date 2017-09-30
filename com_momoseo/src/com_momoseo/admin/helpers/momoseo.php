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

/**
 * Angelgirls component helper.
 */
abstract class AngelgirlsHelper
{
	/**
	 *	Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JHtmlSidebar::addEntry(JText::_('Angelgirls'), 'index.php?option=com_angelgirls&view=angelgirls', $submenu == 'angelgirls');
		JHtmlSidebar::addEntry(JText::_('Galerias'), 'index.php?option=com_angelgirls&view=galerias', $submenu == 'galerias');
		JHtmlSidebar::addEntry(JText::_('Fotos'), 'index.php?option=com_angelgirls&view=fotos', $submenu == 'fotos');
		JHtmlSidebar::addEntry(JText::_('Agendas'), 'index.php?option=com_angelgirls&view=agendas', $submenu == 'agendas');
		JHtmlSidebar::addEntry(JText::_('Modelos'), 'index.php?option=com_angelgirls&view=modelos', $submenu == 'modelos');
		JHtmlSidebar::addEntry(JText::_('Fotografos'), 'index.php?option=com_angelgirls&view=fotografos', $submenu == 'fotografos');
		JHtmlSidebar::addEntry(JText::_('Equipes'), 'index.php?option=com_angelgirls&view=equipes', $submenu == 'equipes');
		JHtmlSidebar::addEntry(JText::_('Locacoes'), 'index.php?option=com_angelgirls&view=locacoes', $submenu == 'locacoes');
		JHtmlSidebar::addEntry(JText::_('Produtos'), 'index.php?option=com_angelgirls&view=produtos', $submenu == 'produtos');
		JHtmlSidebar::addEntry(JText::_('Enderecos'), 'index.php?option=com_angelgirls&view=enderecos', $submenu == 'enderecos');
		JHtmlSidebar::addEntry(JText::_('Telefones'), 'index.php?option=com_angelgirls&view=telefones', $submenu == 'telefones');
		JHtmlSidebar::addEntry(JText::_('Emails'), 'index.php?option=com_angelgirls&view=emails', $submenu == 'emails');
		JHtmlSidebar::addEntry(JText::_('DadosComplementares'), 'index.php?option=com_angelgirls&view=dadoscomplementares', $submenu == 'dadoscomplementares');
		JHtmlSidebar::addEntry(JText::_('Temas'), 'index.php?option=com_angelgirls&view=temas', $submenu == 'temas');
		JHtmlSidebar::addEntry(JText::_('Concursos'), 'index.php?option=com_angelgirls&view=concursos', $submenu == 'concursos');
		JHtmlSidebar::addEntry(JText::_('Categories'), 'index.php?option=com_categories&view=categories&extension=com_angelgirls', $submenu == 'categories');

		// set some global property
		$document = JFactory::getDocument();
		if ($submenu == 'categories'){
			$document->setTitle(JText::_('Categories - Angelgirls'));
		}
	}

	/**
	 *	Get the actions
	 */
	public static function getActions($Id = 0)
	{
		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($Id)){
			$assetName = 'com_angelgirls';
		} else {
			$assetName = 'com_angelgirls.message.'.(int) $Id;
		}

		$actions = JAccess::getActions('com_angelgirls', 'component');

		foreach ($actions as $action){
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}

		return $result;
	}
}
?>