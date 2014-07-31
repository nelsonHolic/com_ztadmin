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
		
class ModelRamo extends JModel{

	//const TAM_MSG       = 160;
	
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	* Lista de ramos
	*/
	function listarRamos($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRamos = $db->nameQuote('#__zramos');		
		
		$whereUsuario = ($user->id > 0) ?  " usuario = {$user->id} " : "";
		
		$query = "SELECT 
						*
				  FROM 
						$tbRamos as ramos
				  WHERE 
						lower(descripcion) like lower('%s') AND
						activo = 1 AND
						$whereUsuario
				  ORDER BY
						descripcion
						";

		$query = sprintf( $query, "%" . $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	
	/**
	* Contar ramos
	*/
	function contarRamos($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRamos = $db->nameQuote('#__zramos');		
		
		$whereUsuario = ($user->id > 0) ?  "  usuario = {$user->id} " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbRamos as ramos
				  WHERE 
						lower(descripcion) like lower('%s') AND
						activo = 1 AND
						$whereUsuario
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}

	
	/**
	* Obtiene el ramo a traves del id
	*/
	function getRamo($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		$row = &JTable::getInstance('Ramos', 'Table');
		$row->id = $id;
		$row->load();
		return $row;
	}
	
	/**
	* Guarda el ramo
	*/
	function guardarRamo(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Ramos', 'Table');
		$user = JFactory::getUser();
		
		if($row->bind(JRequest::get('post'))){
			$row->usuario = $user->id ;
			$row->activo = 1 ;
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('US_GUARDAR_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('US_GUARDAR_ERROR');
			}
		
		}
	}
	
	/**
	* Elimina el ramo
	*/
	function eliminarRamo($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Ramos', 'Table');
		
		$row->id     = $id;
		$row->activo = 0;
		if( $row->store() ){
			return JText::_('M_OK') . sprintf( JText::_('US_ELIMINAR_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('US_ELIMINAR_ERROR');
		}
	}
	
}







