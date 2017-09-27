<?php

/*------------------------------------------------------------------------
# router.php - Angel Girls Component
# ------------------------------------------------------------------------
# author    Jorge Demetrio
# copyright Copyright (C) 2015. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   www.angelgirls.com.br
-------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');



function AngelgirlsBuildRoute(&$query)
{


	$segments = array();

	if(isset($query['task'])){
		array_push($segments, 'ag-'.$query['task']);
		unset($query['task']);
	};
	if(isset($query['view']) && $query['view']=='home' && isset($query['task'])){
		array_push($segments,  'ag-homepage');	
	}
	
	if(isset($query['view'])){
		array_push($segments,  $query['view']);
		unset($query['view']);
	};

	if(isset($query['id'])){
		array_push($segments,  $query['id']);
		unset($query['id']);
	};

	return $segments;
}

function AngelgirlsParseRoute($segments)
{

//	print_r($segments);exit();
	
	
	$vars = array();
	// Count segments
	$count = count($segments);
	
	
	if(!(strpos($segments[0],'ag:')===false)){

		$vars['task'] = substr($segments[0],3);
		//JRequest::setVar('task', $vars['task'] );
		//print_r($vars);exit();
		//unset($segments[0]);
		$valor = array();
		foreach($segments as $val){
			if($countador>0){
				$valor[] = $val;
			}
			++$countador;
		}
		$segments = $valor;
	}
	

	
	$count=sizeof($segments);

	//Handle View and Identifier
	
	switch($segments[0])
	{
		

		case 'perfil':
			$id = explode(':', $segments[$count-1]);
			$vars['view'] = 'perfil';
			if(isset($id[0]) && $id[0]!='perfil'){
				$vars['id'] = (int) $id[0];
				JRequest::setVar('id',$vars['id']);
				JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			}
			break;
		case 'fotosessao':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = 'fotosessao';
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		case 'sessoes':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = 'sessoes';
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		case 'fotoalbum':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = 'fotoalbum';
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		case 'albuns':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = 'albuns';
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		case 'fotografo':
		case 'modelo':
		case 'visitante':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = $segments[0];
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		case 'cadastro':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = 'cadastro';
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		case 'perfil':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = 'cadastro';
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		case 'inbox':
			$id = explode(':', $segments[$count-1]);
			$vars['id'] = (int) $id[0];
			$vars['view'] = 'cadastro';
			JRequest::setVar('id',$vars['id']);
			JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			break;
		default:
			$id = explode(':', $segments[$count-1]);
			if($count!=1 ){
				$vars['id'] = (int) $id[0];
				$vars['descricao'] = $id[1];
				JRequest::setVar('id',(int) $id[0]);
				JRequest::setVar('descricao',str_replace('-',' ',$id[1]));
			}
	}

	return $vars;
}
