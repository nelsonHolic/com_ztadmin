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


		
class ModelCategoriasProductos extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getCategoriaProducto(){
		$db = Configuration::getTiendaDB();

		$tbCategories = $db->nameQuote('#__virtuemart_categories_es_es');
		$query = "SELECT 
						* 
				  FROM 
						$tbCategories 
				  WHERE 
						virtuemart_category_id = $id";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		return $result[0];
	}
	
}










