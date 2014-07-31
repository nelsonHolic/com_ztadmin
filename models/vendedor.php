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
		
class Modelvendedor extends JModel{

	//const TAM_MSG       = 160;
	
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	* Lista de vendedors
	*/
	function listarvendedores($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbvendedores = $db->nameQuote('#__zvendedores');		
		
		$whereUsuario = ($user->id > 0) ?  " usuario = {$user->id} " : "";
		
		$query = "SELECT 
						*
				  FROM 
						$tbvendedores as vendedores
				  WHERE 
						lower(nombre) like lower('%s') AND
						activo = 1 AND
						$whereUsuario
				  ORDER BY
						nombre
						";

		//print_r($query);
		//exit;
						
		$query = sprintf( $query, "%" . $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	
	/**
	* Contar vendedores
	*/
	function contarvendedores($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbvendedores = $db->nameQuote('#__zvendedores');		
		
		$whereUsuario = ($user->id > 0) ?  "  usuario = {$user->id} " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbvendedores as vendedores
				  WHERE 
						lower(nombre) like lower('%s') AND
						activo = 1 AND
						$whereUsuario
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}

	
	/**
	* Obtiene el vendedor a traves del id
	*/
	function getvendedor($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		$row = &JTable::getInstance('vendedores', 'Table');
		$row->id = $id;
		$row->load();
		return $row;
	}
	
	/**
	* Guarda el vendedor
	*/
	function guardarvendedor(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('vendedores', 'Table');
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
	* Elimina el vendedor
	*/
	function eliminarvendedor($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('vendedores', 'Table');
		
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







