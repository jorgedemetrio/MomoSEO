<?php
// no direct access
defined( '_JEXEC' ) || die;

jimport( 'joomla.filesystem.folder' );

if(!defined('DS')){
	define('DS',DIRECTORY_SEPARATOR);
}


if(!defined('FOLDER_IMAGEM_MOMOSEO')){
	define('FOLDER_IMAGEM_MOMOSEO', 'momoseo' );
}

if(!defined('PATH_IMAGEM_MOMOSEO')){
	define('PATH_IMAGEM_MOMOSEO', JPATH_SITE . DS . 'images' . DS . FOLDER_IMAGEM_MOMOSEO  );
	if(!JFolder::exists(PATH_IMAGEM_MOMOSEO)){
		JFolder::create(PATH_IMAGEM_MOMOSEO);
	}
}





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
	function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0)
	{
		if((
				$context=='com_content.category' ||
				$context=='com_content.article' ||
				$context=='com_content.featured' ) && isset($article) ){

			$images  = json_decode($article->images);
			$tam_img_intro = $this->params['img_tamanho_intro'];
			$tam_img_artigo = $this->params['img_tamanho'];
			

			
			$alteradoArquivos=false;
			
			$hasImageFulltext = ($images && isset($images) && isset($images->image_fulltext));
			$hasImageIntro = ($images && isset($images) && isset($images->image_intro));
			
			
			if($hasImageFulltext &&  strpos($images->image_fulltext, FOLDER_IMAGEM_MOMOSEO)===false) {
				$images->image_fulltext = $this->SalvarImagem(dirname($images->image_fulltext), basename($images->image_fulltext), $tam_img_artigo );
				$alteradoArquivos=true;
			}
			
			if($hasImageIntro &&  strpos($images->image_intro, FOLDER_IMAGEM_MOMOSEO)===false) {
				$images->image_intro = $this->SalvarImagem(dirname($images->image_intro), basename($images->image_intro), $tam_img_intro );
				$alteradoArquivos=true;
			}
			
			if($alteradoArquivos){
				$article->images = json_encode($images);
				$db = JFactory::getDbo();
				$query = $db->getQuery ( true );
				$query
					->update($db->quoteName ( '#__content' ))
					->set (array ($db->quoteName ( 'images' ) . ' = ' . $db->quote ( $article->images )))
					->where ($db->quoteName ( 'id' ) . ' = ' . $article->id);
				$db->setQuery ( $query );
				
				if(!$db->execute()){
					JError::raiseWarning( 100, 'Falha interna contate a administrador.' );
				}
			}
		
		
		}
		
		
		
		if($context!='com_content.article' || !isset($article)){
			return '';
		}
		
		
		$document = JFactory::getDocument();
		$config = JFactory::getConfig();
		
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		
		
		//JLayoutFile
		

		$tags  = $article->tags;
		
		

		
		
		//Article,NewsArticle

		
		$baseURL = $protocol.$_SERVER['SERVER_NAME'];
		$urlLocal = $baseURL.$_SERVER['REQUEST_URI'];
		$stylelink='
<meta property="og:locale" content="'.$article->language.'" />
<meta property="og:title" content="'.$article->title.'" />
<meta property="og:url" content="'.$urlLocal.'" />
<meta property="og:description" content="'.$article->metadesc.'" />
<meta property="article:section" content="'.$article->category_title.'" />
<meta property="article:author" content="'.$article->author.'" />		
<meta property="article:published_time" content="'.date('Y-m-d G:i:s',$article->publish_up).'" />		
<meta property="article:modified_time" content="'.date('Y-m-d G:i:s',$article->modified).'" />		
<meta property="og:site_name" content="'.$config->get( 'sitename' ).'" />
<meta property="og:type" content="article"/>
<meta name="referrer" content="origin-when-cross-origin" />
<link rel="canonical" href="'.$urlLocal.'"/>
<meta itemscope itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" itemid="https://google.com/article"/>';
		
		
		$tagsStr="";
		foreach ($tags->itemTags as &$tag) {
			$tagsStr.=$tag->title.',';
		}
		if($tagsStr!=""){
			$tagsStr = substr($tags, 0, -1);
			$stylelink.='<meta property="article:tag" content="'.$tagsStr.'" />';
		}
		

		
		$googleSearchImg='';

				
		if($hasImageFulltext){
			$stylelink.='<meta property="og:image" content="/'.$images->image_fulltext.'"/>';
			$googleSearchImg.='"'.$baseURL.'/'.$images->image_fulltext.'"';
		}
		elseif($hasImageIntro){
			$stylelink.='<meta property="og:image" content="/'.$baseURL.'/'.$images->image_intro.'"/>';
		}
		
		if($hasImageIntro){
			$googleSearchImg.=($hasImageFulltext?",":"").'"'.$baseURL.'/'.$images->image_intro.'"';
		}
		
		
		

		//Mais em 
		//https://developers.google.com/search/docs/data-types/articles#amp
		//http://ogp.me/#type_article
		//date('YmN')==date('YmN',$article->publish_up) se for mesma semana
		
		
		$googleSearch='
<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "'.(date('YmN')==date('YmN',$article->publish_up)?'NewsArticle':'Article').'",
		  "mainEntityOfPage": {
    			"@type": "WebPage",
  		 	 "@id": "https://google.com/article"
 		 },
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
		      "url": "'.$baseURL.'/images/logo.png"
		    }
		  },
		  "description": "'.$article->metadesc.'"
		}
</script>';
		
		
		$document->addCustomTag($stylelink);
		$document->addCustomTag($googleSearch);
		return '<article>';
	}
	
	
	private function SalvarImagem($diretorio, $arquivo, $largura=300){
		

		$logo  = $this->params['logo_intro'];
		$fullpathArquivo=  $diretorio.DS.$arquivo;
		
		$relativo = str_replace(JPATH_SITE,'',$diretorio);
		$arrRelativo  = explode(DS,$relativo);
		$novoDiretorio = PATH_IMAGEM_MOMOSEO;
		

		foreach($arrRelativo as $nomeDir){
			$novoDiretorio.= DS . $nomeDir;
			if(!is_dir($novoDiretorio)){
				mkdir($novoDiretorio);
			}
		}
		
		$newfile=$novoDiretorio . DS . $largura . '_' . $arquivo;
		if (file_exists( $newfile )) {
			
			return str_replace(JPATH_SITE . DS,'',$newfile);
			//unlink( $newfile );
		}
	
		copy($fullpathArquivo,$newfile);
		if($dados==null){
			$dados = getimagesize($newfile);
		}
		
		$dados = getimagesize($newfile,$dados);
		$img = $this->getImg($newfile,$dados);

		list($width, $height) = getimagesize($newfile);
		$fullwidth =  $largura;
		$fullheight = ($height / ($width/$largura));
		$full = imagecreatetruecolor($fullwidth, $fullheight);
	
		
		imagecopyresized($full, $img, 0, 0, 0, 0, $fullwidth, $fullheight, $width, $height );
		
		if(isset($logo)){
			$logoImg = $this->getImg($logo);
			
			list($widthlogo, $heightlogo) = getimagesize($logo);
			
			
			$novoWidthlogo=$widthlogo>($fullwidth*0.5)?($fullwidth*0.5):$widthlogo;
			$novoHeightlogo=$widthlogo>($fullwidth*0.5)?( $heightlogo/($widthlogo/($fullwidth*0.5))):$heightlogo;
			
			imagecopyresampled($full, $logoImg, 
					0, 0,
					0, 0, 
					$novoWidthlogo, $novoHeightlogo, 
					$widthlogo, $heightlogo );
		}

		return $this->SalvarImagemNoDir($full, $newfile,$dados[2],75)? str_replace(JPATH_SITE . DS,'',$newfile) : null ;
	
	}
				
	private function SalvarImagemNoDir($img, $arquivo, $tipo, $qualidade=100){
		$retorno = true;
		if ($tipo & imagetypes()) {
			switch ($tipo) {
				case IMG_GIF:
					if(!imagegif($img, $arquivo)){
						$retorno = false;
					}
					break;
				case IMG_JPEG:
					if(!imagejpeg($img, $arquivo, $qualidade)){
						$retorno = false;
					}
					break;
				case IMG_PNG:
					if(!imagepng($img, $arquivo, $qualidade)){
						$retorno = false;
					}
					break;
				case IMG_WBMP:
					if(!imagewbmp($img, $arquivo)){
						$retorno = false;
					}
					break;
				default:
					if(!imagejpeg($img, $arquivo, $qualidade)){
						$retorno = false;
					}
					break;
			}
		}
		return $retorno;

	}
	
	private function getImg($newfile, $dados =null){
		if($dados==null){
			$dados = getimagesize($newfile);
		}
		$tipo = $dados[2];
		if ($tipo & imagetypes()) {
			switch ($tipo) {
				case IMG_GIF:
					$img = imagecreatefromgif($newfile);
					break;
				case IMG_JPEG:
					$img = imagecreatefromjpeg($newfile);
					break;
				case IMG_PNG:
					$img = imagecreatefrompng($newfile);
					break;
				case IMG_WBMP:
					$img = imagecreatefromwbmp($newfile);
					break;
				default:
					$conteudo = file_get_contents($newfile);
					$img = imagecreatefromstring($conteudo);
					break;
			}
		}
		return $img;
	}
	
	
	
	function onContentAfterDisplay($context, &$article, &$params, $limitstart=0)
	{
		if($context!='com_content.article'){
			return '';
		}
		
		
		return '</article>';
	}
}