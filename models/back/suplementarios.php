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


		
class ModelSuplementarios extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function saveCambioSuplementarios($data){
		
		$lineas = isset($data['lineas']) ? $data['lineas'] : array();
		
		if(count($lineas) >= 1){
			if( (isset($data['gt']) && $data['gt'] == 1 && $data['grupo_timbrado'] != "" ) || !isset($data['gt']) ){
				if( (isset($data['tt']) && $data['tt'] == 1 && $data['minutos_espera'] != "" ) || !isset($data['tt']) ){
					if( (isset($data['cv']) && $data['cv'] == 1 && $data['correo'] != "" ) || !isset($data['cv']) ){
				
						JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_zipcentrex'.DS.'tables');
						$row =& JTable::getInstance('CambioServicios', 'Table');
						
						if($row->bind($data)){
						
							date_default_timezone_set('America/Bogota');
							$fechaHoy = date('Y-m-d H:i:s');
							$user = JFactory::getUser();
							
							$row->lineas = substr($this->getLineas($data['lineas']), 0, strlen($this->getLineas($data['lineas'])) - 1  );
							$row->fecha_registro = $fechaHoy;
							$row->usuario_registro = $user->id;
							$row->estado = 'P';
							
							
							if($row->store()){
								$this->sendMail($row->id);
								return $row;
							}
						}
					}
					else{
						return "ERR|". JText::_("IPCENTREX_US_SUPLEMENTARIOS_ERR_CORREOVOZ");
					}
				}
				else{
					return "ERR|". JText::_("IPCENTREX_US_SUPLEMENTARIOS_ERR_TEMPORIZADOR");
				}
			}
			else{
				return "ERR|" . JText::_("IPCENTREX_US_SUPLEMENTARIOS_ERR_GRUPOTIMBRADO");
			}
		}
		else{
			return "ERR|" . JText::_("IPCENTREX_US_SUPLEMENTARIOS_ERR_LINEA");
		}
		
		//Verificar que escoga por lo menos una linea
		//Verificar que si seleccion gt tambien seleccione grupo
	}
	
	function getLineas($lineas){
		$lineasStr = "";
		
		foreach($lineas as $linea){
			$lineasStr .= $linea. ",";
		}
		return $lineasStr;
	}
	
	
	function sendMail($id){
		require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'phpMailer' . DS . 'class.phpmailer.php' );
		
		$body = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'nuevasolicitudadmin.html');
		
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
	
}









