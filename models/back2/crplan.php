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
		
class ModelCrPlan extends JModel{

    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function listar($filtro, $inicio, $registros){	
		$db = Configuration::getTiendaDB();

		$tbPlanes = $db->nameQuote('#__zplanes');
		
		$query = "SELECT 
						*
				  FROM 
						$tbPlanes as plan
				  WHERE 
						descripcion like '%s'
				  ORDER BY
						descripcion
						";
		$query = sprintf( $query, "%".$filtro."%");
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro){
		$db = Configuration::getTiendaDB();
		$tbPlanes = $db->nameQuote('#__zplanes');
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbPlanes as plan
				  WHERE 
						descripcion like '%s'
						";
		
		$query = sprintf( $query, "%".$filtro."%" );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function guardarPlan($id, $descripcion, $cargo, $tipo, $plan,  $data){
		
		//Guarda plan
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('CrPlanes', 'Table');
		print_r($row);
		
		$row->id = $id;
		$row->descripcion = $descripcion;
		$row->cargo_fijo = $cargo;
		$row->tipo = $tipo;
		$row->plan = $plan;
			
		echo "guardando";
		print_r($row);			
		
		if($row->store()){
			$this->guardarCaracteristicas($row->id, $data);
			return JText::_('M_OK'). JText::_('CONTACTOS_MSG_GUARDAR');
		}
		
		return JText::_('M_ERROR'). JText::_('PROCESO_ERROR');
	}
	
	function guardarCaracteristicas($id, $data){
		echo "<br/>id= " . $id;
		print_r($data);
		
		$this->borrarCaracteristicasPlan($id);
		
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		
		foreach($data as $indice => $valor){
			if(strpos($indice, "car_") !== false ){
				$carac = explode("car_", $indice);
				
				$row =& JTable::getInstance('CrPlanCaracteristica', 'Table');
				$row->plan = $id;
				$row->caracteristica = $carac[1];
				$row->valor = $valor;
				
				$row->store();
					
			}
		}
	}
	
	
	function getInfoPlan($id){
		$db = Configuration::getTiendaDB();
		$tbPlanes = $db->nameQuote('#__zplanes');
	
		$query = "SELECT 
						*
				  FROM 
						$tbPlanes as plan
				  WHERE 
						plan.id = %s
						";
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadObject();
		if(is_object($result)){
			$result->valores = $this->getCaracteristicasPlan($result->id);
		}
		return $result;
	}
	
	function getCaracteristicasPlan($id){
		$db = Configuration::getTiendaDB();
		$tbPlanValores = $db->nameQuote('#__zplan_valores');
		$query = "SELECT 
						p.*
				  FROM 
						$tbPlanValores as p
				  WHERE 
						p.plan = %s
				  ORDER BY caracteristica
						";
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function borrarCaracteristicasPlan($id){
		$db = Configuration::getTiendaDB();
		$tbPlanValores = $db->nameQuote('#__zplan_valores');
		$query = "DELETE
				  FROM 
						$tbPlanValores 
				  WHERE 
						plan = %s
						";
		$query = sprintf( $query, $id);
		echo $query;
		$db->setQuery($query);
	    $result = $db->query();
		return $result;
	}
	
	
	public function eliminar( $id ){
	
		//Verificar si el plan tiene productos asociados
		$productos = $this->productosEnPlan($id);
		if( $productos == 0 ){
			JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
			$row =&JTable::getInstance('CrPlanes', 'Table');
			
			$row->id = $id;
			$result = $row->delete();

			if($result){
				return JText::_('M_OK'). JText::_('CR_PLANES_ELIMINAR_MSG');
			}
			else{
				return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
			}
		}
		else{
			return JText::_('M_ERROR') .  JText::_('CR_PLAN_PRODUCTOS_ASOCIADOS_ERROR');
		}
	}
	
	public function productosEnPlan($id){
		$db = Configuration::getTiendaDB();
		$tbPrPl = $db->nameQuote('#__zproducto_plan');
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbPrPl as prpl
				  WHERE 
						plan = %s
						";
		
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	

}










