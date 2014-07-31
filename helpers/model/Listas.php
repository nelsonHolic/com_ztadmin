<?php 

class Listas{

	function getAseguradoras(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAseguradoras = $db->nameQuote('#__zaseguradoras');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbAseguradoras a
				  WHERE
						a.usuario = %s  AND
						a.activo = 1
				  ORDER BY
						descripcion 
						";
						
		$query = sprintf($query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getClientes(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbClientes = $db->nameQuote('#__zclientes');			
		
		$query = "SELECT 
						id, documento, primer_nombre, segundo_nombre, primer_apellido , segundo_apellido
				  FROM 
						$tbClientes a
				  WHERE
						a.usuario = %s  AND
						a.activo = 1
				  ORDER BY
						primer_nombre || segundo_nombre, primer_apellido || segundo_apellido
						";
						
		$query = sprintf($query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getRamos(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRamos = $db->nameQuote('#__zramos');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbRamos a
				  WHERE
						a.usuario = %s  AND
						a.activo = 1
				  ORDER BY
						descripcion
						";
						
		$query = sprintf($query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getVendedores(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbVendedores = $db->nameQuote('#__zvendedores');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbVendedores a
				  WHERE
						a.usuario = %s  AND
						a.activo = 1
				  ORDER BY
						nombre, apellido
						";
						
		$query = sprintf($query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
}








