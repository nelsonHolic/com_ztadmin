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
		
class ModelEslogan extends JModel{


    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getInfo(){
		$db = Configuration::getTiendaDB();
		$result = $this->obtenerParametrosPlantilla();
		return $result->params;
	}

	
	
	function guardar($eslogan){
		$db = Configuration::getTiendaDB();
		//Obtiene datos actuales
		$result = $this->obtenerParametrosPlantilla();
		
		//Guarda los nuevos datos
		$params = $result->params;
		$params->copyrights = $eslogan;	
		$params = json_encode($params);
	
		
		$tbTemplates = $db->nameQuote('#__template_styles');
		$query = "
			  UPDATE
					$tbTemplates 
			  SET 
					params = '%s'
			  WHERE 
					template = '%s'
					";
		$query = sprintf( $query, mysql_real_escape_string($params),  'gk_bikestore' );
		$db->setQuery($query);
		$result = $db->query();
		return JText::_('M_OK') . JText::_('ENLACESI_FORM_EDITAR_GUARDAR_OK');
		/*}
		else{
			return JText::_('M_ERROR') . JText::_('ENLACESI_FORM_EDITAR_GUARDAR_ERR');
		}*/
	}
	
	function obtenerParametrosPlantilla(){
		$db = Configuration::getTiendaDB();
		$tbTemplates = $db->nameQuote('#__template_styles');
		$query = "SELECT 
						* 
				  FROM 
						$tbTemplates
				  WHERE 
						template = 'gk_bikestore'
						";
		$db->setQuery($query);
	    $result = $db->loadObject();	
		$result->params = json_decode($result->params);
		return $result;
	}
	
	function crearEnlace($tipo, $datos){
		$enlace = "";
		
		if($tipo == "AR"){
			$enlace = "index.php?option=com_content&view=article&id=$datos";
		}
		else if($tipo == "CP"){
			$enlace = "index.php?option=com_virtuemart&view=categories&virtuemart_category_id=$datos";
		}
		else if($tipo == "PT"){
			$enlace = "option=com_virtuemart&view=virtuemart";
		}
		else if($tipo == "CA"){
			$enlace = "index.php?option=com_content&view=category&id=$datos";
		}
		else if($tipo == "BL"){
			$enlace = "index.php?option=com_content&view=category&layout=blog&id=$datos";
		}
		else if($tipo == "CO"){
			$enlace = "index.php?option=com_contact&view=contact&id=$datos";
		}
		else if($tipo == "EX"){
			$enlace = $datos;
		}
		
		return $enlace;
	}

	
}
	











