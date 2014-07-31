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
 *
 * @author      aquintero
 * @package		Joomla
 * @subpackage	ztelecliente
 * @since 1.6
 */


		
class ModelUsuarios extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	* Lista de usuarios
	*/
	function listar($filtro, $inicio, $registros){	
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbUsers = $db->nameQuote('#__users');		
		$query = "SELECT 
						*
				  FROM 
						$tbUsers as users
				  WHERE 
						username like '%s'
				  ORDER BY
						username 
						";
		$query = sprintf( $query, "%". $filtro . "%");
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro){
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbUsers = $db->nameQuote('#__users');
		
		$query = "SELECT 
						count(*)
				   FROM 
						$tbUsers as users
				  WHERE 
						username like '%s'
						";
		
		$query = sprintf( $query, "%".$filtro."%" );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/**
	* Lista de usuarios
	*/
	function listarTodos($filtro){	
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbUsers = $db->nameQuote('#__users');		
		$query = "SELECT 
						id, name, username, email, 
						registerDate as fecha_registro, 
						lastvisitDate as ultima_visita,
						cedula, phone, mobile, points,
						points_exact, points_result,
						points_norder
						
				  FROM 
						$tbUsers as users
				  WHERE 
						username like '%s'
				  ORDER BY
						username
						";
		$query = sprintf( $query, "%". $filtro . "%");
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	
}









