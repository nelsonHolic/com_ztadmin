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
		
class ModelSlideshowIntermedio extends JModel{

	const MODULO_ID = 485;
	
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getInfo(){
		$db = Configuration::getTiendaDB();

		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = %s
						";
		$query = sprintf( $query, self::MODULO_ID );
		$db->setQuery($query);
	    $result = $db->loadObject();
		$params = json_decode($result->params);
		
		//print_r($params->vm_categories);
		//print_r($result);
		
		$result2->titulo = $result->title;
		$result2->categorias = $params->vm_categories;
		$result2->mostrarAgotados = $params->rs_out_of_stock;
	
		return $result2;
		
	}
	
	function guardar($titulo, $categorias, $mostrarAgotados){
		$db = Configuration::getTiendaDB();
		
		//Valida los datos
		if($titulo == ""){
			return JText::_('M_ERROR') . JText::_('SLIDESHOW_ERROR_CAMPOS_OBLIGATORIOS');
		}
		
		if( count($categorias) == 0 ) {
			return JText::_('M_ERROR') . JText::_('SLIDESHOW_ERROR_CAMPOS_OBLIGATORIOS');
		}
		

		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = %s
						";
		$query = sprintf( $query, self::MODULO_ID );
		$db->setQuery($query);
	    $result = $db->loadObject();
		$params = json_decode($result->params);
		$params->vm_categories = $categorias;
		$params->rs_out_of_stock = $mostrarAgotados;
		$params = json_encode($params);
		//print_r($params);
		//exit;
		
		//Actualiza las categorias
		//$titulo = $db->quote($titulo);
		$query = "
			  UPDATE
					$tbModules 
			  SET 
				    title = '%s',
					params = '%s'
			  WHERE 
					published = 1 AND 
					id = %s
					";
		$query = sprintf( $query, $titulo, $params, self::MODULO_ID  );
		$db->setQuery($query);
		$result = $db->query();
		return JText::_('M_OK') . JText::_('SLIDESHOW_GUARDAR_OK');
	}
	
	
	function getCategorias(){
		$db = Configuration::getTiendaDB();

		$tbCategorias = $db->nameQuote('#__virtuemart_categories_es_es');
		$query = "SELECT 
						* 
				  FROM 
						$tbCategorias
						";
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
		
	}
	
	
}
	











