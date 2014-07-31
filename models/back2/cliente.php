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
		
class ModelCliente extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function getCliente($id){
	
		$db = Configuration::getTiendaDB();
		$tbUsuarios = $db->nameQuote('#__users');
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	function getClientes(){
		$db = Configuration::getTiendaDB();
		$tbUsuarios = $db->nameQuote('#__users');
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios
				  WHERE
					    id > 42
			      ORDER BY 
						name
				  ";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function totalCompras($id){
		$db = Configuration::getTiendaDB();
		$tbPedidos = $db->nameQuote('#__virtuemart_orders');
		
		$query = "SELECT 
						sum(order_total)
				  FROM 
						$tbPedidos
				  WHERE 
						virtuemart_user_id = %s AND
						(
							order_status = '%s' OR
							order_status = '%s'
						)
						";
		$query = sprintf( $query, $id, Configuration::PEDIDO_COMPLETO, Configuration::PEDIDO_ENVIADO );
		$db->setQuery($query);
	    $result = $db->loadResult();
		$result = ($result != "") ? $result : 0;
		return $result;
	}
	
	function contarPedidos($id){
		$db = Configuration::getTiendaDB();
		$tbPedidos = $db->nameQuote('#__virtuemart_orders');
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbPedidos
				  WHERE 
						virtuemart_user_id = %s AND
						(
							order_status = '%s' OR
							order_status = '%s'
						)
						";
		$query = sprintf( $query, $id, Configuration::PEDIDO_COMPLETO, Configuration::PEDIDO_ENVIADO );
		$db->setQuery($query);
	    $result = $db->loadResult();
		$result = ($result != "") ? $result : 0;
		return $result;
	}
	
	function comprasMes($id, $ano, $mes){
		$db = Configuration::getTiendaDB();
		$tbPedidos = $db->nameQuote('#__virtuemart_orders');
		
		$ano = ($mes == 12) ? $ano + 1 : $ano;
		$sigMes = ($mes == 12) ? 1 : $mes + 1;  
		$query = "SELECT 
						sum(order_total)
				  FROM 
						$tbPedidos
				  WHERE 
						virtuemart_user_id = %s AND
						created_on >= '%s-%s-01' AND
						created_on < '%s-%s-01' AND
						(
							order_status = '%s' OR
							order_status = '%s'
						)
						";
		$query = sprintf( $query, $id, $ano, $mes, $ano, $sigMes, Configuration::PEDIDO_COMPLETO, Configuration::PEDIDO_ENVIADO );
		$db->setQuery($query);
	    $result = $db->loadResult();
		$result = ($result != "") ? $result : 0;
		return $result;
	}
	

}










