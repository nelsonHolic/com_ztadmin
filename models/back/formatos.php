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


		
class ModelFormatos extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getFormatos(){
		$db 		= & JFactory::getDBO();
		$tbFormatos	= $db->nameQuote('#__zformatos');
		
		$query = "
					SELECT 
						*
					FROM 
						$tbFormatos as formatos
					ORDER BY 
						descripcion
						";
						
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
		
	}
	
	
	
	
	
	
	
	
}









