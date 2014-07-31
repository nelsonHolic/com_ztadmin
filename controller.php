<?php

/**
 * @version		$Id: controller.php 20553 2011-02-06 06:32:09Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_ztelecliente
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');



require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Session.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Menu.php' );

require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GuiLeftHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GuiHeaderHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GuiNotificationBarHelper.php' );
//require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GuiInboxBarHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GuiTaskBarHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GuiUserDropdownHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'PageHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'UtilHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GraphicButtonHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'PortletHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'BasicPageHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'Graphic2DHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'GuiHelper.php' );

require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'gui' . DS . 'JsHelper.php' );

require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Configuration.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'ZDBHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Constantes.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Util.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'FileHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'TiquetesHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Editor.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'ZMailHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'ZDateHelper.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Log.php' );
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'Validation.php' );

require_once( JPATH_COMPONENT . DS . 'helpers' . DS .  'ZExcelHelper.php' );

//Model
require_once( JPATH_COMPONENT . DS . 'helpers' . DS .   'model' . DS . 'Listas.php' );


/**
 * Base controller class for Users.
 *
 * @package		Joomla.Site
 * @subpackage	com_ztelecliente
 * @since		1.6
 */
class SegurosController extends JController
{

	public function isLoggedUser(){
		$user = JFactory::getUser();
		if($user->id > 0 ){
			return true;
		}
		else{
			return false;
		}
	}

	
	/**
	 * Despliega un formulario de login
	 *
	 */
	public function userFormLogin(){	
		$user = JFactory::getUser();
		if($user->id > 0 ){
			$this->checkUserDashboard();
		}
		else{
			$document =& JFactory::getDocument();
			$viewName	= "userFormLogin";
			//$model = $this->getModel('ZMiUne' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			//$view->setModel($model , true);
			$view->display();	
		}
	}
	
	/**
	 * Login the user in the system joomla enabled
	 *
	 */
	public function userLogin(){
		JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));	
		//print_r(JRequest::get('post'));
		$app = JFactory::getApplication();
	
		// Populate the data array:
		$data = array();
		$data['return'] = base64_decode(JRequest::getVar('return', '', 'POST', 'BASE64'));
		$data['username'] = JRequest::getVar('username', '', 'method', 'username');
		$data['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);

		// Set the return URL if empty.
		if (empty($data['return'])) {
			$data['return'] = 'index.php?option=com_ztadmin';
		}
		
		// Get the log in options.
		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = $data['return'];
		//$options['silent'] = 1;

		// Get the log in credentials
		$credentials = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $data['password'];
		
		$error = $app->login($credentials, $options);
		
		// Check if the log in succeeded.
		if (!JError::isError($error)) {
			$app->setUserState('users.login.form.data', array());
			$user = JFactory::getUser();
			
			if($user->id > 0){
				//Get the  user type and redirect
				$this->checkUserDashboard();
			}
			else{
				$app->setUserState('users.login.form.data', $data);
				$app->redirect(JRoute::_('index.php?option=com_ztadmin&task=userFormLogin&error=2'));
			}
				
		} else {
			$data['remember'] = (int)$options['remember'];
			$app->setUserState('users.login.form.data', $data);
			$app->redirect(JRoute::_('index.php?option=com_ztadmin&task=userFormLogin&error=1'));
		}
	}
	
	/**
	 * Logout 
	 *
	*/ 
	public function userLogout(){
		$app = JFactory::getApplication();
		$error = $app->logout();
		$app->redirect('index.php?option=com_ztadmin');	
	}
	
	public function checkUserDashboard(){
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
	
		//Redirect depending of user
		if(isset($user->tipo) ){
			if($user->tipo == 'A'){
				$app->redirect(JRoute::_('index.php?option=com_ztadmin&task=adDashboard', false));	
			}
			else if ($user->tipo == 'U'){
				//$app->redirect(JRoute::_('index.php?option=com_ztadmin&task=veDashboard', false));	
				$app->redirect(JRoute::_('index.php?option=com_ztadmin&task=usDashboard', false));	
			}
		}
		else{
			$this->userFormLogin();
		}
		
	}
	
	/************************************ Acciones Genericas *************************************************/
	
	public function geCambiarClaveForm(){
		$user = JFactory::getUser();
		if($user->id > 0 ){
			$document =& JFactory::getDocument();
			$viewName	= "geCambiarClaveForm";
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function geCambiarClaveSave(){
		$user = JFactory::getUser();
		if($user->id > 0 ){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "geCambiarClaveSave";
			$model = $this->getModel('Generico' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}

	public function userFormRecuperarClave(){	
		$user = JFactory::getUser();
		if($user->id > 0 ){
			$this->checkUserDashboard();
		}
		else{
			$document =& JFactory::getDocument();
			$viewName	= "userFormRecuperarClave";
			//$model = $this->getModel('ZMiUne' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			//$view->setModel($model , true);
			$view->display();	
		}
	}
	
	public function userFormCrearUsuario(){	
		$user = JFactory::getUser();
		if($user->id > 0 ){
			$this->checkUserDashboard();
		}
		else{
			$document =& JFactory::getDocument();
			$viewName	= "userFormCrearUsuario";
			//$model = $this->getModel('ZMiUne' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			//$view->setModel($model , true);
			$view->display();	
		}
	}
	
	
	/*************************************************************** Opciones administrador sistema ad********************************************/
	
	public function adDashboard(){
			$user = JFactory::getUser();
			if($user->id > 0  && $user->tipo=='A'){
				$document =& JFactory::getDocument();
				$viewName	= "admDashboard";
				$model = $this->getModel('Administrador' , 'Model');
				$viewType	= $document->getType();
				$view = &$this->getView($viewName, $viewType , 'View');
				$view->setModel($model , true);
				$view->display();	
			}
			else{
				$this->userFormLogin();
			}
	}
		
	public function adUsuariosListar(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='A'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "adUsuariosListar";
			$model = $this->getModel('Administrador' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function adUsuarioDelete(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='A'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "adUsuarioDelete";
			$model = $this->getModel('Administrador' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	
	
	
	
	public function usDashboard(){
			$user = JFactory::getUser();
			if($user->id > 0  && $user->tipo=='U'){
				$document =& JFactory::getDocument();
				$viewName	= "usDashboard";
				//$model = $this->getModel('Usuario' , 'Model');
				$viewType	= $document->getType();
				$view = &$this->getView($viewName, $viewType , 'View');
				//$view->setModel($model , true);
				$view->display();	
			}
			else{
				$this->userFormLogin();
			}
	}

	
	////////////////////////////////////////////////////*Aseguradora*//////////////////////////////////////////////////////////////
	public function usAseguradoraList(){
		$user = JFactory::getUser();
		if( $user->id > 0  &&  $user->tipo == 'U' ) {
			$document =& JFactory::getDocument();
			$viewName	= "usAseguradoraList";
			$model = $this->getModel('Aseguradora' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usAseguradoraForm(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usAseguradoraForm";
			$model = $this->getModel('Aseguradora' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usAseguradoraSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usAseguradoraSave";
			$model = $this->getModel('Aseguradora' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usAseguradoraDelete(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usAseguradoraDelete";
			$model = $this->getModel('Aseguradora' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}

	////////////////////////////////////////////////////*Ciiu*//////////////////////////////////////////////////////////////
	public function usCiiuList(){
		$user = JFactory::getUser();
		if( $user->id > 0  &&  $user->tipo == 'U' ) {
			$document =& JFactory::getDocument();
			$viewName	= "usCiiuList";
			$model = $this->getModel('Ciiu' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usCiiuForm(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usCiiuForm";
			$model = $this->getModel('Ciiu' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usCiiuSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usCiiuSave";
			$model = $this->getModel('Ciiu' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usCiiuDelete(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usCiiuDelete";
			$model = $this->getModel('Ciiu' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	////////////////////////////////////////////////////*Ramo*//////////////////////////////////////////////////////////////
	public function usRamoList(){
		$user = JFactory::getUser();
		if( $user->id > 0  &&  $user->tipo == 'U' ) {
			$document =& JFactory::getDocument();
			$viewName	= "usRamoList";
			$model = $this->getModel('Ramo' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usRamoForm(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usRamoForm";
			$model = $this->getModel('Ramo' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usRamoSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usRamoSave";
			$model = $this->getModel('Ramo' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usRamoDelete(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usRamoDelete";
			$model = $this->getModel('Ramo' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}

	////////////////////////////////////////////////////*Poliza*//////////////////////////////////////////////////////////////
	public function usPolizaList(){
		$user = JFactory::getUser();
		if( $user->id > 0  &&  $user->tipo == 'U' ) {
			$document =& JFactory::getDocument();
			$viewName	= "usPolizaList";
			$model = $this->getModel('Poliza' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usPolizaForm(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usPolizaForm";
			$model = $this->getModel('Poliza' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usPolizaSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usPolizaSave";
			$model = $this->getModel('Poliza' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	
	////////////////////////////////////////////////////*Vendedor*//////////////////////////////////////////////////////////////
	public function usVendedorList(){
		$user = JFactory::getUser();
		if( $user->id > 0  &&  $user->tipo == 'U' ) {
			$document =& JFactory::getDocument();
			$viewName	= "usVendedorList";
			$model = $this->getModel('Vendedor' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usVendedorForm(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usVendedorForm";
			$model = $this->getModel('Vendedor' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usVendedorSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usVendedorSave";
			$model = $this->getModel('Vendedor' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usVendedorDelete(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usVendedorDelete";
			$model = $this->getModel('Vendedor' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}

	
	
	////////////////////////////////////////////////////*Tarea*//////////////////////////////////////////////////////////////
	public function usTareaList(){
		$user = JFactory::getUser();
		if( $user->id > 0  &&  $user->tipo == 'U' ) {
			$document =& JFactory::getDocument();
			$viewName	= "usTareaList";
			$model = $this->getModel('Tarea' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usTareaForm(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTareaForm";
			$model = $this->getModel('Tarea' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usTareaSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTareaSave";
			$model = $this->getModel('Tarea' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usTareaDelete(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTareaDelete";
			$model = $this->getModel('Tarea' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}


	public function usTareaHistorial(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTareaHistorial";
			$model = $this->getModel('Tarea' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}

	public function usTareaHistorialSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTareaHistorialSave";
			$model = $this->getModel('Tarea' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}


	///////////////////////////////////////////////////*Tecnico*//////////////////////////////////////////////////////////////

	public function usTecnicoList(){
		$user = JFactory::getUser();
		if( $user->id > 0  &&  $user->tipo == 'U' ) {
			$document =& JFactory::getDocument();
			$viewName	= "usTecnicoList";
			$model = $this->getModel('Tecnico' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();	
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usTecnicoForm(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTecnicoForm";
			$model = $this->getModel('Tecnico' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usTecnicoSave(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTecnicoSave";
			$model = $this->getModel('Tecnico' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	public function usTecnicoDelete(){
		$user = JFactory::getUser();
		if($user->id > 0  && $user->tipo=='U'){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "usTecnicoDelete";
			$model = $this->getModel('Tecnico' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}


	
    /*************************************************************** Opciones web reports **********************************************************/
	
	
	/**
	 * Table example
	 */
	public function tableExample(){	
		$user = JFactory::getUser();
		if($user->id > 0 ){
			$this->checkUserDashboard();
		}
		else{
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "tableExample";
			//$model = $this->getModel('ZMiUne' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			//$view->setModel($model , true);
			$view->display();	
		}
	}
	
	/**
	*Form example
	*/
	public function formExample(){	
		$user = JFactory::getUser();
		if($user->id > 0 ){
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "formExample";
			//$model = $this->getModel('ZMiUne' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			//$view->setModel($model , true);
			$view->display();
		}
		else{
			$this->userFormLogin();
		}
	}
	
	/**
	*Form example
	*/
	public function formWizard(){	
		$user = JFactory::getUser();
		if($user->id > 0 ){
			$this->checkUserDashboard();
		}
		else{
			//$this->userFormLogin();	
			$document =& JFactory::getDocument();
			$viewName	= "formWizard";
			//$model = $this->getModel('ZMiUne' , 'Model');
			$viewType	= $document->getType();
			$view = &$this->getView($viewName, $viewType , 'View');
			//$view->setModel($model , true);
			$view->display();
		}
	}
	
	
	
	/**
	* Include all needed libraries
	*/
	public function includeLibraries(){
		/*JHTML::script( 'custom/prototype/prototype-1.6.0.3.js' );
	    JHTML::script( 'custom/jquery/jquery-1.4.2.min.js' );
		JHTML::script( 'custom/jquery/jquery-ui-1.8.2.custom.min.js' );
		*/
		
		
		//JHTML::stylesheet( 'custom/tablehelper.css' );
		
		//Uploadify
		//JHTML::script( 'custom/uploadify/swfobject.js' );
		//JHTML::script( 'custom/uploadify/jquery.uploadify.v2.1.0.min.js' );

		
        $document =& JFactory::getDocument();
		
		$document->addStyleSheet( JURI::root() . '/components/com_ztadmin/css/une/jquery-ui-1.8.custom.css' ); 
		
		//$document->addScript(JURI::root() . "media/system/js/custom/prototype/prototype-1.6.0.3.js");
		$document->addScript(JURI::root() . "media/system/js/custom/jquery/jquery-1.4.2.min.js");
		$document->addScript(JURI::root() . "media/system/js/custom/jquery/jquery-ui-1.8.2.custom.min.js");
		
		
		
		//$document->addScript(JURI::root() . "components/com_ztelecliente/libraries/jstree/jquery.jstree.js");
		
		//Uploadify
		//$document->addScript( JURI::root() . "media/system/js/custom/uploadify/swfobject.js");
		//$document->addScript( JURI::root() . "media/system/js/custom/uploadify/jquery.uploadify.v2.1.0.min.js");
		
		//Tooltip
		//$document->addScript(JURI::root() . "media/system/js/custom/tooltip/jquery.tools.min.js");
		
        $document->addCustomTag( '<script type="text/javascript">jQuery.noConflict();</script>' );		
	}
	

}
	
	
	



