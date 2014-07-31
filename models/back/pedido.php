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
		
class ModelPedido extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	//Pedidos ******************************************************************************
	function listar($filtro, $estado, $cliente, $fechaIni, $fechaFin, $inicio, $registros){
	
		$db = Configuration::getTiendaDB();
		$tbOrdenes = $db->nameQuote('#__virtuemart_orders');
		$tbOrdenesEstados = $db->nameQuote('#__virtuemart_orderstates');
		$tbOrdenesUsuarios = $db->nameQuote('#__users');
		$filtroEstado = ($estado != "") ? " AND order_status_code = '$estado' " : "";
		$filtroCliente = ($cliente != "") ? " AND o1.created_by = $cliente " : "";
		$filtroFechaIni = ($fechaIni != "") ? " AND o1.created_on >= '$fechaIni' " : "";
		$filtroFechaFin = ($fechaFin != "") ? " AND o1.created_on <= '$fechaFin' " : "";
		
		$query = "SELECT 
						o1.*, o2.order_status_name, o2.order_status_code, o3.name, o3.username, o3.id as userId
				  FROM 
						$tbOrdenes as o1,
						$tbOrdenesEstados as o2,
						$tbOrdenesUsuarios as o3
				  WHERE
						o1.order_status = o2.order_status_code AND
						o1.created_by = o3.id AND
						order_number like '%s' 
						$filtroEstado
						$filtroCliente
						$filtroFechaIni
						$filtroFechaFin
				  ORDER BY
						virtuemart_order_id DESC
						";
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro, $estado, $cliente, $fechaIni, $fechaFin){
	
		$db = Configuration::getTiendaDB();
		$tbOrdenes = $db->nameQuote('#__virtuemart_orders');
		$tbOrdenesEstados = $db->nameQuote('#__virtuemart_orderstates');
		$tbOrdenesUsuarios = $db->nameQuote('#__users');
		$filtroEstado = ($estado != "") ? " AND order_status = '$estado' " : "";
		$filtroCliente = ($cliente != "") ? " AND o1.created_by = $cliente " : "";
		$filtroFechaIni = ($fechaIni != "") ? " AND o1.created_on >= '$fechaIni' " : "";
		$filtroFechaFin = ($fechaFin != "") ? " AND o1.created_on <= '$fechaFin' " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbOrdenes as o1,
						$tbOrdenesEstados as o2,
						$tbOrdenesUsuarios as o3
				  WHERE
						o1.order_status = o2.order_status_code AND
						o1.created_by = o3.id AND
						order_number like '%s' 
						$filtroEstado
						$filtroCliente
						$filtroFechaIni
						$filtroFechaFin
						";
		
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	

	function getPedido($idPedido){
		$db = Configuration::getTiendaDB();
		$tbOrdenes = $db->nameQuote('#__virtuemart_orders');
		$tbOrdenesEstados = $db->nameQuote('#__virtuemart_orderstates');
		$tbOrdenesUsuario = $db->nameQuote('#__virtuemart_order_userinfos');
		$tbPaises = $db->nameQuote('#__virtuemart_countries');
		
		$query = "SELECT 
						o1.*, o2.order_status_code, o2.order_status_name, o3.*, o4.country_name
				  FROM 
						$tbOrdenes as o1
				  LEFT JOIN $tbOrdenesEstados as o2 ON o1.order_status = o2.order_status_code
				  LEFT JOIN $tbOrdenesUsuario as o3 ON o1.virtuemart_order_id = o3.virtuemart_order_id
				  LEFT JOIN $tbPaises as o4 ON  o3.virtuemart_country_id  = o4.virtuemart_country_id 
				  WHERE
						o1.virtuemart_order_id = %s 
						";
		$query = sprintf( $query, $idPedido);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
		$datosEnvio = $this->getPedidoDireccionEnvio($result->virtuemart_order_id); 
		$result->datosEnvio = $datosEnvio;
		return $result;
	}
	
	function productosPedido($idPedido){
		$db = Configuration::getTiendaDB();
		$tbProductosOrdenes = $db->nameQuote('#__virtuemart_order_items');
		
		$query = "SELECT 
						*
				  FROM 
						$tbProductosOrdenes as o1
				  WHERE
						o1.virtuemart_order_id = %s 
						";
		$query = sprintf( $query, $idPedido);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getPedidoDireccionEnvio($idPedido){
		$db = Configuration::getTiendaDB();
		$tbPedidoUser = $db->nameQuote('#__virtuemart_order_userinfos');
		$tbPaises = $db->nameQuote('#__virtuemart_countries');
		
		$query = "SELECT 
						o1.*, o2.country_name
				  FROM 
						$tbPedidoUser as o1
				  LEFT JOIN $tbPaises as o2 ON  o1.virtuemart_country_id  = o2.virtuemart_country_id 
				  WHERE
						o1.address_type = 'ST' AND
						o1.virtuemart_order_id = %s 
						";
		$query = sprintf( $query, $idPedido);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		if( isset($result[0]) ){
			$result = $result[0];
		}
		else{
			$query = "SELECT 
						o1.*, o2.country_name
					  FROM 
							$tbPedidoUser as o1
					  LEFT JOIN $tbPaises as o2 ON  o1.virtuemart_country_id  = o2.virtuemart_country_id 
					  WHERE
							o1.address_type = 'BT' AND
							o1.virtuemart_order_id = %s 
						";
			$query = sprintf( $query, $idPedido);
			$db->setQuery($query);
			$result = $db->loadObjectList();
			$result = $result[0];
		}
		return $result;
	}
	
	
	function getEstadosPedido(){
		$db = Configuration::getTiendaDB();
		$tbOrdenesEstados = $db->nameQuote('#__virtuemart_orderstates');
		$query = "SELECT 
						*
				  FROM 
						$tbOrdenesEstados as o1
				  WHERE 
						published = 1
				  ORDER BY 
					virtuemart_orderstate_id
						";
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;	
	}
	
	function actualizarEstado($id, $estado){
		$db = Configuration::getTiendaDB();
		$tbPedidos 			= $db->nameQuote('#__virtuemart_orders');		
		$tbPedidosHistorial = $db->nameQuote('#__virtuemart_order_histories');		
		$user = JFactory::getUser();
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');

		$query = "INSERT INTO $tbPedidosHistorial 
					(
						virtuemart_order_id, order_status_code, customer_notified, comments, 
						published, created_on, created_by, modified_on,
						modified_by
					)
					VALUES
					(
						%s, '%s', 0, '',
					     1, '%s', %s, '%s',
						 %s
					)
					";
		$query = sprintf( $query, $id, $estado, $fechaHoy, $user->id, $fechaHoy, $user->id );
		$db->setQuery($query);
		echo $query;
		$result = $db->query();
		
		if($result){
			$query = "
				  UPDATE
						$tbPedidos
				  SET 
						order_status = '%s',
						modified_by = %s,
						modified_on = '%s'
				  WHERE 
						virtuemart_order_id = %s
						";
			$query = sprintf( $query, $estado, $user->id, $fechaHoy, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			if($result){
				return JText::_('M_OK'). JText::_('PEDIDOS_CAMBIO_ESTADO_OK');
			}
			else{
				return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
			}
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}
	
	
}










