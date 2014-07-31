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


		
class ModelSolicitudesAdmin extends JModel{
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
		
		$tbSolicitudes = $db->nameQuote('#__zcambio_servicios');
		$tbEstados = $db->nameQuote('#__zestados');
		$tbUsers = $db->nameQuote('#__users');
		$tbZUsers = $db->nameQuote('#__zusers');
		
		$user = JFactory::getUser();
		
		$estado = ($estado != "") ?  " AND zsolicitudes.estado = '$estado' " : ""; 
		$query = "
					SELECT
						zsolicitudes.* , estados.estado as estadoSolicitud, estados.descripcion as descripcionEstado, zusers.nit
					FROM 
						$tbSolicitudes as zsolicitudes,
						$tbEstados as estados,
						$tbUsers as users,
						$tbZUsers as zusers
					WHERE
						zsolicitudes.usuario_registro = users.id AND
						users.username = zusers.usuario AND
						zsolicitudes.estado = estados.estado AND 
						(
							lower(lineas) LIKE lower('%$filtro%') 
						)
						$estado
						
					ORDER BY fecha_registro DESC
						";
		
		//echo  $query;
		$db->setQuery($query, $inicio, $resultados);
	    $result = $db->loadObjectList();
		foreach($result as $row){
			$row->mensajeCambio = $this->mensajeCambio($row);
			$row->usuario = $this->usuarioEjecuto($row->usuario_ejecucion);
		}
		return $result;
	}
	
	/*
	* Contar Solicitudes
	*/
	function contar($filtro, $estado){
		$db = & JFactory::getDBO();
		
		$tbSolicitudes = $db->nameQuote('#__zcambio_servicios');
		$tbEstados = $db->nameQuote('#__zestados');
		$tbUsers = $db->nameQuote('#__users');
		$tbZUsers = $db->nameQuote('#__zusers');
		
		$user = JFactory::getUser();
		
		$estado = ($estado != "") ?  " AND estado = '$estado' " : ""; 
		$query = "
					SELECT
						count(*)
					FROM 
						$tbSolicitudes as zsolicitudes,
						$tbEstados as estados,
						$tbUsers as users,
						$tbZUsers as zusers
					WHERE
						zsolicitudes.usuario_registro = users.id AND
						users.username = zusers.usuario AND
						zsolicitudes.estado = estados.estado AND 
						(
							lower(lineas) LIKE lower('%$filtro%') 
						)
						$estado
						";
		//echo  $query;
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	/*
	* Cambiar estado solicitud
	*/
	function atenderSolicitud($id){
		$db = & JFactory::getDBO();
		
		$tbServicios = $db->nameQuote('#__zcambio_servicios');
		
		date_default_timezone_set('America/Bogota');
		$fecha_ejecucion = date( 'Y-m-d H:i:s', time() );
		$user = JFactory::getUser();
		
		//error_reporting(E_ALL);
		//ini_set('display_errors','On');


		$query = "
					UPDATE
						$tbServicios
					SET
						estado = 'A',
						fecha_ejecucion = '$fecha_ejecucion',
						usuario_ejecucion = '{$user->id}'
					WHERE
						id = $id
						
						";
		
		$db->setQuery($query);
		$result = $db->query();
		
		//Enviar notificacion al usuario
		$this->sendMail($id);
		return $result;
	}
	
	/*
	* Solicitudes
	*/
	function usuarioEjecuto($usuario){
		$db = & JFactory::getDBO();
		
		$tbUsers = $db->nameQuote('#__users');
		$user = JFactory::getUser();
		
		$query = "
					SELECT
						username
					FROM 
						$tbUsers as users
					WHERE
						id = $usuario
						";
		//echo  $query;
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	function mensajeCambio($row){
		$msg = "";
		
		if( $row->local == 1){
			$msg .=   JText::_('IPCENTREX_CAT_LOCAL_TOOLTIP') . " + " ;
		}
		
		if( $row->nacional == 1){
			$msg .=   JText::_('IPCENTREX_CAT_DDN_TOOLTIP') . " + " ;
		}
		
		if( $row->internacional == 1){
			$msg .=   JText::_('IPCENTREX_CAT_DDI_TOOLTIP') . " + " ;
		}
		
		if( $row->celular == 1){
			$msg .=   JText::_('IPCENTREX_CAT_CEL_TOOLTIP') . " + " ;
		}
		
		if( $row->c901 == 1){
			$msg .=   JText::_('IPCENTREX_CAT_901_TOOLTIP') . " + " ;
		}
		
		if( $row->c113 == 1){
			$msg .=   JText::_('IPCENTREX_CAT_113_TOOLTIP') . " + " ;
		}
		
		if( $row->il == 1){
			$msg .=   JText::_('IPCENTREX_SER_IL_TOOLTIP') . " + " ;
		}
		
		if( $row->ct == 1){
			$msg .=   JText::_('IPCENTREX_SER_CT_TOOLTIP') . " + " ;
		}
		
		if( $row->gt == 1){
			$msg .=   JText::_('IPCENTREX_SER_GT_TOOLTIP') . "[{$row->grupo_timbrado}] + " ;
		}
		
		if( $row->tt == 1){
			$msg .=   JText::_('IPCENTREX_SER_TE_TOOLTIP') . "[{$row->minutos_espera}] + " ;
		}
		
		if( $row->cv == 1){
			$msg .=   JText::_('IPCENTREX_SER_CV_TOOLTIP') . "[{$row->correo}] + " ;
		}
		
		return substr($msg, 0, strlen($msg) -2 );
		
	}
	
	
	function sendMail($id){
		require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'phpMailer' . DS . 'class.phpmailer.php' );

		$db = & JFactory::getDBO();
		
		$tbServicios = $db->nameQuote('#__zcambio_servicios');
		$tbUsuarios = $db->nameQuote('#__users');
		
		//Obtiene el correo del usuario que registro la solicitud
		$query = "
				SELECT 
					name, username, email
				FROM
					$tbServicios as servicios,
					$tbUsuarios as usuarios
				WHERE
					servicios.usuario_registro = usuarios.id AND
					servicios.id = $id
					";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
						
		//echo "name={$result->name} name={$result->username} name={$result->email}";
		
		$body = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'atencionsolicitudadmin.html');
		
		$body =  preg_replace("/TAG_NAME/", $result->name, $body);
		$body =  preg_replace("/TAG_ID/", $id, $body);
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
		$mail->AddAddress( $result->email, $result->name );
		if(!$mail->Send()) {
			//echo "Error al enviar el mensaje: " . $mail->ErrorInfo;
			return false;
		} else {
			//echo "El mensaje fue enviado!";
			return true;
			//Change status message
		}
		
	}
	
	
	
	
	
	
}









