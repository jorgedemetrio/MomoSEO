<?php

/*------------------------------------------------------------------------
# controller.php - MomoSEO Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 3 || later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.alldreams.com.br
-------------------------------------------------------------------------*/
// No direct access to this file
defined('_JEXEC') || die('Restricted access');
// import Joomla controller library
jimport('joomla.application.component.controller');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.application.component.helper');
include_once JPATH_BASE .DS.'components/com_content/models/article.php';
//require_once JPATH_BASE .DS.'components/com_content/helpers/route.php';
JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');
//require_once JPATH_BASE .DS.'components/com_content/helpers/query.php';
jimport( 'joomla.application.module.helper' );
jimport( 'joomla.mail.mail' );
jimport('joomla.log.log');



/**
 * MomoSEO Component Controller
 */
class MomoseoController extends JControllerLegacy{

	function display($cachable = false, $urlparams = false) {
	    
	    $input = JFactory::getApplication()->getInput();
	    
		// set default view if not set
		$input->set( 'view', $input->get ( 'view', 'Momoseo' ) );

		// call parent behavior
		parent::display ( $cachable );

	}

	const HEADER_XML_SITEMAOP =  "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
	const CONTENT_TYPE_XML ='Content-type: application/xml';
	const SITEMAP_TAGS1 = "\t<url>\n";
	const SITEMAP_TAGS2 = "\t\t<changefreq>monthly</changefreq>\n\t\t<priority>0.3</priority>\n";
	const SITEMAP_TAGS3 = "\t</url>\n";
	const SITEMAP_TAGS4 = '</urlset>';
	const HTTPS_HOST = 'HTTP_HOST';

	/**
	 * Sitemap content
	 */
	public function sitemapContent(){
		$db = JFactory::getDbo ();
		$host = $_SERVER[MomoseoController::HTTPS_HOST] ;
        $ssl = JUri::getInstance()->isSsl() ? 1 : -1;
		$publish_up = $db->quoteName ( 'publish_up' );

		$query = $db->getQuery ( true );
		$query->select("`id` , id + ':' + alias as slug, catid, language, modified  ")
		->from ('#__content')
		->where ("( $publish_up <= NOW()  || $publish_up  IS NULL || $publish_up = '0000-00-00 00:00:00' )" )
				->where ( $db->quoteName ( 'state' ) . ' = 1  ' )
				->order('created DESC')
				->setLimit(50000);
		$db->setQuery ( $query );
		$results = $db->loadObjectList();
		$xml = MomoseoController::HEADER_XML_SITEMAOP;
		foreach ( $results as $result){
		    
			//$url = $host . JRoute::_(ContentHelperRoute::getArticleRoute($result->slug, $result->catid, $result->language));
			
			$url = str_replace('&', '&amp;',JRoute::link('site', ContentHelperRoute::getArticleRoute($result->id, $result->catid, $result->language), false, $ssl));
			
			
			$xml = $xml . MomoseoController::SITEMAP_TAGS1;
			$xml = $xml . "\t\t<lastmod>" . JFactory::getDate($result->modified)->format('Y-m-d\TH:i:sP')  . "</lastmod>\n";
			$xml = $xml . MomoseoController::SITEMAP_TAGS2;
			$xml = $xml . "\t\t<loc>$url</loc>\n";
			$xml = $xml . MomoseoController::SITEMAP_TAGS3;
		}
		$xml = $xml . MomoseoController::SITEMAP_TAGS4;
		header(MomoseoController::CONTENT_TYPE_XML);
		echo $xml;
		exit();
	}



    public function sitemapTags(){
            $db = JFactory::getDbo ();
            $host = $_SERVER[MomoseoController::HTTPS_HOST] ;

            $query = $db->getQuery ( true );
            $query->select("`id` , path ")
            ->from ('#__tags')
                                ->order('published DESC')
                                ->setLimit(50000);
            $db->setQuery ( $query );
            $results = $db->loadObjectList();
            $xml = MomoseoController::HEADER_XML_SITEMAOP;
            foreach ( $results as $result){
                        $url = str_replace('&', '&amp;',$host.'/component/tags/tag/' . $result->id  . '-' . $result->path  . '.html');
                        $xml = $xml . MomoseoController::SITEMAP_TAGS1;
                        $xml = $xml . MomoseoController::SITEMAP_TAGS2;
                        $xml = $xml . "\t\t<loc>https://" .  $url . "</loc>\n";
                        $xml = $xml . MomoseoController::SITEMAP_TAGS3;
            }
            $xml = $xml . MomoseoController::SITEMAP_TAGS4;
            header(MomoseoController::CONTENT_TYPE_XML);
            echo $xml;
            exit();
      }


      public function sitemapOutros(){
      	$db = JFactory::getDbo ();
      	$host = $_SERVER[MomoseoController::HTTPS_HOST] ;

      	$query = $db->getQuery ( true );
      	$query->select("`id` , url , prioridade ")
      	->from ('#__mom_dyna_page')
      	->order('data_alteracao DESC')
      	->setLimit(50000);
      	$db->setQuery ( $query );
      	$results = $db->loadObjectList();
      	$xml = MomoseoController::HEADER_XML_SITEMAOP;
      	foreach ( $results as $result){
      		$url = str_replace('&', '&amp;',(strpos( $result->url,$host)===false? $host : "" )  . $result->url)  ;
      		$xml = $xml . MomoseoController::SITEMAP_TAGS1;
      		$xml = $xml . "\t\t<changefreq>monthly</changefreq>\n";
      		$xml = $xml . "\t\t<priority>" . $result->prioridade  . "</priority>\n";
      		$xml = $xml . "\t\t<loc>https://" .  $url . "</loc>\n";
      		$xml = $xml . MomoseoController::SITEMAP_TAGS3;
      	}
      	$xml = $xml . MomoseoController::SITEMAP_TAGS4;
      	header(MomoseoController::CONTENT_TYPE_XML);
      	echo $xml;
      	exit();
      }

	/**
	 * Sitemap dos menus
	 */
	function sitemapMenus(){
		$xml = MomoseoController::HEADER_XML_SITEMAOP;
		$xml = $xml . MomoseoController::SITEMAP_TAGS4;
		header(MomoseoController::CONTENT_TYPE_XML);
		echo $xml;
		exit();
	}



	/**
	 * Carrega a tela de bsuca
	 */
	public function buscar(){
	    	    
	    $input = JFactory::getApplication()->getInput();
	    
		// set default view if not set
		
		$q = $input->get( 'q');
		JSearchHelper::logSearch($q, 'com_search');

		$input->set( 'view', 'busca' );
		$input->set( 'layout', 'default' );
		parent::display (true, false);
	}


}