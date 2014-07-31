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


		
class ModelFlexible extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getProducts($cedula){
		$client = new SoapClient("http://10.4.251.17/etp/ws/flexible.php?wsdl");
		$result = $client->getProductosByCedula($cedula);
		return $result;
	}
	
	function logAccess($cedula, $productos){
		$db = & JFactory::getDBO();
		
		JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_zflexible'.DS.'tables');
		$row =& JTable::getInstance('Log', 'Table');
		
		$row->cedula 	=	$cedula;
		$row->productos =	$productos;	 
		$row->fecha 	=	date('Y-m-d h:i:s');
		$row->store();
		
	}
	
	function getSegmento($producto){
		$client = new SoapClient("http://10.4.251.17/etp/ws/flexible.php?wsdl");
		$result = $client->getSegmento($producto);
		return $result;
	}
	
	function getPregunta($producto, $pregunta){
		$client = new SoapClient("http://10.4.251.17/etp/ws/flexible.php?wsdl");
		$result = $client->getPregunta($producto, $pregunta);
		return $result;
	}
	
	function savePaso1($data){
		JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_zflexible'.DS.'tables');
		$row =& JTable::getInstance('User', 'Table');
		
		$nombre 			= isset($data['nombre']) ? $data['nombre'] : "";
		$tipo 				= isset($data['tipo']) ? $data['tipo'] : "";
		$cedula 			= isset($data['cedula']) ? $data['cedula'] : "";
		$correo 			= isset($data['correo']) ? $data['correo'] : "";
		$correo2 			= isset($data['correo2']) ? $data['correo2'] : "";
		$persona_contacto 	= isset($data['persona_contacto']) ? $data['persona_contacto'] : "";
		$telefono_contacto 	= isset($data['telefono_contacto']) ? $data['telefono_contacto'] : "";
		$producto 			= isset($data['producto']) ? $data['producto'] : "";
		$segmento 			= isset($data['segmento']) ? $data['segmento'] : "";
		
		if( $nombre != "" && $tipo != "" && $cedula != "" && $correo != "" && $persona_contacto != "" && $telefono_contacto != "" && $producto != "" &&
			$segmento != "" 
		  ){
		  
			if( $correo == $correo2 ){
				$existe = $this->existeProducto($cedula, $producto);
				$bloqueado = $this->estaBloqueado($cedula, $producto);
				if($bloqueado == 0 ){
					if($existe == 0 ){
						$row->estado = 'P';
						if($row->bind($data)){
							 if($row->store()){
								return $row;
							 }
						}
						$row->error = "Ocurri&oacute; un error al guardar los datos";
						return $row;
					}
					else{
						$row->error = "Este producto ya est&aacute; asociado al usuario";
						return $row;
					}
				}
				else{
					$row->error = "El usuario se encuentra bloqueado por demasiados intentos fallidos. Comuniquese con nuestra l&iacute;nea de atenci&oacute;n para realizar nuevamente el proceso.";
					return $row;
				}
			}
			else{
				$row->error = "Los correos deben coincidir";
				return $row;
			}
			
		}
		else{
			$row->error = "Debe ingresar todos los campos marcados como obligatorios con el s&iacute;mbolo (*)";
			return $row;
		}	
	}
	
	function bloquearUsuario($cedula, $producto){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		$query = "
					UPDATE
						$tbUsers 
					SET 
						estado = 'B'
					WHERE
						cedula = '$cedula' AND
						producto = '$producto'
						";
						
		$db->setQuery($query);
	    $result = $db->query();
		return $result;
	}
	
	function estaBloqueado($cedula, $producto){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		$query = "
					SELECT
						count(*)
					FROM 
						$tbUsers 
					WHERE
						cedula = '$cedula' AND
						producto = '$producto' AND
						estado = 'B'
						";
						
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	
	function existeProducto($cedula, $producto){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		
		$query = "
					SELECT 
						count(*)
					FROM 
						$tbUsers 
					WHERE
						cedula = '$cedula' AND
						producto = '$producto' AND
						estado = 'A'
						";
		
		//echo $query;
		$db->setQuery($query);
	
	    $result = $db->loadResult();
		return $result;
	}
	
	
	function existeUsuario( $usuario ){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		$query = "
					SELECT 
						count(*)
					FROM 
						$tbUsers users
					WHERE
						usuario = '$usuario' AND
						(estado = 'A' OR estado='V')
						";
						
		$db->setQuery($query);
	
	    $result = $db->loadResult();
		
		return ($result == 0 ? false : true );
	}
	
	
	function saveDocuments($producto, $cedula, $certExt, $cartRep, $ceduRep){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		//Obtiene el ultimo ingreso
		$query = "
					SELECT 
						max(id)
					FROM 
						$tbUsers 
					WHERE
						cedula = '$cedula' AND
						producto = '$producto'
						";
		$db->setQuery($query);
	    $max = $db->loadResult();
		
		
		//borrar registros anteriores
		$query = "
					DELETE FROM 
						$tbUsers 
					WHERE
						cedula = '$cedula' AND
						producto = '$producto' AND
						id < $max 
						";
		//echo $query;			
		$db->setQuery($query);
	    $result = $db->query();
		
		$query = "
					UPDATE
						$tbUsers 
					SET 
						cert_ext = '$certExt',
						cart_rep = '$cartRep',
						cedu_rep = '$ceduRep'
					WHERE
						cedula = '$cedula' AND
						producto = '$producto'
						";
		//echo $query;			
		$db->setQuery($query);
	    $result = $db->query();
	}
	
	
	function activarUsuario($cedula, $producto, $usuario, $clave, $estado){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		//Borrar usuarios repetidos
		//Obtiene el ultimo ingreso
		$query = "
					SELECT 
						max(id)
					FROM 
						$tbUsers 
					WHERE
						cedula = '$cedula' AND
						producto = '$producto'
						";
		$db->setQuery($query);
	    $max = $db->loadResult();
		
		//borrar registros anteriores
		$query = "
					DELETE FROM 
						$tbUsers 
					WHERE
						cedula = '$cedula' AND
						producto = '$producto' AND
						id < $max 
						";
		//echo $query;			
		$db->setQuery($query);
	    $result = $db->query();
		
		
		$query = "
					UPDATE
						$tbUsers 
					SET 
						usuario = '$usuario',
						clave = md5('$clave'),
						clave2 = '$clave',
						estado = '$estado'
					WHERE
						cedula = '$cedula' AND
						producto = '$producto'
						";
						
		
						
		$client = new SoapClient("http://10.4.251.17/etp/ws/flexible.php?wsdl");
		$resultWs = $client->guardarUsuario($cedula, $producto, $usuario, 'A');
		
		$db->setQuery($query);
	    $result = $db->query();
						
		
		return $result;
	}
	
	function getPendienteEmpresas(){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		$query = "
					SELECT 
						*
					FROM 
						$tbUsers 
					WHERE
						estado = 'V'
						";
						
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	
	function getUser($id){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		
		$query = "
					SELECT
						*
					FROM 
						$tbUsers 
					WHERE
						id = '$id' 
						";
						
		$db->setQuery($query);
		$result = $db->loadObject();
		return $result;
	}
	
	
	function cambiarEstadoEmpresa($id, $estado){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zfusers');
		$query = "
					UPDATE
						$tbUsers 
					SET
						estado = '$estado'
					WHERE
						id = $id
						";
		
		//echo $query;
		$db->setQuery($query);
	    $result = $db->query();
		
		return $result;
	}
	
	
}









