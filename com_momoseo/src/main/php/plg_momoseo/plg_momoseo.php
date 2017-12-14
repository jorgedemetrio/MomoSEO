<?php
// no direct access
defined( '_JEXEC' ) || die;



class plgSystemPlg_momoseo extends JPlugin
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
	function onAfterRender()
	{

		$document = JFactory::getDocument();

		$document->addScript('https://apis.google.com/js/platform.js', 'text/javascript',  true, true);
		//<script src="https://apis.google.com/js/platform.js" async defer></script>
		//<g:plusone></g:plusone>
		//<div class="g-plus" data-action="share"></div>

		//<div id="fb-root"></div>
		$document->addScriptDeclaration('(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
			}(document,"script", "facebook-jssdk"));');







		/* <div class="fb-like" data-href="http://www.your-domain.com/your-page.html" data-layout="standard" data-action="like" data-show-faces="true"> </div>*/
		//<div class="fb-page" data-href="https://www.facebook.com/mamaezona.net/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/humorpretonobranco/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/humorpretonobranco/">Humor Preto no Branco</a></blockquote></div>


		/*
		 * Plugin code goes here.
		 * You can access database and application objects and parameters via $this->db,
		 * $this->app and $this->params respectively
		 */
		return true;
	}
}





class plgSystemMomoseo extends JPlugin
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
	function onAfterRender()
	{
		
		$document = JFactory::getDocument();

		$document->addScript('https://apis.google.com/js/platform.js', 'text/javascript',  true, true);
		//<script src="https://apis.google.com/js/platform.js" async defer></script>
		//<g:plusone></g:plusone>
		//<div class="g-plus" data-action="share"></div>
		
		//<div id="fb-root"></div>
		$document->addScriptDeclaration('(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
			}(document,"script", "facebook-jssdk"));');
		
		
		
		
		$document = JFactory::getDocument();
		
		//JLayoutFile
		
		$images  = json_decode($this->item->images);
		
		//echo $images->image_intro;
		
		//print_r($images);
		//echo "AQUI";
		if($images && isset($images) && $images->image_fulltext && isset($images->image_fulltext)){
			$stylelink='<meta name="ResourceLoaderDynamicStyles" content=""/>
<meta name="referrer" content="origin-when-cross-origin"/>
<meta property="og:image" content="/'.$images->image_fulltext.'"/>
<link rel="apple-touch-icon" href="/favicon-96x96.png"/>
<link rel="shortcut icon" href="/favicon.ico"/>
<link rel="canonical" href="//'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'"/>';
		
			$document->addCustomTag($stylelink);
			//$document->setMetadata('Keywords', 'buscar '.$busca.',localizar '.$busca.','.str_replace(' ', ',',$busca));
		}
		elseif($images && isset($images) && $images->image_intro && isset($images->image_intro)){
			$stylelink='<meta name="ResourceLoaderDynamicStyles" content=""/>
<meta name="referrer" content="origin-when-cross-origin"/>
<meta property="og:image" content="/'.$images->image_intro.'"/>
<link rel="apple-touch-icon" href="/favicon-96x96.png"/>
<link rel="shortcut icon" href="/favicon.ico"/>
<link rel="canonical" href="//'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].'"/>';
		
			$document->addCustomTag($stylelink);
			//$document->setMetadata('Keywords', 'buscar '.$busca.',localizar '.$busca.','.str_replace(' ', ',',$busca));
		}
		
		
		
	
		/* <div class="fb-like" data-href="http://www.your-domain.com/your-page.html" data-layout="standard" data-action="like" data-show-faces="true"> </div>*/
		//<div class="fb-page" data-href="https://www.facebook.com/mamaezona.net/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/humorpretonobranco/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/humorpretonobranco/">Humor Preto no Branco</a></blockquote></div>
		
		
		/*
		 * Plugin code goes here.
		 * You can access database and application objects and parameters via $this->db,
		 * $this->app and $this->params respectively
		 */
		return true;
	}
}