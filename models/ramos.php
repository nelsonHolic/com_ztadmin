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
		
class ModelRamos extends JModel{

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
	function listarRamos($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRamos = $db->nameQuote('#__zramos');		
		
		
		$query = "SELECT 
						*
				  FROM 
						$tbRamos as ramos
				  WHERE 
						lower(descripcion) like lower('%s') AND
						activo = 1 
				  ORDER BY
						descripcion
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	
	/**
	* Contar aseguradoras
	*/
	function contarRamos($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRamos = $db->nameQuote('#__zramos');		
		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbRamos as ramos
				  WHERE 
						lower(descripcion) like lower('%s') AND
						activo = 1 
				  ORDER BY
						descripcion
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	

	
	/**
	* Obtiene la aseguradora a traves del id
	*/
	function getRamo($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		$row = &JTable::getInstance('Ramos', 'Table');
		$row->id = $id;
		$row->load();
		return $row;
	}
	
	/**
	* Guarda la aseguradora
	*/
	function guardarRamo(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Ramos', 'Table');
		$user = JFactory::getUser();
		
		if($row->bind(JRequest::get('post'))){
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
	* Elimina la aseguradora
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







