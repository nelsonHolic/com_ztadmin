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
		
class ModelTecnico extends JModel{

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
	function listarTecnicos($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbTecnico = $db->nameQuote('#__users');
		$usuarioID = $user->id;
		
		$query = "SELECT 
						*
				  FROM 
						$tbTecnico as Tecnico
				  WHERE 
						(lower(name) like lower('%s') OR
						lower(username) like lower('%s')) AND 
						parent = $usuarioID AND
						block = 0  
				  ORDER BY
						name
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" , "%" . $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	
	/**
	* Contar aseguradoras
	*/
	function contarTecnicos($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbTecnico = $db->nameQuote('#__users');		
		

		$usuarioID = $user->id;
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbTecnico as Tecnico
				  WHERE 
						(lower(name) like lower('%s') OR
						lower(username) like lower('%s')) AND 
						parent = $usuarioID AND
						block = 0  
						";
						
		$query = sprintf( $query, "%" . $filtro . "%" , "%" . $filtro . "%" );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		return $result;
	}
	

	
	/**
	* Obtiene la aseguradora a traves del id
	*/
	function getTecnico($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		$row = &JTable::getInstance('Users', 'Table');
		$row->id = $id;
		$row->load();
		return $row;
	}
	
	/**
	* Guarda la aseguradora
	*/
	function guardarTecnico(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Users', 'Table');
		$user = JFactory::getUser();
		
		if($row->bind(JRequest::get('post'))){
			$row->parent = $user->id;
			$row->tipo = "T";			
			$row->registerDate = date("Y-m-d");
			$row->lastVisitDate = date("Y-m-d");
			
			$clave1 = JRequest::getVar('clave1');
			$clave2 = JRequest::getVar('clave2');
			
			if($clave1 == $clave2){
				$row->password = md5($clave1);

				if($row->store()){
					return JText::_('M_OK') . sprintf( JText::_('US_GUARDAR_OK') , $row->id );
				}
				else{
					return JText::_('M_ERROR'). JText::_('US_GUARDAR_ERROR');
				}
			}else{
					return JText::_('M_ERROR'). JText::_('US_GUARDAR_ERROR');
				}
		
		}
	}
	
	/**
	* Elimina la aseguradora
	*/
	function eliminarTarea($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Users', 'Table');
		
		$row->id     = $id;
		$row->block = 1;
		if( $row->store() ){
			return JText::_('M_OK') . sprintf( JText::_('US_ELIMINAR_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('US_ELIMINAR_ERROR');
		}
	}

}
