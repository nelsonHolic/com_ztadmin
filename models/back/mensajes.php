<?php


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 *
 * @author      aquintero
 * @package		Etp
 * @subpackage	correos
 * @since 1.6
 */


		
class ModelMensajes extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	/*
	* Solicitudes
	*/
	function listar($filtro, $estado, $inicio, $resultados){
		$db = & JFactory::getDBO();
		
		$tbMensajes = $db->nameQuote('#__zmensajes');
		$tbUsuarios = $db->nameQuote('#__users');
		
		//Filtra el estado
		$estadoQuery = "";
		if($estado == "P"){
			$estadoQuery = " AND cantidad - enviados > 0 ";
		}
		else if($estado == "T"){
			$estadoQuery = " AND cantidad - enviados <= 0 ";
		}
		
		$query = "
					SELECT
						mensajes.*, usuarios.username as usuario
					FROM 
						$tbMensajes as mensajes,
						$tbUsuarios as usuarios
					WHERE
						mensajes.usuario = usuarios.id AND
						(
							lower(asunto) LIKE lower('%$filtro%')  OR
							lower(cuenta) LIKE lower('%$filtro%')  OR
							lower(usuario) LIKE lower('%$filtro%')  
						)
						$estadoQuery
					ORDER BY 
						fecha_registro DESC
						";
		//echo  $query;
		$db->setQuery($query, $inicio, $resultados);
	    $result = $db->loadObjectList();
		foreach($result as $row){
			//$row->mensajeCambio = $this->mensajeCambio($row);
		}
		return $result;
	}
	
	/*
	* Solicitudes
	*/
	function contar($filtro, $estado){
		$db = & JFactory::getDBO();
		
		$tbMensajes = $db->nameQuote('#__zmensajes');
		$tbUsuarios = $db->nameQuote('#__users');
		
		//$estado = ($estado != "") ?  " AND estado = '$estado' " : ""; 
		
		$query = "
					SELECT
						count(*)
					FROM 
						$tbMensajes as mensajes,
						$tbUsuarios as usuarios
					WHERE
						mensajes.usuario = usuarios.id AND
						(
							lower(asunto) LIKE lower('%$filtro%')  OR
							lower(cuenta) LIKE lower('%$filtro%')  OR
							lower(usuario) LIKE lower('%$filtro%')  
						)
						";
		//echo  $query;
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getFormatos(){
		$db 		= & JFactory::getDBO();
		$tbFormatos	= $db->nameQuote('#__zformatos');
		
		$query = "
					SELECT 
						*
					FROM 
						$tbFormatos as formatos
					ORDER BY 
						descripcion
						";
						
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
		
	}
	
	
	
	
	
	
	
	
}









