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
		
class ModelCrPlanEquipo extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	//Planes Equipos ******************************************************************************
	function listar($filtro, $producto, $plan, $inicio, $registros){
	
		$db = Configuration::getTiendaDB();
		$tbProductoPlan = $db->nameQuote('#__zproducto_plan');
		$tbPlanes = $db->nameQuote('#__zplanes');
		$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
	
		$flTexto 	= ($filtro != "") ? " AND ( pl.descripcion LIKE '%$filtro%' OR pr.product_s_desc LIKE '%$filtro%' ) " : "";
		$flPlan 	= ($plan != "") ? " AND pl.id = '$plan' " : "";
		$flProducto = ($producto != "") ? " AND pr.virtuemart_product_id = '$producto' " : "";
		
		$query = "SELECT 
						pp.*, pl.descripcion, pr.product_s_desc
				  FROM 
						$tbProductoPlan as pp,
						$tbPlanes as pl,
						$tbProductos as pr
				  WHERE
						pp.plan = pl.id AND
						pp.producto = pr.virtuemart_product_id 
						$flTexto
						$flPlan
						$flProducto
				  ORDER BY
						pl.descripcion, pr.product_s_desc
						";
		//$query = sprintf( $query );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro, $producto, $plan){
	
		$db = Configuration::getTiendaDB();
		$tbProductoPlan = $db->nameQuote('#__zproducto_plan');
		$tbPlanes = $db->nameQuote('#__zplanes');
		$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
		
		$flTexto 	= ($filtro != "") ? " AND ( pl.descripcion LIKE '%$filtro%' OR pr.product_s_desc LIKE '%$filtro%' ) " : "";
		$flPlan 	= ($plan != "") ? " AND pl.id = '$plan' " : "";
		$flProducto = ($producto != "") ? " AND pr.virtuemart_product_id = '$producto' " : "";
		
		$query = "SELECT 
						count(*)
				 FROM 
						$tbProductoPlan as pp,
						$tbPlanes as pl,
						$tbProductos as pr
				  WHERE
						pp.plan = pl.id AND
						pp.producto = pr.virtuemart_product_id 
						$flTexto
						$flPlan
						$flProducto
						";
		
		//$query = sprintf( $query );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getInfoPlanEquipo($id){
		$db = Configuration::getTiendaDB();
		$tbProductoPlan = $db->nameQuote('#__zproducto_plan');
	
		$query = "SELECT 
						*
				  FROM 
						$tbProductoPlan as pp
				  WHERE 
						pp.id = %s
						";
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}

	function guardarPlanEquipo($data){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		
		$row =& JTable::getInstance('CrPlanEquipo', 'Table');
		if($row->bind($data)){
			if($row->store()){
				return JText::_('M_OK'). JText::_('PLANESEQUIPOS_MSG_GUARDAR');
			}
		}
		return JText::_('M_ERROR'). JText::_('PROCESO_ERROR');
	}
	
	public function eliminar( $id ){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =&JTable::getInstance('CrPlanEquipo', 'Table');
		
		$row->id = $id;
		$result = $row->delete();

		if($result){
			return JText::_('M_OK'). JText::_('CR_PLAN_EQUIPO_ELIMINAR_MSG');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	
	}
	
	
}










