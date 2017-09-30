<?php
/*
 * ------------------------------------------------------------------------
 * # controller.php - Angel Girls Component
 * # ------------------------------------------------------------------------
 * # author Jorge Demetrio
 * # copyright Copyright (C) 2015. All Rights Reserved
 * # license GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * # website www.angelgirls.com.br
 * -------------------------------------------------------------------------
 */

// No direct access to this file
defined ( '_JEXEC' ) || die ( 'Restricted access' );

// import Joomla controller library
jimport ( 'joomla.application.component.controller' );
jimport ( 'joomla.filesystem.file' );
jimport ( 'joomla.filesystem.folder' );
jimport ('joomla.application.component.helper');

/**
 * General Controller of Angelgirls component
 */
class AngelgirlsController extends JControllerLegacy {
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		// set default view if not set
		JRequest::setVar ( 'view', JRequest::getCmd ( 'view', 'Angelgirls' ) );
		
		// call parent behavior
		parent::display ( $cachable );
		
		// set view
		$view = strtolower ( JRequest::getVar ( 'view' ) );
		
		// Set the submenu
		AngelgirlsHelper::addSubmenu ( $view );
	}
	
	/**
	 * ****************** MODELO *****************************************
	 */
	public function deleteModelo() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ) || ! JSession::checkToken ( 'post' ))
			die ( 'Restricted access' );
		
		$id = JRequest::getInt ( 'id', 0 );
		
		if ($id != 0) {
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			
			$query->update ( $db->quoteName ( '#__angelgirls_modelo' ) )->set ( array (
					$db->quoteName ( 'status_dado' ) . ' = \'REMOVED\'',
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id 
			) )->where ( array (
					$db->quoteName ( 'id' ) . ' = ' . $id 
			) );
			
			$db->setQuery ( $query );
			
			$db->execute ();
		}
		
		$this->listModelo ();
	}
	public function editModelo() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		$id = JRequest::getInt ( 'id', 0 );
		
		// Carregar cidade e estado
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName ( array (
				'a.id','a.id_usuario','a.nome_artistico','a.descricao','a.meta_descricao','a.foto_perfil','a.foto_inteira','a.altura','a.peso','a.busto',
				'a.calsa','a.calsado','a.olhos','a.pele','a.etinia','a.cabelo','a.tamanho_cabelo','a.cor_cabelo','a.outra_cor_cabelo','a.profissao',
				'a.nascionaldiade','a.id_cidade_nasceu','a.data_nascimento','a.site','a.sexo','a.cpf','a.banco','a.agencia','a.conta','a.custo_medio_diaria',
				'a.status_modelo','a.qualificao_equipe','a.audiencia_gostou','a.audiencia_ngostou','a.audiencia_view','a.id_cidade','a.status_dado',
				'a.id_usuario_criador','a.id_usuario_alterador','a.data_criado','a.data_alterado','b.name','c.name','d.name','d.username','d.email' 
		), array (
				'id','id_usuario','nome_artistico','descricao','meta_descricao','foto_perfil','foto_inteira','altura','peso','busto','calsa',
				'calsado','olhos','pele','etinia','cabelo','tamanho_cabelo','cor_cabelo','outra_cor_cabelo','profissao','nascionaldiade','id_cidade_nasceu',
				'data_nascimento','site','sexo','cpf','banco','agencia','conta','custo_medio_diaria','status_modelo','qualificao_equipe',
				'audiencia_gostou','audiencia_ngostou','audiencia_view','id_cidade','status_dado','id_usuario_criador','id_usuario_alterador',
				'data_criado','data_alterado','criador','editor','name','username','email')))
			->from ( $db->quoteName ( '#__angelgirls_modelo', 'a' ) )
			->join ( 'INNER', $db->quoteName ( '#__users', 'b' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_criador' ) . ' = ' . $db->quoteName ( 'b.id' ) . ')' )
			->join ( 'INNER', $db->quoteName ( '#__users', 'c' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_alterador' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
			->join ( 'INNER', $db->quoteName ( '#__users', 'd' ) . ' ON (' . $db->quoteName ( 'a.id_usuario' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
			
			->where ( $db->quoteName ( 'a.id' ) . ' = ' . $id )
			->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' );
		$db->setQuery ( $query );
		$objeto = $db->loadObject ();
		JRequest::setVar ( 'modelo', $objeto );
		
		JRequest::setVar ('id_usuario', $objeto->id_usuario);
		
		$this->listaEmails();
		
		$this->listaEnderecos();
		
		$this->listaTelefones();
		
		$this->listaRedeSocial();
		
		
		$this->addModelo();
	}
	
	public function listaRedeSocialHTML(){
		$this->listaRedeSocial();
		require_once 'views/modelos/tmpl/tableRedeSociais.php';
		exit();
	}
	
	public function listaEnderecosHTML(){
		$this->listaEnderecos();
		require_once 'views/modelos/tmpl/tableEnderecos.php';
		exit();
	}
	
	public function listaTelefonesHTML(){
		$this->listaTelefones();
		require_once 'views/modelos/tmpl/tableTelefones.php';
		exit();
	}
	
	public function listaEmailsHTML(){
		$this->listaEmails();
		require_once 'views/modelos/tmpl/tableEmails.php';
		exit();
	}
	
	public function listaRedeSocial(){
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( array ('id','principal','rede_social','url_usuario','id_usuario','ordem')))
		->from ('#__angelgirls_redesocial')
		->where ( $db->quoteName ( 'id_usuario' ) . ' = ' . JRequest::getVar('id_usuario') )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList();
		JRequest::setVar ( 'redesSociais', $result );
	}
	
	public function listaEnderecos(){
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( array ('id','principal','endereco','numero','bairro','complemento','cep','id_cidade','id_usuario','ordem')))
		->from ('#__angelgirls_endereco')
		->where ( $db->quoteName ( 'id_usuario' ) . ' = ' . $objeto->id_usuario )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList();
		JRequest::setVar ( 'telefones', $result );
	}
	
	public function listaTelefones(){
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( array ('id','principal','operadora','ddi','ddd','telefone','id_usuario','ordem')))
		->from ('#__angelgirls_telefone')
		->where ( $db->quoteName ( 'id_usuario' ) . ' = ' . $objeto->id_usuario )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList();
		JRequest::setVar ( 'telefones', $result );
	}
	
	public function listaEmails(){
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( array ('id','principal','email','id_usuario','ordem')))
			->from ('#__angelgirls_email')
			->where ( $db->quoteName ( 'id_usuario' ) . ' = ' . $objeto->id_usuario )
			->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList();
		JRequest::setVar ( 'emails', $result );
	}
	
	/**
	 * Method to register a user.
	 *
	 * @return boolean
	 *
	 * @since 1.6
	 */
	public function registerUser() {
		JSession::checkToken ( 'post' ) or jexit ( JText::_ ( 'JINVALID_TOKEN' ) );
		
		// Get the application
		$app = JFactory::getApplication ();
		
		// Get the form data.
		$data = $this->input->post->get ( 'user', array (), 'array' );
		
		// Get the model and validate the data.
		$model = $this->getModel ( 'Registration', 'UsersModel' );
		$return = $model->validate ( $data );
		
		// Check for errors.
		if ($return === false) {
			// Get the validation messages.
			$errors = $model->getErrors ();
			
			// Push up to three validation messages out to the user.
			for($i = 0, $n = count ( $errors ); $i < $n && $i < 3; $i ++) {
				if ($errors [$i] instanceof Exception) {
					$app->enqueueMessage ( $errors [$i]->getMessage (), 'notice' );
				} else {
					$app->enqueueMessage ( $errors [$i], 'notice' );
				}
			}
			
			// Save the data in the session.
			$app->setUserState ( 'users.registration.form.data', $data );
			
			// Redirect back to the registration form.
//			$this->setRedirect ( 'index.php?option=com_amgelgirls&task=addUser' );
			
			return false;
		}
		
		// Finish the registration.
		$return = $model->register( $data );
		
		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState ( 'users.registration.form.data', $data );
			
			// Redirect back to the registration form.
			$message = JText::sprintf ( 'COM_USERS_REGISTRATION_SAVE_FAILED', $model->getError () );
			//$this->setRedirect ( 'index.php?option=com_users&view=registration', $message, 'error' );
			
			return false;
		}
		
		// Flush the data from the session.
		$app->setUserState ( 'users.registration.form.data', null );
		
		return true;
	}
	
	/**
	 * S� carrrega a tela de adi��o.
	 */
	public function addModelo() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName ( array (
				'a.id',
				'a.nome',
				'a.uf' 
		) ) )->from ( $db->quoteName ( '#__cidade', 'a' ) )->order ( 'a.nome, a.uf' );
		
		$db->setQuery ( $query );
		
		$result = $db->loadObjectList ();
		JRequest::setVar ( 'cidades', $result );
		
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName ( array (
				'a.uf' 
		) ) )->from ( $db->quoteName ( '#__cidade', 'a' ) )->group ( $db->quoteName ( array (
				'a.uf' 
		) ) )->order ( 'a.uf' );
		$db->setQuery ( $query );
		
		$result = $db->loadObjectList ();
		JRequest::setVar ( 'ufs', $result );
		
		JRequest::setVar ( 'view', 'modelos' );
		JRequest::setVar ( 'layout', 'cadastro' );
		parent::display ();
	}
	
	public function applayModelo() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$this->saveModeloDB ();
		$this->editModelo ();
	}
	
	/**
	 * Apenas salva no banco o dado.
	 */
	public function saveModeloDB() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ) || ! JSession::checkToken ( 'post' ))
			die ( 'Restricted access' );
		$uploadPath = JPATH_SITE . DS . 'images' . DS . 'modelos' . DS;
		$erro = false;
		
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		$name = JRequest::getString ( 'name', null, 'POST' );
		$descricao = JRequest::getString ( 'descricao', null, 'POST' );
		$metaDescricao = JRequest::getString ( 'meta_descricao', null, 'POST' );
		$nomeArtistico = JRequest::getString ( 'nome_artistico', null, 'POST' );
		$altura = JRequest::getFloat ( 'altura', null, 'POST' );
		$peso = JRequest::getFloat ( 'peso', null, 'POST' );
		$busto = JRequest::getFloat ( 'busto', null, 'POST' );
		$calsa = JRequest::getInt ( 'calsa', null, 'POST' );
		$calsado = JRequest::getInt ( 'calsado', null, 'POST' );
		$olhos = JRequest::getString ( 'olhos', null, 'POST' );
		$pele = JRequest::getString ( 'pele', null, 'POST' );
		$etinia = JRequest::getString ( 'etinia', null, 'POST' );
		$cabelo = JRequest::getString ( 'cabelo', null, 'POST' );
		$tamanhoCabelo = JRequest::getString ( 'tamanho_cabelo', null, 'POST' );
		$corCabelo = JRequest::getString ( 'cor_cabelo', null, 'POST' );
		$outraCorCabelo = JRequest::getString ( 'outra_cor_cabelo', null, 'POST' );
		$profissao = JRequest::getString ( 'profissao', null, 'POST' );
		$nascionalidade = JRequest::getString ( 'nascionalidade', '', 'POST' );
		$idCidadeNasceu = JRequest::getInt ( 'id_cidade_nasceu', null, 'POST' );
		$dataNascimento = JRequest::getString ( 'data_nascimento', null, 'POST' );
		$site = JRequest::getString ( 'site', null, 'POST' );
		$sexo = JRequest::getString ( 'sexo', null, 'POST' );
		$cpf = JRequest::getString ( 'cpf', null, 'POST' );
		$banco = JRequest::getString ( 'banco', null, 'POST' );
		$agencia = JRequest::getString ( 'agencia', null, 'POST' );
		$conta = JRequest::getString ( 'conta', null, 'POST' );
		$custoMedioDiaria = JRequest::getString ( 'custo_medio_diaria', null, 'POST' );
		$statusModelo = JRequest::getString ( 'status_modelo', null, 'POST' );
		$qualificaoEquipe = JRequest::getString ( 'qualificao_equipe', null, 'POST' );
		$idCidade = JRequest::getInt ( 'id_cidade', null, 'POST' );
		
		$foto_perfil = $_FILES ['foto_perfil'];
		$foto_inteira = $_FILES ['foto_inteira'];
		$foto_inteira_horizontal = $_FILES ['foto_inteira_horizontal'];
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		if ($id != null && $id != 0) { // UPDATE
			
			$query->update ( $db->quoteName ( '#__angelgirls_modelo' ) )->set ( array (
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id,
					$db->quoteName ( 'id_usuario' ) . ' = ' . $user->id,//TODO Pegar o ID DO USU�RIO SALVO
					$db->quoteName ( 'nome_artistico' ) . ' = ' . $db->quote($nomeArtistico),
					$db->quoteName ( 'descricao' ) . ' = ' . $db->quote($descricao),
					$db->quoteName ( 'meta_descricao' ) . ' = ' . $db->quote($metaDescricao),
					$db->quoteName ( 'altura' ) . ' = ' . ($altura == null ? ' null ' : $db->quote($altura)),
					$db->quoteName ( 'peso' ) . ' = ' . ($peso == null ? ' null ' : $db->quote($peso)),
					$db->quoteName ( 'busto' ) . ' = ' . ($busto == null ? ' null ' : $db->quote($busto)),
					$db->quoteName ( 'calsa' ) . ' = ' . ($calsa == null ? ' null ' : $db->quote($calsa)),
					$db->quoteName ( 'calsado' ) . ' = ' . ($calsado == null ? ' null ' : $db->quote($calsado)),
					$db->quoteName ( 'olhos' ) . ' = ' . ($olhos == null ? ' null ' : $db->quote($olhos)),
					$db->quoteName ( 'pele' ) . ' = ' . ($pele == null ? ' null ' : $db->quote($pele)),
					$db->quoteName ( 'etinia' ) . ' = ' . ($etinia == null ? ' null ' : $db->quote($etinia)),
					$db->quoteName ( 'cabelo' ) . ' = ' . ($cabelo == null ? ' null ' : $db->quote($cabelo)),
					$db->quoteName ( 'tamanho_cabelo' ) . ' = ' . ($tamanhoCabelo == null ? ' null ' : $db->quote($tamanhoCabelo)),
					$db->quoteName ( 'cor_cabelo' ) . ' = ' . ($corCabelo == null ? ' null ' : $db->quote($corCabelo)),
					$db->quoteName ( 'outra_cor_cabelo' ) . ' = ' . ($outraCorCabelo == null ? ' null ' : $db->quote($outraCorCabelo)),
					$db->quoteName ( 'profissao' ) . ' = ' . ($profissao == null ? ' null ' : $db->quote($profissao)),
					$db->quoteName ( 'nascionaldiade' ) . ' = ' . ($nascionaldiade == null ? ' null ' : $db->quote($nascionaldiade)),
					$db->quoteName ( 'id_cidade_nasceu' ) . ' = ' . ($idCidadeNasceu == null ? ' null ' : $db->quote($idCidadeNasceu)),
					$db->quoteName ( 'data_nascimento' ) . ' = ' . ($dataNascimento == null ? ' null ' : $db->quote(
							substr($dataNascimento,6,10) . '-' . substr($dataNascimento,4,5) . '-' . substr($dataNascimento,0,2))),
					$db->quoteName ( 'site' ) . ' = ' . ($site == null ? ' null ' : $db->quote($site)),
					$db->quoteName ( 'sexo' ) . ' = ' . ($sexo == null ? ' null ' : $db->quote($sexo)),
					$db->quoteName ( 'cpf' ) . ' = ' . ($cpf == null ? ' null ' : $db->quote($cpf)),
					$db->quoteName ( 'banco' ) . ' = ' . ($banco == null ? ' null ' : $db->quote($banco)),
					$db->quoteName ( 'agencia' ) . ' = ' . ($agencia == null ? ' null ' : $db->quote($agencia)),
					$db->quoteName ( 'conta' ) . ' = ' . ($conta == null ? ' null ' : $db->quote($conta)),
					$db->quoteName ( 'custo_medio_diaria' ) . ' = ' . ($custoMedioDiaria == null ? ' null ' : $db->quote($custoMedioDiaria)),
					$db->quoteName ( 'status_modelo' ) . ' = ' . ($statusModelo == null ? ' null ' : $db->quote($statusModelo)),
					$db->quoteName ( 'qualificao_equipe' ) . ' = ' . ($qualificaoEquipe == null ? ' null ' : $db->quote($qualificaoEquipe)),
					$db->quoteName ( 'id_cidade' ) . ' = ' . ($idCidade == null ? ' null ' : $db->quote($idCidade))
					))
				->where ( array (
					$db->quoteName ( 'id' ) . ' = ' . $id 
				));
			
			$db->setQuery ( $query );
			
			$db->execute ();
		} else {
			$query->insert ( $db->quoteName ( '#__angelgirls_modelo' ) )->columns ( array (
					$db->quoteName ( 'status_dado' ),
					$db->quoteName ( 'data_criado' ),
					$db->quoteName ( 'id_usuario_criador' ),
					$db->quoteName ( 'data_alterado' ),
					$db->quoteName ( 'id_usuario_alterador' ), 
					$db->quoteName ( 'nome_artistico' ),
					$db->quoteName ( 'descricao' ),
					$db->quoteName ( 'meta_descricao' ),
					$db->quoteName ( 'altura' ),
					$db->quoteName ( 'peso' ),
					$db->quoteName ( 'busto' ),
					$db->quoteName ( 'calsa' ),
					$db->quoteName ( 'calsado' ),
					$db->quoteName ( 'olhos' ),
					$db->quoteName ( 'pele' ),
					$db->quoteName ( 'etinia' ),
					$db->quoteName ( 'cabelo' ),
					$db->quoteName ( 'tamanho_cabelo' ),
					$db->quoteName ( 'cor_cabelo' ),
					$db->quoteName ( 'outra_cor_cabelo' ),
					$db->quoteName ( 'profissao' ),
					$db->quoteName ( 'nascionaldiade' ),
					$db->quoteName ( 'id_cidade_nasceu' ),
					$db->quoteName ( 'data_nascimento' ),
					$db->quoteName ( 'site' ),
					$db->quoteName ( 'sexo' ),
					$db->quoteName ( 'cpf' ),
					$db->quoteName ( 'banco' ),
					$db->quoteName ( 'agencia' ),
					$db->quoteName ( 'conta' ),
					$db->quoteName ( 'custo_medio_diaria' ),
					$db->quoteName ( 'status_modelo' ),
					$db->quoteName ( 'qualificao_equipe' ),
					$db->quoteName ( 'id_cidade' )
					))
			->values ( implode ( ',', array (
					'\'NOVO\'',
					'NOW()',
					$user->id,
					'NOW()',
					$user->id,
					$db->quote($nomeArtistico),
					$db->quote($descricao),
					$db->quote($metaDescricao), 
					($altura == null ? ' null ' : $db->quote($altura)),
					($peso == null ? ' null ' : $db->quote($peso)), 
					($busto == null ? ' null ' : $db->quote($busto)), 
					($calsa == null ? ' null ' : $db->quote($calsa)), 
					($calsado == null ? ' null ' : $db->quote($calsado)), 
					($olhos == null ? ' null ' : $db->quote($olhos)), 
					($pele == null ? ' null ' : $db->quote($pele)), 
					($etinia == null ? ' null ' : $db->quote($etinia)), 
					($cabelo == null ? ' null ' : $db->quote($cabelo)), 
					($tamanhoCabelo == null ? ' null ' : $db->quote($tamanhoCabelo)), 
					($corCabelo == null ? ' null ' : $db->quote($corCabelo)), 
					($outraCorCabelo == null ? ' null ' : $db->quote($outraCorCabelo)), 
					($profissao == null ? ' null ' : $db->quote($profissao)), 
					($nascionaldiade == null ? ' null ' : $db->quote($nascionaldiade)), 
					($idCidadeNasceu == null ? ' null ' : $db->quote($idCidadeNasceu)), 
					($dataNascimento == null ? ' null ' : $db->quote(
							substr($dataNascimento,6,10) . '-' . substr($dataNascimento,4,5) . '-' . substr($dataNascimento,0,2))), 
					($site == null ? ' null ' : $db->quote($site)), 
					($sexo == null ? ' null ' : $db->quote($sexo)), 
					($cpf == null ? ' null ' : $db->quote($cpf)), 
					($banco == null ? ' null ' : $db->quote($banco)), 
					($agencia == null ? ' null ' : $db->quote($agencia)), 
					($conta == null ? ' null ' : $db->quote($conta)), 
					($custoMedioDiaria == null ? ' null ' : $db->quote($custoMedioDiaria)), 
					($statusModelo == null ? ' null ' : $db->quote($statusModelo)), 
					($qualificaoEquipe == null ? ' null ' : $db->quote($qualificaoEquipe)), 
					($idCidade == null ? ' null ' : $db->quote($idCidade))
			)));
			
			$db->setQuery( $query );
			
			$db->execute();
			
			$id = $db->insertid();
			
			JRequest::setVar ( 'id', $id );
		}
		
		if (isset ( $foto_perfil ) && JFolder::exists ( $foto_perfil ['tmp_name'] )) {
			$fileName = $foto_perfil ['name'];
			$uploadedFileNameParts = explode ( '.', $fileName );
			$uploadedFileExtension = array_pop ( $uploadedFileNameParts );
			
			$fileTemp = $foto_perfil ['tmp_name'];
			$newfile = $uploadPath . $id . '_perfil.' . $uploadedFileExtension;
			
			if (JFolder::exists ( $newfile )) {
				JFile::delete ( $newfile );
			}
			
			if (! JFile::upload ( $fileTemp, $newfile )) {
				JError::raiseWarning ( 100, 'Falha ao salvar o arquivo.' );
				$erro = true;
			} else {
				$query->update ( $db->quoteName ( '#__angelgirls_modelo' ) )->set ( array (
						$db->quoteName ( 'foto_perfil' ) . ' = ' . $db->quote ( $id . '.' . $uploadedFileExtension ) 
				) )->where ( array (
						$db->quoteName ( 'id' ) . ' = ' . $id 
				) );
				$db->setQuery ( $query );
				$db->execute ();
			}
		}
		
		if (isset ( $foto_inteira ) && JFolder::exists ( $foto_inteira ['tmp_name'] )) {
			$fileName = $foto_inteira ['name'];
			$uploadedFileNameParts = explode ( '.', $fileName );
			$uploadedFileExtension = array_pop ( $uploadedFileNameParts );
				
			$fileTemp = $foto_inteira ['tmp_name'];
			$newfile = $uploadPath . $id . '_inteira.' . $uploadedFileExtension;
				
			if (JFolder::exists ( $newfile )) {
				JFile::delete ( $newfile );
			}
				
			if (! JFile::upload ( $fileTemp, $newfile )) {
				JError::raiseWarning ( 100, 'Falha ao salvar o arquivo.' );
				$erro = true;
			} else {
				$query->update ( $db->quoteName ( '#__angelgirls_modelo' ) )->set ( array (
						$db->quoteName ( 'foto_inteira' ) . ' = ' . $db->quote ( $id . '.' . $uploadedFileExtension )
				) )->where ( array (
						$db->quoteName ( 'id' ) . ' = ' . $id
				) );
				$db->setQuery ( $query );
				$db->execute ();
			}
		}
		
		
		if (! $erro) {
			JFactory::getApplication ()->enqueueMessage ( 'Modelo salvo com sucesso' );
		}
	}
	public function saveModelo() {
		$user =  JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$this->saveModeloDB ();
		$this->listModelo ();
	}
	public function listModelo() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName (array(
						'a.id','a.id_usuario','a.nome_artistico','a.descricao','a.meta_descricao','a.foto_perfil','a.foto_inteira',
						'a.altura','a.peso','a.busto','a.calsa','a.calsado','a.olhos','a.pele','a.etinia','a.cabelo','a.tamanho_cabelo',
						'a.cor_cabelo','a.outra_cor_cabelo','a.profissao','a.nascionaldiade','a.id_cidade_nasceu','a.data_nascimento',
						'a.site','a.sexo','a.cpf','a.banco','a.agencia','a.conta','a.custo_medio_diaria','a.status_modelo','a.qualificao_equipe',
						'a.audiencia_gostou','a.audiencia_ngostou','a.audiencia_view','a.id_cidade','a.status_dado','a.id_usuario_criador',
						'a.id_usuario_alterador','a.data_criado','a.data_alterado','b.name','c.name','d.name','d.username','d.email'), array (
				'id','id_usuario','nome_artistico','descricao',	'meta_descricao','foto_perfil','foto_inteira','altura','peso','busto',
				'calsa','calsado','olhos','pele','etinia','cabelo','tamanho_cabelo','cor_cabelo','outra_cor_cabelo','profissao',
				'nascionaldiade','id_cidade_nasceu','data_nascimento','site','sexo','cpf','banco','agencia','conta','custo_medio_diaria',
				'status_modelo','qualificao_equipe','audiencia_gostou','audiencia_ngostou','audiencia_view','id_cidade','status_dado',
				'id_usuario_criador','id_usuario_alterador','data_criado','data_alterado','criador','editor','nome','usuario','email')))
		->from ( $db->quoteName ( '#__angelgirls_modelo', 'a' ))
		->join ( 'INNER', $db->quoteName ( '#__users', 'b' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_criador' ) . ' = ' . $db->quoteName ( 'b.id' ) . ')' )
		->join ( 'INNER', $db->quoteName ( '#__users', 'c' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_alterador' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
		->join ( 'INNER', $db->quoteName ( '#__users', 'd' ) . ' ON (' . $db->quoteName ( 'a.id_usuario' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
		->order ( 'data_alterado ASC' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		JRequest::setVar ( 'lista', $results );
		JRequest::setVar ( 'view', 'modelos' );
		JRequest::setVar ( 'layout', 'default' );
		parent::display ();
	}
	
	
	/**
	 * ****************** CONTATOS *****************************************
	 */
	
	

	
	/**
	 * JSON metodo.
	 */
	public function saveEmail(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		$idUsuario = JRequest::getInt ( 'id_usuario', 0, 'POST' );
		$email = JRequest::getString ( 'email', null, 'POST' );
		$principal = JRequest::getString ( 'principal', 'N', 'POST' );
		
		$db = JFactory::getDbo ();

		// E-mail n�o pode estar cadastrado em outro usu�rio.
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName (array('a.id_usuario')))
						->from ( $db->quoteName ( '#__angelgirls_email', 'a' ))
						->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
						->where ( $db->quoteName ( 'email' ) . ' <> ' .  $email);
		$db->setQuery ( $query );
		$results = $db->loadObject();
		if(sizeof($results)>0){
			$mensagens=$mensagens.'"E-mail j� cadastrado em outra conta [ID USUARIO: ' . $results->id_usuario. '].",';
		}
		
		$query = $db->getQuery ( true );
		$query->select ( $db->quoteName (array('a.email')))
		->from ( $db->quoteName ( '#__angelgirls_email', 'a' ))
		->where ( $db->quoteName ( 'id_usuario' ) . ' = ' .  $idUsuario)
		->where ( $db->quoteName ( 'id' ) . ' = ' .  $id);
		
		$db->setQuery ( $query );
		$results = $db->loadObjectList ();
		if(sizeof($results)<=0){
			$mensagens=$mensagens.'"Esse ID de e-mail n�o pertence ao usu�rio.",';
		}
		
		if(strpos($email, "@") === false || strpos($email, ".", strpos($email, "@")) === false){
			$mensagens=$mensagens.'"E-mail n�o � valido.",';
		}
		
		if($mensagens == ''){
			if($principal=='S'){
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_email' ) )->set ( array (
						$db->quoteName ( 'principal' ) . ' = ' . ($db->quote ( 'N' )),
				) )->where ( array (
						$db->quoteName ( 'id_usuario' ) . ' = ' . $idUsuario
				) );
				$db->setQuery ( $query );
				 $db->execute();
			}
			
			if ($id != null && $id != 0) { // UPDATE
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_email' ) )
						->set ( array (
							$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
							$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id,
							$db->quoteName ( 'email' ) . ' = ' . ($nome == null ? 'null' : $db->quote ($email)),
							$db->quoteName ( 'principal' ) . ' = ' . ($principal == null ? 'null' : $db->quote ( $principal ))
						))
						->where ( array (
							$db->quoteName ( 'id' ) . ' = ' . $id
						));
					
				$db->setQuery ( $query );
					
				$ok = $db->execute ();
			} else {
				$query = $db->getQuery ( true );
				$query->insert ( $db->quoteName ( '#__angelgirls_email' ))
					->columns(array(
						$db->quoteName ( 'status_dado' ),
						$db->quoteName ( 'data_criado' ),
						$db->quoteName ( 'id_usuario_criador' ),
						$db->quoteName ( 'data_alterado' ),
						$db->quoteName ( 'id_usuario_alterador' ),
						$db->quoteName ( 'email' ),
						$db->quoteName ( 'id_usuario' ),
						$db->quoteName ( 'principal' ),
						$db->quoteName ( 'ordem' ) . ' = '))
					->values ( implode ( ',', array (
						'\'NOVO\'',
						'NOW()',
						$user->id,
						'NOW()',
						$user->id,
						($email == null ? 'null' : $db->quote ( $email )),
						($idUsuario == null ? 'null' : $db->quote ( $idUsuario )),
						($principal == null ? 'null' : $db->quote ( $principal )),
						'( SELECT MAX(ordem) + 1 FROM #__angelgirls_email WHERE id_usuario = '.$idUsuario.')'
					)));
					
				$db->setQuery ( $query );
				$ok = $db->execute ();
				$id = $db->insertid ();
				JRequest::setVar ( 'id', $id );
			}
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();		
	}
	
	public function saveRedeSocial(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		$idUsuario = JRequest::getInt ( 'id_usuario', 0, 'POST' );
		$redeSocial = JRequest::getString ( 'rede_social', null, 'POST' );
		$urlUsuario = JRequest::getString ( 'url_usuario', null, 'POST' );
		$principal = JRequest::getString ( 'principal', 'N', 'POST' );
		
		$db = JFactory::getDbo ();
		
		if($mensagens == ''){
			if($principal=='S'){
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_redesocial' ) )->set ( array (
						$db->quoteName ( 'principal' ) . ' = ' . ($db->quote ( 'N' )),
				) )->where ( array (
						$db->quoteName ( 'id_usuario' ) . ' = ' . $idUsuario
				) );
				$db->setQuery ( $query );
				$db->execute();
			}
				
			if ($id != null && $id != 0) { // UPDATE
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_redesocial' ) )
				->set ( array (
						$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
						$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id,
						$db->quoteName ( 'rede_social' ) . ' = ' . ($redeSocial == null ? 'null' : $db->quote ($redeSocial)),
						$db->quoteName ( 'url_usuario' ) . ' = ' . ($urlUsuario == null ? 'null' : $db->quote ($urlUsuario)),
						$db->quoteName ( 'principal' ) . ' = ' . ($principal == null ? 'null' : $db->quote ( $principal ))
				))
				->where ( array (
						$db->quoteName ( 'id' ) . ' = ' . $id
				));
					
				$db->setQuery ( $query );
					
				$ok = $db->execute ();
			} else {
				$query = $db->getQuery ( true );
				$query->insert ( $db->quoteName ( '#__angelgirls_redesocial' ))
				->columns(array(
						$db->quoteName ( 'status_dado' ),
						$db->quoteName ( 'data_criado' ),
						$db->quoteName ( 'id_usuario_criador' ),
						$db->quoteName ( 'data_alterado' ),
						$db->quoteName ( 'id_usuario_alterador' ),
						$db->quoteName ( 'rede_social' ),
						$db->quoteName ( 'url_usuario' ),
						$db->quoteName ( 'id_usuario' ),
						$db->quoteName ( 'principal' ),
						$db->quoteName ( 'ordem' ) . ' = '))
						->values ( implode ( ',', array (
								'\'NOVO\'',
								'NOW()',
								$user->id,
								'NOW()',
								$user->id,
								($redeSocial == null ? 'null' : $db->quote ( $redeSocial )),
								($urlUsuario == null ? 'null' : $db->quote ( $urlUsuario )),
								($idUsuario == null ? 'null' : $db->quote ( $idUsuario )),
								($principal == null ? 'null' : $db->quote ( $principal )),
								'( SELECT MAX(ordem) + 1 FROM #__angelgirls_email WHERE id_usuario = '.$idUsuario.')'
						)));
							
						$db->setQuery ( $query );
						$ok = $db->execute ();
						$id = $db->insertid ();
						JRequest::setVar ( 'id', $id );
			}
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();
	}
	
	public function saveEndereco(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		$idUsuario = JRequest::getInt ( 'id_usuario', 0, 'POST' );
		$endereco = JRequest::getString ( 'endereco', null, 'POST' );
		$numero = JRequest::getString ( 'numero', null, 'POST' );
		$bairro = JRequest::getString ( 'bairro', null, 'POST' );
		$complemento = JRequest::getString ( 'complemento', null, 'POST' );
		$cep = JRequest::getString ( 'cep', null, 'POST' );
		$idCidade = JRequest::getInt( 'id_cidade', null, 'POST' );
		$tipo = JRequest::getString ( 'tipo', null, 'POST' );
		
		$principal = JRequest::getString ( 'principal', 'N', 'POST' );
		
		$db = JFactory::getDbo ();
		
		if($mensagens == ''){
			if($principal=='S'){
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_endereco' ) )->set ( array (
						$db->quoteName ( 'principal' ) . ' = ' . ($db->quote ( 'N' )),
				) )->where ( array (
						$db->quoteName ( 'id_usuario' ) . ' = ' . $idUsuario
				) );
				$db->setQuery ( $query );
				$db->execute();
			}
		
			if ($id != null && $id != 0) { // UPDATE
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_endereco' ) )
				->set ( array (
						$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
						$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id,
						$db->quoteName ( 'tipo' ) . ' = ' . ($tipo == null ? 'null' : $db->quote ($tipo)),
						$db->quoteName ( 'endereco' ) . ' = ' . ($endereco == null ? 'null' : $db->quote ($endereco)),
						$db->quoteName ( 'numero' ) . ' = ' . ($numero == null ? 'null' : $db->quote ($numero)),
						$db->quoteName ( 'bairro' ) . ' = ' . ($bairro == null ? 'null' : $db->quote ($bairro)),
						$db->quoteName ( 'complemento' ) . ' = ' . ($complemento == null ? 'null' : $db->quote ($complemento)),
						$db->quoteName ( 'cep' ) . ' = ' . ($cep == null ? 'null' : $db->quote ($cep)),
						$db->quoteName ( 'idCidade' ) . ' = ' . ($idCidade == null ? 'null' : $idCidade),
		
						$db->quoteName ( 'principal' ) . ' = ' . ($principal == null ? 'null' : $db->quote ( $principal ))
				))
				->where ( array (
						$db->quoteName ( 'id' ) . ' = ' . $id
				));
					
				$db->setQuery ( $query );
					
				$ok = $db->execute ();
			} else {
				$query = $db->getQuery ( true );
				$query->insert ( $db->quoteName ( '#__angelgirls_endereco' ))
				->columns(array(
						$db->quoteName ( 'status_dado' ),
						$db->quoteName ( 'data_criado' ),
						$db->quoteName ( 'id_usuario_criador' ),
						$db->quoteName ( 'data_alterado' ),
						$db->quoteName ( 'id_usuario_alterador' ),
						$db->quoteName ( 'tipo' ),
						$db->quoteName ( 'endereco' ),
						$db->quoteName ( 'numero' ),
						$db->quoteName ( 'bairro' ),
						$db->quoteName ( 'complemento' ),
						$db->quoteName ( 'cep' ),
						$db->quoteName ( 'idCidade' ),
						$db->quoteName ( 'id_usuario' ),
						$db->quoteName ( 'principal' ),
						$db->quoteName ( 'ordem' ) . ' = '))
					->values ( implode ( ',', array (
							'\'NOVO\'',
							'NOW()',
							$user->id,
							'NOW()',
							$user->id,
							($tipo == null ? 'null' : $db->quote ($tipo)),
							($endereco == null ? 'null' : $db->quote ($endereco)),
							($numero == null ? 'null' : $db->quote ($numero)),
							($bairro == null ? 'null' : $db->quote ($bairro)),
							($complemento == null ? 'null' : $db->quote ($complemento)),
							($cep == null ? 'null' : $db->quote ($cep)),
							($idCidade == null ? 'null' : $idCidade),
							($idUsuario == null ? 'null' : $db->quote ( $idUsuario )),
							($principal == null ? 'null' : $db->quote ( $principal )),
							'( SELECT MAX(ordem) + 1 FROM #__angelgirls_email WHERE id_usuario = '.$idUsuario.')'
					)));
						
				$db->setQuery ( $query );
				$ok = $db->execute ();
				$id = $db->insertid ();
				JRequest::setVar ( 'id', $id );
			}
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();
	}
	
	public function saveTelefone(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		$idUsuario = JRequest::getInt ( 'id_usuario', 0, 'POST' );
		$operadora = JRequest::getString ( 'operadora', null, 'POST' );
		$ddi = JRequest::getString ( 'ddi', null, 'POST' );
		$ddd = JRequest::getString ( 'ddd', null, 'POST' );
		$telefone = JRequest::getString ( 'telefone', null, 'POST' );
		$tipo = JRequest::getString ( 'tipo', null, 'POST' );
		
		$principal = JRequest::getString ( 'principal', 'N', 'POST' );
		
		$db = JFactory::getDbo ();
		
		if($mensagens == ''){
			if($principal=='S'){
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_telefone' ) )->set ( array (
						$db->quoteName ( 'principal' ) . ' = ' . ($db->quote ( 'N' )),
				) )->where ( array (
						$db->quoteName ( 'id_usuario' ) . ' = ' . $idUsuario
				) );
				$db->setQuery ( $query );
				$db->execute();
			}
		
			if ($id != null && $id != 0) { // UPDATE
				$query = $db->getQuery ( true );
				$query->update ( $db->quoteName ( '#__angelgirls_telefone' ) )
				->set ( array (
						$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
						$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id,
						$db->quoteName ( 'tipo' ) . ' = ' . ($tipo == null ? 'null' : $db->quote ($tipo)),
						$db->quoteName ( 'operadora' ) . ' = ' . ($operadora == null ? 'null' : $db->quote ($operadora)),
						//$db->quoteName ( 'ddi' ) . ' = ' . ($ddi == null ? 'null' : $db->quote ($ddi)),
						$db->quoteName ( 'ddd' ) . ' = ' . ($ddd == null ? 'null' : $db->quote ($ddd)),
						$db->quoteName ( 'telefone' ) . ' = ' . ($telefone == null ? 'null' : $db->quote ($telefone)),
						
						$db->quoteName ( 'principal' ) . ' = ' . ($principal == null ? 'null' : $db->quote ( $principal ))
				))
				->where ( array (
						$db->quoteName ( 'id' ) . ' = ' . $id
				));
					
				$db->setQuery ( $query );
					
				$ok = $db->execute ();
			} else {
				$query = $db->getQuery ( true );
				$query->insert ( $db->quoteName ( '#__angelgirls_telefone' ))
				->columns(array(
						$db->quoteName ( 'status_dado' ),
						$db->quoteName ( 'data_criado' ),
						$db->quoteName ( 'id_usuario_criador' ),
						$db->quoteName ( 'data_alterado' ),
						$db->quoteName ( 'id_usuario_alterador' ),
						$db->quoteName ( 'tipo' ),
						$db->quoteName ( 'operadora' ),
						//$db->quoteName ( 'ddi' ),
						$db->quoteName ( 'ddd' ),
						$db->quoteName ( 'telefone' ),
						$db->quoteName ( 'id_usuario' ),
						$db->quoteName ( 'principal' ),
						$db->quoteName ( 'ordem' ) . ' = '))
						->values ( implode ( ',', array (
								'\'NOVO\'',
								'NOW()',
								$user->id,
								'NOW()',
								$user->id,
								($tipo == null ? 'null' : $db->quote ($tipo)),
								($operadora == null ? 'null' : $db->quote ( $operadora )),
						//		($ddi == null ? 'null' : $db->quote ( $ddi )),
								($ddd == null ? 'null' : $db->quote ( $ddd )),
								($telefone == null ? 'null' : $db->quote ( $telefone )),
								($idUsuario == null ? 'null' : $db->quote ( $idUsuario )),
								($principal == null ? 'null' : $db->quote ( $principal )),
								'( SELECT MAX(ordem) + 1 FROM #__angelgirls_email WHERE id_usuario = '.$idUsuario.')'
						)));
					
				$db->setQuery ( $query );
				$ok = $db->execute ();
				$id = $db->insertid ();
				JRequest::setVar ( 'id', $id );
			}
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();
	}
	
	public function removeEmail(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		
		$db = JFactory::getDbo ();
		

		if($id == 0){
			$mensagens=$mensagens.'"Esse ID de e-mail n�o pertence ao usu�rio.",';
		}
		

		
		if($mensagens == ''){
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
				
			$query->update ( $db->quoteName ( '#__angelgirls_email' ) )->set ( array (
					$db->quoteName ( 'status_dado' ) . ' = \'REMOVED\'',
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id))
			->where (array ($db->quoteName ( 'id' ) . ' = ' . $id));
				
			$db->setQuery ( $query );
				
			$db->execute ();
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();
	}
	
	public function removeRedeSocial(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		
		$db = JFactory::getDbo ();
		
		
		if($id == 0){
			$mensagens=$mensagens.'"Esse ID de rede social n�o pertence ao usu�rio.",';
		}
		
		
		
		if($mensagens == ''){
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
		
			$query->update ( $db->quoteName ( '#__angelgirls_redesocial' ) )->set ( array (
					$db->quoteName ( 'status_dado' ) . ' = \'REMOVED\'',
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id))
					->where (array ($db->quoteName ( 'id' ) . ' = ' . $id));
		
			$db->setQuery ( $query );
		
			$db->execute ();
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();
	}
	
	public function removeEndereco(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		
		$db = JFactory::getDbo ();
		
		
		if($id == 0){
			$mensagens=$mensagens.'"Esse ID de Endereco n�o pertence ao usu�rio.",';
		}
		
		
		
		if($mensagens == ''){
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
		
			$query->update ( $db->quoteName ( '#__angelgirls_endereco' ) )->set ( array (
					$db->quoteName ( 'status_dado' ) . ' = \'REMOVED\'',
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id))
					->where (array ($db->quoteName ( 'id' ) . ' = ' . $id));
		
			$db->setQuery ( $query );
		
			$db->execute ();
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();
	}
	
	public function removeTelefone(){
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$ok=null;
		$mensagens = '';
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		
		$db = JFactory::getDbo ();
		
		
		if($id == 0){
			$mensagens=$mensagens.'"Esse ID de telefone n�o pertence ao usu�rio.",';
		}
		
		
		
		if($mensagens == ''){
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
		
			$query->update ( $db->quoteName ( '#__angelgirls_telefone' ) )->set ( array (
					$db->quoteName ( 'status_dado' ) . ' = \'REMOVED\'',
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id))
					->where (array ($db->quoteName ( 'id' ) . ' = ' . $id));
		
			$db->setQuery ( $query );
		
			$db->execute ();
		}
		
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="progress-report-results.json"');
		echo "{'ok'='".$ok."','mensagens=[".$mensagens."]'}";
		JFactory::getApplication()->close(); // or jexit();
	}
	
	/**
	 * ****************** TEMA *****************************************
	 */
	public function deleteTema() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ) || ! JSession::checkToken ( 'post' ))
			die ( 'Restricted access' );
		
		$id = JRequest::getInt ( 'id', 0 );
		
		if ($id != 0) {
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			
			$query->update ( $db->quoteName ( '#__angelgirls_tema' ) )->set ( array (
					$db->quoteName ( 'status_dado' ) . ' = \'REMOVED\'',
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id 
			) )->where ( array (
					$db->quoteName ( 'id' ) . ' = ' . $id 
			) );
			
			$db->setQuery ( $query );
			
			$db->execute ();
		}
		
		$this->listTema ();
	}
	
	public function editTema() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		$id = JRequest::getInt ( 'id', 0 );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName ( array (
				'a.id','a.nome','a.nome_foto','a.descricao','a.meta_descricao',	'a.audiencia_gostou','a.audiencia_ngostou',	'a.audiencia_view',
				'a.status_dado','a.id_usuario_criador','a.id_usuario_alterador','a.data_criado','a.data_alterado','b.name',	'c.name'), array (
				'id','nome','nome_foto','descricao','meta_descricao','audiencia_gostou','audiencia_ngostou','audiencia_view',
				'status_dado','id_usuario_criador','id_usuario_alterador','data_criado','data_alterado','criador','editor')))
		->from($db->quoteName ( '#__angelgirls_tema', 'a' ))
		->join ( 'INNER', $db->quoteName ( '#__users', 'b' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_criador' ) . ' = ' . $db->quoteName ( 'b.id' ) . ')' )
		->join ( 'INNER', $db->quoteName ( '#__users', 'c' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_alterador' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
		->where ( $db->quoteName ( 'a.id' ) . ' = ' . $id )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' );
		
		$db->setQuery ( $query );
		
		$result = $db->loadObject ();
		JRequest::setVar ( 'tema', $result );
		
		$this->addTema ();
	}
	
	/**
	 * S� carrrega a tela de adi��o.
	 */
	public function addTema() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		JRequest::setVar ( 'view', 'temas' );
		JRequest::setVar ( 'layout', 'cadastro' );
		parent::display ();
	}
	public function applayTema() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$this->saveTemaDB ();
		$this->editTema ();
	}
	
	/**
	 * Apenas salva no banco o dado.
	 */
	public function saveTemaDB() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ) || ! JSession::checkToken ( 'post' ))
			die ( 'Restricted access' );
		$uploadPath = JPATH_SITE . DS . 'images' . DS . 'temas' . DS;
		$erro = false;
		
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		$nome = JRequest::getString ( 'nome', '', 'POST' );
		$descricao = JRequest::getString ( 'descricao', '', 'POST' );
		$metaDescricao = JRequest::getString ( 'meta_descricao', '', 'POST' );
		
		$foto = $_FILES ['foto'];
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		if ($id != null && $id != 0) { // UPDATE
			
			$query->update ( $db->quoteName ( '#__angelgirls_tema' ) )->set ( array (
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id,
					$db->quoteName ( 'nome' ) . ' = ' . ($nome == null ? 'null' : $db->quote ( $nome )),
					$db->quoteName ( 'descricao' ) . ' = ' . ($descricao == null ? 'null' : $db->quote ( $descricao )),
					$db->quoteName ( 'meta_descricao' ) . ' = ' . ($metaDescricao == null ? 'null' : $db->quote ( $metaDescricao )) 
			) )->where ( array (
					$db->quoteName ( 'id' ) . ' = ' . $id 
			) );
			
			$db->setQuery ( $query );
			
			$db->execute ();
		} else {
			$query->insert ( $db->quoteName ( '#__angelgirls_tema' ) )->columns ( array (
					$db->quoteName ( 'status_dado' ),
					$db->quoteName ( 'data_criado' ),
					$db->quoteName ( 'id_usuario_criador' ),
					$db->quoteName ( 'data_alterado' ),
					$db->quoteName ( 'id_usuario_alterador' ),
					$db->quoteName ( 'nome' ),
					$db->quoteName ( 'descricao' ),
					$db->quoteName ( 'meta_descricao' ) 
			) )->values ( implode ( ',', array (
					'\'NOVO\'',
					'NOW()',
					$user->id,
					'NOW()',
					$user->id,
					($nome == null ? 'null' : $db->quote ( $nome )),
					($descricao == null ? 'null' : $db->quote ( $descricao )),
					($metaDescricao == null ? 'null' : $db->quote ( $metaDescricao )) 
			) ) );
			
			$db->setQuery ( $query );
			
			$db->execute ();
			
			$id = $db->insertid ();
			
			JRequest::setVar ( 'id', $id );
		}
		
		if (isset ( $foto ) && JFolder::exists ( $foto ['tmp_name'] )) {
			$fileName = $foto ['name'];
			$uploadedFileNameParts = explode ( '.', $fileName );
			$uploadedFileExtension = array_pop ( $uploadedFileNameParts );
			
			$fileTemp = $foto ['tmp_name'];
			$newfile = $uploadPath . $id . '.' . $uploadedFileExtension;
			
			if (JFolder::exists ( $newfile )) {
				JFile::delete ( $newfile );
			}
			
			if (! JFile::upload ( $fileTemp, $newfile )) {
				JError::raiseWarning ( 100, 'Falha ao salvar o arquivo.' );
				$erro = true;
			} else {
				$query->update ( $db->quoteName ( '#__angelgirls_tema' ) )->set ( array (
						$db->quoteName ( 'nome_foto' ) . ' = ' . $db->quote ( $id . '.' . $uploadedFileExtension ) 
				) )->where ( array (
						$db->quoteName ( 'id' ) . ' = ' . $id 
				) );
				$db->setQuery ( $query );
				$db->execute ();
			}
		}
		if (! $erro) {
			JFactory::getApplication ()->enqueueMessage ( 'Tema salvo com sucesso' );
		}
	}
	public function saveTema() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$this->saveTemaDB ();
		$this->listTema ();
	}
	public function listTema() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName ( array (
				'a.id','a.nome','a.descricao','a.nome_foto',
				'a.meta_descricao','a.audiencia_gostou','a.audiencia_ngostou',
				'a.audiencia_view','a.status_dado','a.id_usuario_criador',
				'a.id_usuario_alterador','a.data_criado','a.data_alterado',
				'b.name','c.name'), array (
				'id','nome','descricao','nome_foto',
				'meta_descricao','audiencia_gostou','audiencia_ngostou',
				'audiencia_view','status_dado',	'id_usuario_criador',
				'id_usuario_alterador',	'data_criado','data_alterado',
				'criador','editor')))
		->from ( $db->quoteName ( '#__angelgirls_tema', 'a' ) )
		->join ( 'INNER', $db->quoteName ( '#__users', 'b' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_criador' ) . ' = ' . $db->quoteName ( 'b.id' ) . ')' )
		->join ( 'INNER', $db->quoteName ( '#__users', 'c' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_alterador' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
		->order ( 'data_alterado ASC' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		JRequest::setVar ( 'lista', $results );
		JRequest::setVar ( 'view', 'temas' );
		JRequest::setVar ( 'layout', 'default' );
		parent::display ();
	}
	
	/**
	 * ****************** AGENDA *****************************************
	 */
	public function deleteAgenda() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ) || ! JSession::checkToken ( 'post' ))
			die ( 'Restricted access' );
		
		$id = JRequest::getInt ( 'id', 0 );
		
		if ($id != 0) {
			$db = JFactory::getDbo ();
			$query = $db->getQuery ( true );
			
			$query->update ( $db->quoteName ( '#__angelgirls_agenda' ) )->set ( array (
					$db->quoteName ( 'status_dado' ) . ' = \'REMOVED\'',
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id 
			) )->where ( array (
					$db->quoteName ( 'id' ) . ' = ' . $id 
			) );
			
			$db->setQuery ( $query );
			
			$db->execute ();
		}
		
		$this->listAgenda ();
	}
	public function editAgenda() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		$id = JRequest::getInt ( 'id', 0 );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName ( array (
				'a.id','a.data','a.titulo','a.tipo','a.descricao','a.meta_descricao','a.audiencia_gostou','a.audiencia_ngostou','a.audiencia_view',
				'a.id_tema','a.id_modelo','a.id_locacao','a.id_fotografo','a.publicar','a.status_dado','a.id_usuario_criador','a.id_usuario_alterador',
				'a.data_criado','a.data_alterado','b.name','c.name' 
		), array (
				'id','data','titulo','tipo','descricao','meta_descricao','audiencia_gostou','audiencia_ngostou','audiencia_view','id_tema','id_modelo',
				'id_locacao','id_fotografo','publicar','status_dado','id_usuario_criador','id_usuario_alterador','data_criado','data_alterado','criador',
				'editor')))
		->from ( $db->quoteName ( '#__angelgirls_agenda', 'a' ) )
		->join ( 'INNER', $db->quoteName ( '#__users', 'b' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_criador' ) . ' = ' . $db->quoteName ( 'b.id' ) . ')' )
		->join ( 'INNER', $db->quoteName ( '#__users', 'c' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_alterador' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
		->where ( $db->quoteName ( 'a.id' ) . ' = ' . $id )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' );
		
		$db->setQuery ( $query );
		
		$result = $db->loadObject ();
		JRequest::setVar ( 'agenda', $result );
		
		$this->addAgenda ();
	}
	
	/**
	 * S� carrrega a tela de adi��o.
	 */
	public function addAgenda() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName ( array (
				'a.id',
				'a.id_usuario',
				'a.nome_artistico' 
		), array (
				'id',
				'id_usuario',
				'nome' 
		) ) )->from ( $db->quoteName ( '#__angelgirls_modelo', 'a' ) )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
		->order ( $db->quoteName ( 'a.nome_artistico' ) . ' DESC' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList ();
		JRequest::setVar ( 'modelos', $result );
		
		$query->select ( $db->quoteName ( array (
				'a.id',
				'a.id_usuario',
				'a.nome_artistico' 
		), array (
				'id',
				'id_usuario',
				'nome' 
		) ) )->from ( $db->quoteName ( '#__angelgirls_fotografo', 'a' ) )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
		->order ( $db->quoteName ( 'a.nome_artistico' ) . ' DESC' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList ();
		JRequest::setVar ( 'fotografos', $result );

		
		$query->select ( $db->quoteName ( array (
				'a.id',
				'a.nome' 
		) ) )->from ( $db->quoteName ( '#__angelgirls_tema', 'a' ) )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
		->order ( $db->quoteName ( 'a.nome' ) . ' DESC' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList ();
		JRequest::setVar ( 'temas', $result );
		
		$query->select ( $db->quoteName ( array (
				'a.id',
				'a.nome' 
		) ) )->from ( $db->quoteName ( '#__angelgirls_locacao', 'a' ) )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
		->order ( $db->quoteName ( 'a.nome' ) . ' DESC' );
		$db->setQuery ( $query );
		$result = $db->loadObjectList ();
		JRequest::setVar ( 'locacoes', $result );
		
		JRequest::setVar ( 'view', 'agendas' );
		JRequest::setVar ( 'layout', 'cadastro' );
		parent::display ();
	}
	public function applayAgenda() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$this->saveAgendaDB ();
		$this->editAgenda ();
	}
	
	/**
	 * Apenas salva no banco o dado.
	 */
	public function saveAgendaDB() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ) || ! JSession::checkToken ( 'post' ))
			die ( 'Restricted access' );
			
			// JError::raiseNotice( 100, 'Notice' );
			// JError::raiseWarning( 100, 'Warning' );
			// JError::raiseError( 4711, 'A severe error occurred' );
		
		$id = JRequest::getInt ( 'id', 0, 'POST' );
		$titulo = JRequest::getString ( 'titulo', '', 'POST' );
		$tipo = JRequest::getString ( 'tipo', '', 'POST' );
		$descricao = JRequest::getString ( 'descricao', '', 'POST' );
		$metaDescricao = JRequest::getString ( 'meta_descricao', '', 'POST' );
		
		$idTema = JRequest::getInt ( 'id_tema', null, 'POST' );
		$idModelo = JRequest::getInt ( 'id_modelo', null, 'POST' );
		$idLocacao = JRequest::getInt ( 'id_locacao', null, 'POST' );
		$idFotografo = JRequest::getInt ( 'id_fotografo', null, 'POST' );
		$publicar = JRequest::getString ( 'publicar', 'N', 'POST' );
		$data = JRequest::getString ( 'data', null, 'POST' );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		if ($id != null && $id != 0) { // UPDATE
			
			$query->update ( $db->quoteName ( '#__angelgirls_agenda' ) )->set ( array (
					$db->quoteName ( 'data_alterado' ) . ' = NOW() ',
					$db->quoteName ( 'id_usuario_alterador' ) . ' = ' . $user->id,
					$db->quoteName ( 'titulo' ) . ' = ' . ($titulo == null ? 'null' : $db->quote ( $titulo )),
					$db->quoteName ( 'tipo' ) . ' = ' . ($tipo == null ? 'null' : $db->quote ( $tipo )),
					$db->quoteName ( 'descricao' ) . ' = ' . ($descricao == null ? 'null' : $db->quote ( $descricao )),
					$db->quoteName ( 'meta_descricao' ) . ' = ' . ($metaDescricao == null ? 'null' : $db->quote ( $metaDescricao )),
					$db->quoteName ( 'id_tema' ) . ' = ' . ($idTema == null ? 'null' : $idTema),
					$db->quoteName ( 'id_modelo' ) . ' = ' . ($idModelo == null ? 'null' : $idModelo),
					$db->quoteName ( 'id_locacao' ) . ' = ' . ($idLocacao == null ? 'null' : $idLocacao),
					$db->quoteName ( 'id_fotografo' ) . ' = ' . ($idFotografo == null ? 'null' : $idFotografo),
					$db->quoteName ( 'id_fotografo' ) . ' = ' . ($idFotografo == null ? 'null' : $idFotografo),
					$db->quoteName ( 'data' ) . ' = ' . ($data == null ? 'null' : $db->quote ( $data )) 
			) )->where ( array (
					$db->quoteName ( 'id' ) . ' = ' . $id 
			) );
			
			$db->setQuery ( $query );
			
			$db->execute ();
		} else {
			$query->insert ( $db->quoteName ( '#__angelgirls_agenda' ) )->columns ( array (
					$db->quoteName ( 'status_dado' ),
					$db->quoteName ( 'data_criado' ),
					$db->quoteName ( 'id_usuario_criador' ),
					$db->quoteName ( 'data_alterado' ),
					$db->quoteName ( 'id_usuario_alterador' ),
					$db->quoteName ( 'titulo' ),
					$db->quoteName ( 'tipo' ),
					$db->quoteName ( 'descricao' ),
					$db->quoteName ( 'meta_descricao' ),
					$db->quoteName ( 'id_tema' ),
					$db->quoteName ( 'id_modelo' ),
					$db->quoteName ( 'id_locacao' ),
					$db->quoteName ( 'id_fotografo' ),
					$db->quoteName ( 'publicar' ),
					$db->quoteName ( 'data' ) 
			) )->values ( implode ( ',', array (
					'\'NOVO\'',
					'NOW()',
					$user->id,
					'NOW()',
					$user->id,
					($titulo == null ? 'null' : $db->quote ( $titulo )),
					($tipo == null ? 'null' : $db->quote ( $tipo )),
					($descricao == null ? 'null' : $db->quote ( $descricao )),
					($metaDescricao == null ? 'null' : $db->quote ( $metaDescricao )),
					($idTema == null ? 'null' : $idTema),
					($idModelo == null ? 'null' : $idModelo),
					($idLocacao == null ? 'null' : $idLocacao),
					($idFotografo == null ? 'null' : $idFotografo),
					($publicar == null ? 'null' : $db->quote ( $publicar )),
					($data == null ? 'null' : $db->quote ( $data )) 
			) ) );
			
			$db->setQuery ( $query );
			
			$db->execute ();
			
			JRequest::setVar ( 'id', $db->insertid () );
		}
		
		JFactory::getApplication ()->enqueueMessage ( 'Agenda salva com sucesso' );
	}
	public function saveAngenda() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$this->saveAgendaDB ();
		$this->listAgenda ();
	}
	public function listAgenda() {
		$user = & JFactory::getUser ();
		if (! isset ( $user ))
			die ( 'Restricted access' );
		
		$db = JFactory::getDbo ();
		$query = $db->getQuery ( true );
		
		$query->select ( $db->quoteName ( array (
				'a.id','a.data','a.titulo','a.tipo','a.descricao','a.meta_descricao','a.audiencia_gostou','a.audiencia_ngostou','a.audiencia_view','a.id_tema',
				'a.id_modelo','a.id_locacao','a.id_fotografo','a.publicar','a.status_dado','a.id_usuario_criador','a.id_usuario_alterador','a.data_criado',
				'a.data_alterado','b.name','c.name' 
		), array (
				'id','data','titulo','tipo','descricao','meta_descricao','audiencia_gostou','audiencia_ngostou','audiencia_view','id_tema','id_modelo',
				'id_locacao','id_fotografo','publicar','status_dado','id_usuario_criador','id_usuario_alterador','data_criado','data_alterado','criador',
				'editor')))
		->from ( $db->quoteName ( '#__angelgirls_agenda', 'a' ) )
		->join ( 'INNER', $db->quoteName ( '#__users', 'b' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_criador' ) . ' = ' . $db->quoteName ( 'b.id' ) . ')' )
		->join ( 'INNER', $db->quoteName ( '#__users', 'c' ) . ' ON (' . $db->quoteName ( 'a.id_usuario_alterador' ) . ' = ' . $db->quoteName ( 'c.id' ) . ')' )
		->where ( $db->quoteName ( 'status_dado' ) . ' <> \'REMOVED\' ' )
		->order ( 'data_alterado ASC' );
		
		$db->setQuery ( $query );
		
		$results = $db->loadObjectList ();
		
		JRequest::setVar ( 'lista', $results );
		JRequest::setVar ( 'view', 'agendas' );
		JRequest::setVar ( 'layout', 'default' );
		parent::display ();
	}
}
?>