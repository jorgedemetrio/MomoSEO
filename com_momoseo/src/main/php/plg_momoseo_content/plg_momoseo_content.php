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
	define('PATH_IMAGEM_MOMOSEO', JPATH_SITE . DS . 'images' . DS . FOLDER_IMAGEM_MOMOSEO );
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
	 * @var boolean
	 * @since 3.1
	 */
	protected $autoloadLanguage = true;

	const ARTICLE = 'com_content.article';
	const FORMATO_DATA_PADRAO='Y-m-d\TH:i:sP';
	const TIPO_INTRO='intro';
	const TIPO_FULL='full';
	
	
	
	private function checarImagens($context, &$article){
		if((
			$context=='com_content.category' ||
			$context== plgContentPlg_momoseo_content::ARTICLE ||
			$context=='com_content.featured' ) && isset($article) ){
			$images = json_decode($article->images);
			$tam_img_intro = $this->params['img_tamanho_intro'];
			$tam_img_artigo = $this->params['img_tamanho'];
			$hasImageFulltext = ($images && isset($images) && isset($images->image_fulltext));
			$hasImageIntro = ($images && isset($images) && isset($images->image_intro));
			
			$alteradoArquivos = $this->tratarImagem($images, $tam_img_artigo, plgContentPlg_momoseo_content::TIPO_FULL, $hasImageFulltext);
			$alteradoArquivosIntro = $this->tratarImagem($images, $tam_img_intro, plgContentPlg_momoseo_content::TIPO_INTRO, $hasImageIntro);
			
			if($alteradoArquivos || $alteradoArquivosIntro){
				$this->atualizarDadosArtigo($article, $images);
			}
		}
	}
	
	private function tratarImagem(&$images, $tam_img, $tipo, $hasImage){
		$alteradoArquivos = false;
		if($tipo==plgContentPlg_momoseo_content::TIPO_INTRO){
			$imagem =$images->image_intro;
		}
		else{
			$imagem = $images->image_fulltext;
		}
		
		if($hasImage && (strpos($imagem, FOLDER_IMAGEM_MOMOSEO)===false)) {
			$imagem = $this->SalvarImagem(dirname($imagem),
					basename($imagem),
					$tam_img,
					$this->params['qualida_'.$tipo],
					$this->params['alinhamento_h_'.$tipo],
					$this->params['alinhamento_v_'.$tipo] );
			
			if($tipo==plgContentPlg_momoseo_content::TIPO_INTRO){
				$images->origem_intro= $images->image_intro;
				$images->image_intro = $imagem;
			}
			else{
				$images->origem_full= $images->image_fulltext;
				$images->image_fulltext = $imagem;
			}
			
			$alteradoArquivos=true;
		}
		return $alteradoArquivos;
	}
	
	private function atualizarDadosArtigo (&$article, $images){
			$images->dataAlteradoMomoSEO = date("d/m/Y H:i:s");
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
	
	
	/**
	 * Plugin method with the same name as the event will be called automatically.
	 */
	function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0)
	{

		
		$this->checarImagens($context, $article);
		
		if($context!=plgContentPlg_momoseo_content::ARTICLE || !isset($article)){
			return '';
		}
		
		$images = json_decode($article->images);
		$tags = $article->tags;
		
		$hasImageFulltext = ($images && isset($images) && isset($images->image_fulltext));
		$hasImageIntro = ($images && isset($images) && isset($images->image_intro));
		
		
		$document = JFactory::getDocument();
		$config = JFactory::getConfig();
		
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$baseURL = $protocol.$_SERVER['SERVER_NAME'];
		$urlLocal = $baseURL.$_SERVER['REQUEST_URI'];
		$stylelink='
<meta property="og:locale" content="'.$article->language.'" />
<meta property="og:title" content="'.$article->title.'" />
<meta property="og:url" content="'.$urlLocal.'" />
<meta property="og:description" content="'.$article->metadesc.'" />
<meta property="article:section" content="'.$article->category_title.'" />
<meta property="article:author" content="'.$article->author.'" />		
<meta property="article:published_time" content="'.JFactory::getDate($article->publish_up)->format(plgContentPlg_momoseo_content::FORMATO_DATA_PADRAO).'" />		
<meta property="article:modified_time" content="'.JFactory::getDate($article->modified)->format(plgContentPlg_momoseo_content::FORMATO_DATA_PADRAO).'" />		
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
			$tagsStr = substr($tagsStr, 0, -1);
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
		 "@type": "'.(date('YmN')==JFactory::getDate($article->publish_up)->format('YmN')?'NewsArticle':'Article').'",
		 "mainEntityOfPage": {
 			"@type": "WebPage",
 		 	 "@id": "https://google.com/article"
 		 },
		 "headline": "'.$article->title.'",
		 "image": [
		 '.$googleSearchImg.'
		 ],
		 "datePublished": "'.JFactory::getDate($article->publish_up)->format(plgContentPlg_momoseo_content::FORMATO_DATA_PADRAO).'",
		 "dateModified": "'.JFactory::getDate($article->modified)->format(plgContentPlg_momoseo_content::FORMATO_DATA_PADRAO).'",
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
	
	
	private function SalvarImagem($diretorio, $arquivo, $largura=300, $qualidade=75, $posicao_h_logo=1, $posicao_v_logo=1){
		

		$logo = $this->params['logo_intro'];
		
		$relativo = str_replace(JPATH_SITE,'',$diretorio);
		$arrRelativo = explode(DS,$relativo);
		$novoDiretorio = PATH_IMAGEM_MOMOSEO;
		$arquivoPathCompleto = JPATH_SITE . DS . $diretorio.DS.$arquivo;
		

		foreach($arrRelativo as $nomeDir){
			$novoDiretorio.= DS . $nomeDir;
			if(!is_dir($novoDiretorio)){
				mkdir($novoDiretorio);
			}
		}
		
		
		$newfile=$novoDiretorio . DS . $largura . '_' . (substr($arquivo,0,strrpos($arquivo,'.') ) . '.jpg' ) ;
		if (file_exists( $newfile )) {
			return str_replace(JPATH_SITE . DS,'',$newfile);
		}
	
		
		$img = $this->getImg($arquivoPathCompleto);
		
		list($width, $height) = getimagesize($arquivoPathCompleto);
		$fullwidth = $largura;
		$fullheight = intval($height / ($width/$largura));
		$full = imagecreatetruecolor($fullwidth, $fullheight);
		
		$bgc = imagecolorallocate($full, 255,255,255);

		//PInta o fundo de branco antes de pintar a imagem
		imagefilledrectangle($full, 0, 0, $fullwidth, $fullheight, $bgc);
		
		
		imagecopyresized($full, $img, 0, 0, 0, 0, $fullwidth, $fullheight, $width, $height );
		
		if(isset($logo)){
			$logoImg = $this->getImg($logo);
			
			list($widthlogo, $heightlogo) = getimagesize($logo);
			
			
			$novoWidthlogo=$widthlogo>($fullwidth*0.5)?($fullwidth*0.5):$widthlogo;
			$novoHeightlogo=$widthlogo>($fullwidth*0.5)?( $heightlogo/($widthlogo/($fullwidth*0.5))):$heightlogo;
			
			$posicao_x=0;
			$posicao_y=0;
			
			if($posicao_h_logo==2){
				$posicao_x= intval(($fullwidth-$novoWidthlogo)/2);
			}
			elseif($posicao_h_logo==3){
				$posicao_x= intval($fullwidth-$novoWidthlogo);
			}
			
			if($posicao_v_logo==2){
				$posicao_y= intval(($fullheight-$novoHeightlogo)/2);
			}
			elseif($posicao_v_logo==3){
				$posicao_y= intval($fullheight-$novoHeightlogo);
			}
			
			
			
			imagecopyresampled($full, $logoImg, 
					$posicao_x, $posicao_y,
					0, 0, 
					$novoWidthlogo, $novoHeightlogo, 
					$widthlogo, $heightlogo );
		}
		
		
		imagejpeg($full, $newfile, $qualidade);
		
		return str_replace(JPATH_SITE . DS,'',$newfile) ;
		
	
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
		if($context!=plgContentPlg_momoseo_content::ARTICLE){
			return '';
		}
		
		
		return '</article>';
	}
}
