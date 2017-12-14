<?php
// no direct access
defined( '_JEXEC' ) || die;

class plgContentPlg_momoseo_content extends JPlugin
{
	/**
	 * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
	 * If you want to support 3.0 series you must override the constructor
	 *
	 * @var    boolean
	 * @since  3.1
	 */
	protected $autoloadLanguage = true;


	
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onContentBeforeDisplay($context, &$article, &$params, $limitstart)
	{
		if($context!='com_content.article'){
			return;
		}
		
		
		$document = JFactory::getDocument();
		$config = JFactory::getConfig();
		
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		
		
		//JLayoutFile
		
		$images  = json_decode($article->images);
		$tags  = $this->item->tags;
		
		

		
		//Article,NewsArticle

		
		$stylelink='
<meta property="og:locale" content="'.$article->language.'" />
<meta property="og:title" content="'.$article->title.'" />
<meta property="og:url" content="'.$protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'" />
<meta property="og:description" content="'.$article->metadesc.'" />
<meta property="article:section" content="'.$article->category_title.'" />
<meta property="article:author" content="'.$article->author.'" />		
<meta property="article:published_time" content="'.date('Y-m-d G:i:s',$article->publish_up).'" />		
<meta property="article:modified_time" content="'.date('Y-m-d G:i:s',$article->modified).'" />		
<meta property="og:site_name" content="'.$config->get( 'sitename' ).'" />
<meta property="og:type" content="article"/>

		
		
<meta name="referrer" content="origin-when-cross-origin" />
<link rel="canonical" href="'.$protocol.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'"/>';
		
		
		$tagsStr="";
		foreach ($tags->itemTags as &$tag) {
			$tagsStr.=$tag->title.',';
		}
		if($tagsStr!=""){
			$tagsStr = substr($tags, 0, -1);
			$stylelink.='<meta property="article:tag" content="'.$tagsStr.'" />';
		}
		

		
		$googleSearchImg='';
		$hasImageIntro = ($images && isset($images) && $images->image_fulltext && isset($images->image_fulltext));
		$hasImageIntro = ($images && isset($images) && $images->image_intro && isset($images->image_intro));
				
		if($hasImageFulltext){
			$stylelink.='<meta property="og:image" content="/'.$images->image_fulltext.'"/>';
			$googleSearchImg.='"'.$protocol.$_SERVER['SERVER_NAME'].'/'.$images->image_fulltext.'"';
		}
		elseif($hasImageIntro){
			$stylelink.='<meta property="og:image" content="/'.$images->image_intro.'"/>';
		}
		
		if($hasImageIntro){
			$googleSearchImg.=($hasImageFulltext?",":"").'"'.$protocol.$_SERVER['SERVER_NAME'].'/'.$images->image_intro.'"';
		}
		
		
		

		//Mais em 
		//https://developers.google.com/search/docs/data-types/articles#amp
		//http://ogp.me/#type_article
		//date('YmN')==date('YmN',$article->publish_up) se for mesma semana
		
		
		$googleSearch='<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "'.(date('YmN')==date('YmN',$article->publish_up)?'NewsArticle':'Article').'",
		  "headline": "'.$article->title.'",
		  "image": [
		    '.$googleSearchImg.'
		   ],
		  "datePublished": "'.JFactory::getDate($article->publish_up)->format('Y-m-d\TH:i:sP').'",
		  "dateModified": "'.JFactory::getDate($article->modified)->format('Y-m-d\TH:i:sP').'",
		  "author": {
		    "@type": "Person",
		    "name": "'.$article->author.'"
		  },
		   "publisher": {
		    "@type": "Organization",
		    "name": "'.$config->get( 'sitename' ).'",
		    "logo": {
		      "@type": "ImageObject",
		      "url": "'.$protocol.$_SERVER['SERVER_NAME'].'/images/logo.png"
		    }
		  },
		  "description": "'.$article->metadesc.'"
		}
		</script>';
		
		
		$document->addCustomTag($stylelink);
		$document->addCustomTag($googleSearch);
		return '<article>';
	}
	
	function onContentAfterDisplay($context, &$article, &$params, $limitstart)
	{
		if($context!='com_content.article'){
			return;
		}
		
		
		return '</article>';
	}
}