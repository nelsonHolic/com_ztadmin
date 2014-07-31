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
		
class ModelSede extends JModel{
  
  /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	function listarConsumos( $tipo, $fechaInicio, $fechaFinal){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		
		$tbSedeUsuario = $db->nameQuote('#__zsede_usuarios');
		$tbSedeLineas = $db->nameQuote('#__zsede_lineas');
		$tbConsumos = $db->nameQuote('#__zlineas_consumos');
		
		$whereTipo  =  ($tipo == "E") ?  " AND  sl.linea = c.destino  " : " AND sl.linea = c.origen ";
		$whereFechaInicio = ($fechaInicio != "") ? " AND c.fecha >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal != "") ?  " AND c.fecha <= '$fechaFinal'" : "";
		
		
		//$whereEstado = ($estado > 0) ?  " AND estado = $estado " : "";
		
		$query = "SELECT 
						c.*
				  FROM 
						$tbSedeUsuario su,
						$tbSedeLineas sl,
						$tbConsumos c
				  WHERE 
						 su.usuario = %s AND
						 su.sede = sl.sede 
						 $whereTipo
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						c.id  
						";
						
		$query = sprintf( $query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
		
}







