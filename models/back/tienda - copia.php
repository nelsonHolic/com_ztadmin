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
	
	
	function listarMenu(){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						* 
				  FROM 
						$tbMenu 
				  WHERE 
						published = 1 AND 
						parent_id = 1 and 
						menutype = 'mainmenu' 
						and type='component'";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		//TODO traer otros niveles
		
		return $result;
	}
	
	function contarMenu(){
		
	}
	
	
	function crearItemMenu(){
		
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						* 
				  FROM 
						$tbMenu 
				  WHERE 
						published = 1 AND 
						parent_id = 1 and 
						menutype = 'mainmenu' 
						and type='component'";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		foreach($result as $item){
			echo $item->title . "  ";
			echo $item->link . "  ";
			echo $item->level . "  ";
			echo $item->parent_id . "  ";
			echo "<br/>";
		}
	
		
		echo "creando un nuevo item";
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










