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


		
class ModelTienda extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function cargarConfiguracion(){
		$db = Configuration::getTiendaDB();

		$tbConfig1 = $db->nameQuote('#__virtuemart_vendors');
		$tbConfig2 = $db->nameQuote('#__virtuemart_vendors_es_es');
		$query = "SELECT 
						* 
				  FROM 
						$tbConfig1 as c1, 
						$tbConfig2 as c2
				  WHERE 
						c1.virtuemart_vendor_id = c2.virtuemart_vendor_id AND
						c1.virtuemart_vendor_id = 1
						";
		$db->setQuery($query);
	    $result = $db->loadObject();
		
		$tbConfig3 = $db->nameQuote('#__virtuemart_currencies');
		$query = "SELECT 
						* 
				  FROM 
						$tbConfig3 as c3
				  WHERE 
						c3.virtuemart_currency_id = 31
						";
		$db->setQuery($query);
	    $result2 = $db->loadObject();
		
		$result->currency = $result2;
		return $result;
	}
	
	
	function guardarConfiguracion($nombre, $simbolo, $decimales, $termServ, $infoLegal){
		$db = Configuration::getTiendaDB();
		$tbConfig1 = $db->nameQuote('#__virtuemart_vendors');
		$tbConfig2 = $db->nameQuote('#__virtuemart_vendors_es_es');
		$tbConfig3 = $db->nameQuote('#__virtuemart_currencies');
		
		$termServ = mysql_real_escape_string($termServ);
		$infoLegal = mysql_real_escape_string($infoLegal);
		
		$query = "
			  UPDATE
					$tbConfig1
			  SET 
					vendor_name = '%s'
			  WHERE 
					virtuemart_vendor_id = %s
					";
		$query = sprintf( $query, $nombre, 1);
		$db->setQuery($query);
		echo $query;
		$result = $db->query();
		
		if($result){
			$slug = str_replace(" " , "-", $nombre);
			$query = "
				  UPDATE
						$tbConfig2
				  SET 
						vendor_store_name = '%s',
						vendor_terms_of_service = '%s',
						vendor_legal_info = '%s',
						slug = '%s'
				  WHERE 
						virtuemart_vendor_id = %s
					";
			$query = sprintf( $query, $nombre, $termServ, $infoLegal, $slug, 1);
			$db->setQuery($query);
			$result = $db->query();
			
			$slug = str_replace(" " , "-", $nombre);
			$query = "
				  UPDATE
						$tbConfig3
				  SET 
						currency_symbol = '%s',
						currency_decimal_place = '%s'
				  WHERE 
						virtuemart_currency_id = %s
					";
			$query = sprintf( $query, $simbolo[0], $decimales[0], 31);
			$db->setQuery($query);
			$result = $db->query();
			
			return JText::_('M_OK'). JText::_('PRODUCTOS_CATEGORIAS_GUARDAR');
			
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
		
	}
	
	
	function registrar($accion, $anterior, $nuevo, $valor){
		JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_zcorreos'.DS.'tables');
		
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');

		$user = JFactory::getUser();
		
		$log =& JTable::getInstance('Log', 'Table');
		$log->accion = $accion;
		$log->fecha = $fechaHoy;
		$log->usuario = $user->id;
		$log->valor = $valor;
		$log->ip = Util::getIp();
		$log->store();
	
	}
	
	/*
	* Listado Log
	*/
	function listar($filtro, $estado, $inicio, $resultados){
		$db = & JFactory::getDBO();
		
		$tbLog = $db->nameQuote('#__zlog');
		$tbUsers = $db->nameQuote('#__users');
		
		
		$estado = ($estado != "") ?  " AND zsolicitudes.estado = '$estado' " : ""; 
		
		$query = "
					SELECT
						zlog.*, users.username
					FROM 
						$tbLog as zlog
					LEFT JOIN 
						$tbUsers as users
					ON 
						zlog.usuario = users.id
					WHERE
						(
							lower(accion) LIKE lower('%$filtro%') OR
							lower(valor) LIKE lower('%$filtro%') OR
							lower(username) LIKE lower('%$filtro%') 
						)
						$estado
					ORDER BY fecha DESC
						";
		
		//echo  $query;
		$db->setQuery($query, $inicio, $resultados);
	    $result = $db->loadObjectList();
		/*foreach($result as $row){
		}*/
		return $result;
	}
	
	
	/*
	* Contar Log
	*/
	function contar($filtro, $estado){
		$db = & JFactory::getDBO();
		
		$tbLog = $db->nameQuote('#__zlog');
		$tbUsers = $db->nameQuote('#__users');
		
		$estado = ($estado != "") ?  " AND estado = '$estado' " : ""; 
		
		$query = "
					SELECT
						count(*)
					FROM 
						$tbLog as zlog
					LEFT JOIN 
						$tbUsers as users
					ON 
						zlog.usuario = users.id
					WHERE
						(
							lower(accion) LIKE lower('%$filtro%') OR
							lower(valor) LIKE lower('%$filtro%') OR
							lower(username) LIKE lower('%$filtro%') 
						)
						$estado
						";
		//echo  $query;
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	
	
	
	
	
	
	

	
	
	
}










