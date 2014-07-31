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
 * Mensaje
 *
 * @author      aquintero
 * @package		Joomla
 * @since 1.6
 */
		
class ModelTarea extends JModel{

	//const TAM_MSG       = 160;
	
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	* Lista de aseguradoras
	*/
	function listarTareas($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbTarea = $db->nameQuote('#__ztareas');	
		$tbUser = $db->nameQuote('#__users');		
		
		$usuarioID = $user->id;
		
		$query = "SELECT 
						*, (SELECT name from $tbUser as user where user.id = usuario_asignado )  as usuario_asignado 
				  FROM 
						$tbTarea as Tarea
				  WHERE 
						(lower(descripcion) like lower('%s') OR
						lower(observaciones) like lower('%s')) AND 
						usuario = $usuarioID 
				  ORDER BY
						descripcion
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" , "%" . $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	
	/**
	* Contar aseguradoras
	*/
	function contarTareas($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbTarea = $db->nameQuote('#__ztareas');		
		

		$usuarioID = $user->id;
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbTarea as Tarea
				  WHERE 
						(lower(descripcion) like lower('%s') OR
						lower(observaciones) like lower('%s')) AND 
						usuario = $usuarioID AND
						activo = 1  
				  ORDER BY
						descripcion
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" , "%" . $filtro . "%" );
		$db->setQuery($query );
	    $result = $db->loadObjectList();
		
		return $result;
	}
	

	
	/**
	* Obtiene la aseguradora a traves del id
	*/
	function getTarea($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		$row = &JTable::getInstance('Tareas', 'Table');
		$row->id = $id;
		$row->load();
		return $row;
	}
	
	/**
	* Guarda la aseguradora
	*/
	function guardarTarea(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Tareas', 'Table');
		$user = JFactory::getUser();
		
		if($row->bind(JRequest::get('post'))){
			$row->activo = 1 ;
			$row->usuario = $user->id;
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('US_GUARDAR_OK'.$row->fecha_creacion) , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('US_GUARDAR_ERROR');
			}
		
		}
	}
	
	/**
	* Elimina la aseguradora
	*/
	function eliminarTarea($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Tareas', 'Table');
		
		$row->id     = $id;
		$row->activo = 0;
		if( $row->store() ){
			return JText::_('M_OK') . sprintf( JText::_('US_ELIMINAR_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('US_ELIMINAR_ERROR');
		}
	}



	function listarHistorialTarea($filtro, $inicio, $registros, $id){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbHistorial = $db->nameQuote('#__ztareas_historial');	
		$tbUser = $db->nameQuote('#__users');		
		
		$tareaID = $id;
		
		$query = "SELECT 
						*, (SELECT name from $tbUser as user where user.id = usuario )  as usuario 
				  FROM 
						$tbHistorial as Historial
				  WHERE 
						lower(observacion) like lower('%s') AND
						tarea = $tareaID  
				  ORDER BY
						fecha
				  DESC
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" , "%" . $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		return $result;
	}



	function guardarHistorialTarea(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('TareaHistoriales', 'Table');
		$user = JFactory::getUser();
		
		if($row->bind(JRequest::get('post'))){
			$row->usuario = $user->id;
			$row->fecha = date("Y-m-d");
			$tarea = JRequest::getVar('id');
			$row->tarea = $tarea;

			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('US_GUARDAR_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('US_GUARDAR_ERROR');
			}
		
		}
	}
	
	/**
	* Contar aseguradoras
	*/
	function contarHistorialTarea($filtro, $id){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbTarea = $db->nameQuote('#__ztareas');		
		

		$usuarioID = $user->id;
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbTarea as Tarea
				  WHERE 
						(lower(descripcion) like lower('%s') OR
						lower(observaciones) like lower('%s')) AND 
						usuario = $usuarioID AND
						activo = 1  
				  ORDER BY
						descripcion
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" , "%" . $filtro . "%" );
		$db->setQuery($query );
	    $result = $db->loadObjectList();
		
		return $result;
	}
	

	


	function listarUsuarios(){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUser = $db->nameQuote('#__users');		
		$parent = $user->id;
		
		$query = "SELECT 
						*
				  FROM 
						$tbUser as User
				  WHERE 
						parent = $parent AND 
						block = 0 
				  ORDER BY
						name
						";
		$query = sprintf( $query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	
	/**
	* Contar Usuarios
	*/
	function contarUsuarios(){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUser = $db->nameQuote('#__users');		
		$parent = $user->id;
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbUser as User
				  WHERE 
						parent = $parent AND 
						block = 0 
				  ORDER BY
						name
						";
						
		$query = sprintf( $query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		return $result;
	}





	

}
