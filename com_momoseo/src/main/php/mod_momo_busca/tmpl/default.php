<?php 
// No direct access
defined('_JEXEC') || die; 


$url = JRoute::_('index.php?option=com_momoseo&task=buscar&Itemid='.JRequest::getVar ( 'Itemid' ), false);

?>
<div class="<?php echo $lblClass;?>">

<form action="<?php echo $url;?>" id="cse-search-box">
  <div>
    <input type="hidden" name="cx" value="<?php echo $cx;?>" />
    <input type="hidden" name="cof" value="<?php echo $cof;?>" />
    <input type="hidden" name="ie" value="UTF-8" />
    <input type="text" name="q" size="20" value="<?php echo $_GET["q"];?>"/>
    <input class="button" name="sa" <?php 
		if($lblImagem!=null && $lblImagem!=''){
			echo ' src="' . $lblImagem . '" type="image" ';
		}
		else{ 
			echo ' type="submit" ';
		}?>value="<?php echo $lblBotaoBusca; ?>" />
  </div>
</form>
</div>