  <?php
/**
 * User Model
 *
 * @version $Id:  
 * @author Andres Quintero
 * @package Joomla
 * @subpackage zschool
 * @license GNU/GPL
 *
 * Allows to manage user data
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

//require_once( JPATH_COMPONENT . DS .'models' . DS . 'zteam.php' );

/**
 * ZTelecliente
 *
 * @author      aquintero
 * @package		Joomla
 * @since 1.6
 */
		
class ModelAdministrador extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	//FAQ
	
	function listarFaq($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbFaq = $db->nameQuote('#__zfaq');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						id, pregunta, titulo, contenido
				  FROM 
						$tbFaq as f
				  WHERE 
						 f.pregunta like '%s' OR
						 f.titulo like '%s'  
				  ORDER BY
						f.id
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro);
		echo $query;
		$db->setQuery($query, $inicio, $registros);
		//print_r($db);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarFaq($filtro){	
	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbFaq = $db->nameQuote('#__zfaq');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						count(*)
				   FROM 
						$tbFaq as f
				  WHERE 
						 f.pregunta like '%s' OR
						 f.titulo like '%s'
				  ORDER BY
						codigo 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getFaq($id){
		$db = JFactory::getDBO();
		$tbFaq 	= $db->nameQuote('#__zfaq');		
	
		$query = "SELECT 
						*
				  FROM 
						$tbFaq as f
				  WHERE 
						
						f.id = %s
						";
						 
		$query = sprintf( $query, $id );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}

	function guardarFaq(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Faq', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			$contenido 	= JRequest::getVar('contenido', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$row->contenido = $contenido;
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_FAQ_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_FAQ_ERROR');
			}
		
		}
	}
	
	function eliminarFaq($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Faq', 'Table');
		
		$row->id = $id;
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('AD_ELIMINAR_FAQ_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_ELIMINAR_FAQ_ERROR');
		}
		
		
	}

	
	//Usuarios
	
	function listarUsuarios($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios as u
				  WHERE 
						u.parent = %s  AND
						(u.name like '%s' OR
						 u.username like '%s' OR
						 u.email like '%s')
				  ORDER BY
						u.username
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
		
		
		
	function contarUsuarios($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');
		$tbTroncales = $db->nameQuote('#__ztroncales');			
		
		$query = "SELECT
						count(*)
				  FROM 
						$tbUsuarios as u,
						$tbTroncales as t
				  WHERE 
						u.id = t.user AND
						(t.descripcion like '%s' OR
						 u.name like '%s' OR
						 u.username like '%s' OR
						 u.email like '%s')
				  ORDER BY
						u.id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function bloquearUsuario($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			$row->id = $id;
			$row->block = 1;
			
			if($row->store()){
				$data->user = $row->id;
				return JText::_('M_OK') . sprintf( JText::_('AD_BLOQUEAR_USUARIO_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_BLOQUEAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function desbloquearUsuario($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			$row->id = $id;
			$row->block = 0;
			
			if($row->store()){
				$data->user = $row->id;
				return JText::_('M_OK') . sprintf( JText::_('AD_DESBLOQUEAR_USUARIO_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_DESBLOQUEAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function getUsuarios(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios as u
				  WHERE
						u.parent =%s
				  ORDER BY
						u.username
						";
						
		$query = sprintf( $query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getPaquetesMensajes(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbPaquetes = $db->nameQuote('#__zmpaquetesmensajes');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbPaquetes as p
				  ORDER BY
						p.cantidad
						";
						
		$query = sprintf( $query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
		
	
	function getUsuario($id){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios as u
				  WHERE 
						u.id = %s 
						";
						
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	function asignarMensajes(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$user = JFactory::getUser();
		$row =& JTable::getInstance('User', 'Table');
		$cantidad = JRequest::getVar('cantidad');
		$id = JRequest::getVar('id');
		$row->id = $id;
		$row->load();
		$row->mensajes = $row->mensajes + $cantidad;
			
		if($row->store()){
			return JText::_('M_OK') . sprintf( JText::_('AD_ASIGNAR_MENSAJES_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_ASIGNAR_MENSAJES_ERROR');
		}
		
		
	}

	function cambiarClaveUsuario(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$user = JFactory::getUser();
		$row =& JTable::getInstance('User', 'Table');
		$clave1 = JRequest::getVar('clave1');
		$clave2 = JRequest::getVar('clave2');
		$id = JRequest::getVar('id');
		$row->id = $id;
		$row->load();
		
		if($clave1 == $clave2){
				$row->password = md5($clave1);
		}
			
		if($row->store()){
			return JText::_('M_OK') . sprintf( JText::_('AD_ASIGNAR_MENSAJES_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_ASIGNAR_MENSAJES_ERROR');
		}
		
		
	}

	
	
	function guardarUser(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$db 	= JFactory::getDBO();
		$user 	= JFactory::getUser();
		$row  	= &JTable::getInstance('User', 'Table');
		$clave1 = JRequest::getVar('clave1');
		$clave2 = JRequest::getVar('clave2');
		$tipo 	= JRequest::getVar('tipo');
		
		if($row->bind(JRequest::get('post'))){
			date_default_timezone_set('America/Bogota');
			$fechaHoy = date('Y-m-d H:i:s');
			$row->registerDate = $fechaHoy;
			
			if($clave1 == $clave2){
				if($clave1 != ""){
					$row->password = md5($clave1);
				}
			}
			
			$row->params = '{"admin_style":"","admin_language":"","language":"","editor":"","helpsite":"","timezone":""}';
			$row->parent = $user->id;
			
			if($row->store()){               
				$tbGrupo = $db->nameQuote('#__user_usergroup_map');
				$query = "INSERT INTO $tbGrupo(user_id, group_id) VALUES({$row->id}, 2 )";
				$db->setQuery($query);
				$result= $db->query();
				if($result){
					return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_USUARIO_OK') , $row->id );
				}
				
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_USUARIO_ERROR');
			}
		
		}
	}

	
	function getTiposUsuarios(){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__zmtiposusuario');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios u
				  ORDER BY
						u.id 
						";
		$query = sprintf($query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function eliminarUsuario($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		$row->id = $id;
		
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('AD_RETIRAR_USUARIO_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_RETIRAR_USUARIO_ERROR');
		}
	}
	
	
}







