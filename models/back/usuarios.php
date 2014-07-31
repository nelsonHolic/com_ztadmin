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
 * @subpackage	ztelecliente
 * @since 1.6
 */


		
class ModelUsuarios extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	/*
	* Usuarios en el sistema
	*/
	function listar($filtro, $estado, $inicio, $resultados){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zusers');
		$tbUsersJ = $db->nameQuote('#__users');
		
		$estado = ($estado != "") ?  " AND estado = '$estado' " : ""; 
		$query = "
					SELECT
						zusers.*, users.id as userId
					FROM 
						$tbUsers as zusers
					LEFT JOIN 
						$tbUsersJ as users
					ON
						zusers.usuario = users.username 
					WHERE
						(
							lower(nit) LIKE lower('%$filtro%') OR
							lower(razon_social) LIKE lower('%$filtro%')  OR
							lower(usuario) LIKE lower('%$filtro%')
						)
						$estado
						";
		//echo  $query;
		$db->setQuery($query, $inicio, $resultados);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Conteo de usuarios en el sistema
	*/
	function contar($filtro, $estado){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zusers');
		
		$estado = ($estado != "") ?  " AND estado = '$estado' " : "" ;
		$query = "
					SELECT
						count(*)
					FROM 
						$tbUsers 
					WHERE
						(
							lower(nit) LIKE lower('%$filtro%') OR
							lower(razon_social) LIKE lower('%$filtro%')  OR
							lower(usuario) LIKE lower('%$filtro%')
						)
						$estado
						";
						
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	/*
	* Usuarios en el sistema
	*/
	function cambiarEstadoUsuario($id, $estado){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zusers');
		
		date_default_timezone_set('America/Bogota');
		$fecha_revision = date( 'Y-m-d H:i:s', time() );
		$user = JFactory::getUser();
		
		$query = "
					UPDATE
						$tbUsers 
					SET
						estado = '$estado',
						fecha_revision = '$fecha_revision',
						usuario_revision = '{$user->username}'
					WHERE
						id = $id
						
						";
		//echo  $query;
		$db->setQuery($query);
		$result = $db->query();
		if($estado == 'A'){
			$this->crearAccesoUsuario($id);
		}
		return $result;
	}
	
	
	
	
	function cargarUsuario($id){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zusers');
		
		$query = "
					SELECT
						*
					FROM 
						$tbUsers 
					WHERE
						id = $id
						";
		//echo  $query;
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	function crearAccesoUsuario($id){
		$db = & JFactory::getDBO();
		
		$usuario = $this->cargarUsuario($id);
		
		$nombre 	= $usuario->razon_social;
		$username 	= $usuario->usuario;	 
		$email 		= $usuario->correo;	 
		$pass 		= $usuario->clave;	 
		
		//Guarda el usuario
		$query = "
			INSERT INTO jos_users
			(`id`, `name`, `username`, `email`, `password`, `usertype`, `block`, `sendEmail`, `registerDate`, `lastvisitDate`, `activation`, `params`, `tipo`) 
			VALUES 
			( NULL, '$nombre', '$username', '$email', md5('$pass'), '', '0', '0', now(), '0000-00-00 00:00:00', '', '', 'U')
		";
		
		$db->setQuery($query);
		$result = $db->query($query);
		
		$uid = $db->insertid();

		if( $uid > 0 ){
			$query = "
			INSERT INTO `jos_user_usergroup_map` 
			( user_id, group_id)
			VALUES 
			( $uid , 2)
			";
			$db->setQuery($query);
			$result = $db->query($query);
		}
		
		
	}
	
	/*
	* Cambiar clave usuario
	*/
	function cambiarClave($id, $pass){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__users');
		
		
		$query = "
					UPDATE
						$tbUsers 
					SET
						password = md5('$pass')
					WHERE
						id = $id
						
						";
		//echo  $query;
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	
	function getNombreIpCentrex($nit){
		
		//Web service
		$client = new SoapClient("http://10.4.251.17/etp/ws/ipcentrex/ipcentrex.php?wsdl");
		$result = $client->infoIpCentrex($nit);
		$data = explode("|", $result);
		
		$data[1] = isset($data[1]) ? $data[1] : "";
		
		return $data[1];
				
	}
	
	
	
	
}









