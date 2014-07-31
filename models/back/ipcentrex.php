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


		
class ModelIpCentrex extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function saveRegistro($data){
		//Guarda el registro del usuario para revision y aprobacion
		
		//Obtiene datos para las validaciones
		$razon_social 	= $data['razon_social'];
		$telefono 		= $data['telefono'];
		$nit 			= $data['nit'];
		$usuario 		= $data['usuario'];
		$correo1 		= $data['correo'];
		$correo2 		= $data['correo2'];
		$pass 	 		= $data['clave'];
		$pass2 	 		= $data['clave2'];
		
		
		//Aplica validaciones
		
		if($razon_social == "" || $telefono == "" || $nit == "" || $usuario == "" || $correo1 == "" || $pass == ""){
			return "ERROR|". JText::_('IPCENTREX_ERROR_CAMPOS_VACIOS');
		}
		
		if(strlen($usuario) < 5){
			return "ERROR|". JText::_('IPCENTREX_ERROR_USUARIO_TAM');
		}
		
		if( $correo1 != $correo2){
			return "ERROR|". JText::_('IPCENTREX_ERROR_CORREOS_DIF');
		}
		
		if( $pass != $pass2){
			return "ERROR|". JText::_('IPCENTREX_ERROR_PASS_DIF');
		}
		
		if($this->existeUsuario($usuario)){
			return "ERROR|". JText::_('IPCENTREX_ERROR_USUARIO_EXISTE');
		}
		
	
		
		//Guarda en la base de datos
		
		$db = & JFactory::getDBO();
		JTable::addIncludePath(JPATH_COMPONENT. DS . 'tables');
		$row =& JTable::getInstance('User', 'Table');
		
		//Estado pendiente para aprobacion
		$row->estado = 'P';
		date_default_timezone_set('America/Bogota');
		$row->fecha_registro = date( 'Y-m-d H:i:s',time() );
		
		//Guarda en la base de datos.
		if($row->bind($data)){
			 if($row->store()){
				$msg = JText::_('IPCENTREX_REGISTRO_GUARDAR_OK');
				$this->sendMail();
				return "OK|$msg";
			 }
		}

		$msg = JText::_('IPCENTREX_REGISTRO_GUARDAR_NOK');
		return "ERROR|$msg";
		
	}
	
	/*
	* Verifica si un nombre de usuario está activo en el sistema
	*/
	function existeUsuario($usuario){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__zusers');
		
		$query = "
					SELECT
						count(*)
					FROM 
						$tbUsers 
					WHERE
						usuario = '$usuario' AND
						estado = 'A'
						";
						
		$db->setQuery($query);
		$result = $db->loadResult();
		if($result >= 1){
			return true;
		}
		return false;
	}
	
	
	
	function sendMail(){
		require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'phpMailer' . DS . 'class.phpmailer.php' );
		
		$body = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'nuevoregistro.html');
		
		//$body =  preg_replace("/TAG_CLAVE_ASIGNADA/", $pass, $body);
		
		$mail             = new PHPMailer();
		//$mail->Username =  "etp\correosac" ;
		//$mail->Password      = "Etp2011";        // SMTP account password
		//$user->email =  "sac@etp.com.co" ;
		$mail->Username =  JText::_('IPCENTREX_MAIL_USERNAME');
		$mail->Password      = JText::_('IPCENTREX_MAIL_PASSWORD');        // SMTP account password
		$user->email =  JText::_('IPCENTREX_MAIL_EMAIL') ;
		$mail->IsSMTP(); // telling the class to use SMTP
		//$mail->Host       = "exchange01"; // SMTP server
		$mail->Host       = JText::_('IPCENTREX_MAIL_HOST'); // SMTP server
		
		//$mail->SMTPAuth      = true;                  // enable SMTP authentication
		//$mail->Port          = 587;                    // set the SMTP port for the GMAIL server
		
		//$mail->SMTPSecure = "tls";                 // sets the prefix to the server
		
		$mail->SetFrom( $user->email , 'Une Telefonica de Pereira');
		$mail->Subject    = JText::_('IPCENTREX_MAIL_SUBJECT');
		$mail->AltBody    = JText::_('IPCENTREX_MAIL_ALTBODY'); 
		$mail->MsgHTML($body);
		
		//$address = "aquintero@etp.com.co"; //TODO quitar
		$mail->AddAddress(  JText::_('IPCENTREX_CORREO_ADMIN'), "Administrador IpCentrex" );
		if(!$mail->Send()) {
			//echo "Error al enviar el mensaje: " . $mail->ErrorInfo;
			return false;
		} else {
			//echo "El mensaje fue enviado!";
			return true;
			//Change status message
		}
	}
	
	
	
	
	
	
	
	
	
	/*
	
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
	*/
	
}









