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
 * @subpackage	ztelecliente
 * @since 1.6
 */


		
class ModelSedes extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	
	function getSedes($usuario){
		$db = & JFactory::getDBO();
		
		$tbIPCentrex 	= $db->nameQuote('#__zipcentrex');
		$tbSedes 		= $db->nameQuote('#__zsedes');
		
		$query = "
					SELECT 
						*
					FROM 
						$tbIPCentrex as ipcentrex,
						$tbSedes as sedes
					WHERE
						ipcentrex.usuario = $usuario AND
						sedes.ipcentrex = ipcentrex.id
						";
						
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	
	
	
}









