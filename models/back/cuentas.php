<?php


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 *
 * @author      aquintero
 * @package		Etp
 * @subpackage	correos
 * @since 1.6
 */


		
class ModelCuentas extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getCuentas(){
		$db 		= & JFactory::getDBO();
		$tbCuentas	= $db->nameQuote('#__zcuentas');
		
		$query = "
					SELECT 
						*
					FROM 
						$tbCuentas as cuentas
					ORDER BY 
						correo
						";
						
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
		
	}
	
	
	
	
	
	
	
	
}









