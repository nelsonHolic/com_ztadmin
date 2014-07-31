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
		
class ModelCaracteristicas extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getTipos($id){
		$db = Configuration::getTiendaDB();
		$tbTipos = $db->nameQuote('#__ztipo_caracteristicas');
		//$tbCaracteristicas = $db->nameQuote('#__zcaracteristicas');
		
		$query = "SELECT 
						*
				  FROM 
						$tbTipos as t
						";
						
		//$query = sprintf( $query, $id );			
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		foreach( $result as $tipo){
			$tipo->data = $this->getCaracteristicas($tipo->id, $id);
		}
		return $result;
	}
	
	
	function getCaracteristicas($tipo, $producto){
		$db = Configuration::getTiendaDB();
		$tbCaracteristicas = $db->nameQuote('#__zcaracteristicas');
		$tbProd = $db->nameQuote('#__zproducto_caracteristica');
		$query = "SELECT 
						c.id, c.descripcion, p.producto, p.valor
				  FROM 
						$tbCaracteristicas as c
				  LEFT JOIN
						$tbProd as p ON c.id = p.caracteristica AND p.producto = %s 
				  WHERE 
						c.tipo = %s
				  ORDER BY
						tipo, descripcion
						";
		
		$query = sprintf( $query, $producto, $tipo );			
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function guardarCaracteristicasProducto($data, $producto){
		foreach($data as $id => $valor ){	
			if( strpos($id , "v_") !== false ){
				$carac =  explode("_", $id);
				$idCarac = $carac[1];
				//guardar Caracteristica
				$this->guardarCaracteristica($idCarac, $producto, $valor);
			}
		}
		return JText::_('M_OK'). JText::_('PRODUCTOS_CARACTERISTICAS_MSG_GUARDAR');
	}
	
	function guardarCaracteristica($carac, $producto, $valor){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('ProductoCaracteristica', 'Table');
		
		$row->producto = $producto;
		$row->caracteristica = $carac;
		
		$row->load( array('caracteristica' => $carac, 'producto' => $producto));
		$row->producto = $producto;
		$row->caracteristica = $carac;
		$row->valor = $valor;
		print_r($row);
		
		if($row->store()){
			return true;
		}
		else{
			return false;
		}
	}
	//function existe
	

}










