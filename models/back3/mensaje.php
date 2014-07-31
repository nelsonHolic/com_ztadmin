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
 * Mensaje
 *
 * @author      aquintero
 * @package		Joomla
 * @since 1.6
 */
		
class ModelMensaje extends JModel{

	const TAM_MSG       = 160;
	const TAM_MSG2      = 153;
	const TAM_MSG_TILDE = 70;
	
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function verificarGuardar(){
		//Obtener mensajes
		//Obtener contactos 
		//Creditos actuales
		
		//Armar mensaje
		//Enviar datos en sesión para guardar!!
		
	}
	
	
	function guardar(&$data){
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Mensajes', 'Table');
		$user = JFactory::getUser();
		$fechaHoy = Configuration::getDate();
		$fechaEnvio = $fechaHoy;
			
		$grupos 	= $data['grupos'];
		$contactos 	= $data['contactos'];
		$telefonos 	= $data['telefonos'];
		$mensaje 	= $data['message'];
		$envioPost 	= isset($data['envio_posterior']) ?  $data['envio_posterior'] : "";
		
		if( $envioPost == "on"){
			$fechaPost 	= $data['fecha']; //JRequest::getVar('fecha');
			$horaPost 	= $data['hora'];
			$minutoPost = $data['minuto'];
			$fechaPost = "$fechaPost $horaPost:$minutoPost:00";
			//echo "$fechaHoy - $fechaPost";
			$datetime1 = new DateTime( $fechaHoy );
			$datetime2 = new DateTime( $fechaPost );
			
			//Php 5.3
			//$interval = $datetime1->diff($datetime2);
			///$dias = $interval->format('%R%a');
			//$horas = $interval->format('%R%h');
			
			//Php 5.2
			//http://stackoverflow.com/questions/4033224/what-can-use-for-datetimediff-for-php-5-2
			$interval = $this->dateTimeDiff($datetime1, $datetime2);
			print_r($interval);
			$dias = $interval->d;
			$horas = $interval->h;
			
			echo "dias = " . $dias;
			echo "horas = " . $horas; 

			if($horas >=0 && $horas >= 0){
				$fechaEnvio = $fechaPost;
			}
			//echo "fecha envio = " . $fechaHoy;
		}
		
		
		$mensaje = $this->removerCaracteresEspeciales($mensaje);
		$cantidadMensajes = $this->contarMensajes($mensaje, $tam );
		
		
		$row->asunto = "";
		$row->origen = $user->username;
		$row->data = $mensaje;
		$row->usuario = $user->id;
		$row->estado = 'P';
		$row->cantidad_sms = $cantidadMensajes;
		$row->tamano_sms = $tam;
		$row->fecha_creacion = $fechaHoy;
		$row->fecha_envio = $fechaEnvio;
		
	
		
		if( isset($data['envio_todos']) ){
			ZDBHelper::initTransaction($db);
			if($row->store()){
				$id = $db->insertid();
				$cantContactos 	= $this->guardarContactosTodos( $row->id, $mensaje, $fechaEnvio  );
				//Actualiza destinos
				$row =& JTable::getInstance('Mensajes', 'Table');
				$row->id = $id;
				$row->cantidad_destinos = $cantContactos;
				$row->total_sms = $cantContactos * $cantidadMensajes;
				$row->store();
				$row->load();
				
				$mensajesActuales = $this->getMensajesUsuario($user->id);
				if( $row->total_sms > $mensajesActuales && $user->ilimitado == 0 ){
					ZDBHelper::rollBack($db);
					return JText::_('M_ERROR'). sprintf( JText::_('VE_GUARDAR_MENSAJE_ERROR_CREDITOS') , 
														 $row->total_sms, 
														 $mensajesActuales );
				}
				
				if( $this->actualizarMensajesUsuario( $user->id, $mensajesActuales - $row->total_sms ) ){
					ZDBHelper::commit($db);
					//Registra en log
					Log::add("Envio de mensaje", 
							 "Mensajes enviados = " . $row->total_sms . " SMS = " );
					$data = $row;
					Session::setVar("data", array() );
					return JText::_('M_OK') . sprintf( JText::_('VE_GUARDAR_MENSAJE_OK') , $row->id );
				}
				else{
					ZDBHelper::rollBack($db);
					return JText::_('M_ERROR'). JText::_('VE_GUARDAR_MENSAJE_ERROR');
				}
			}
			else{
				ZDBHelper::rollBack($db);
				return JText::_('M_ERROR'). JText::_('VE_GUARDAR_MENSAJE_ERROR');
			}
		}
		else if( ($grupos != "" || $contactos != "" || $telefonos != "" )  ){
			if(strlen($mensaje) >= 1){
	
				ZDBHelper::initTransaction($db);

				if($row->store()){
					$id = $db->insertid();
					$cantContactos 	= $this->guardarContactos( $contactos, $row->id, $mensaje, $fechaEnvio  );
					$cantGrupos 	= $this->guardarGrupos( $grupos, $row->id, $mensaje, $fechaEnvio );
					$cantTelefonos 	= $this->guardarTelefonos( $telefonos, $row->id, $mensaje, $fechaEnvio );
					$destinos = $cantContactos + $cantGrupos + $cantTelefonos;
					//Actualiza destinos
					$row =& JTable::getInstance('Mensajes', 'Table');
					$row->id = $id;
					$row->cantidad_destinos = $destinos;
					$row->total_sms = $destinos * $cantidadMensajes;
					$row->store();
					$row->load();
					
					$mensajesActuales = $this->getMensajesUsuario($user->id);
					
					//echo $row->cantidad_destinos * $cantidadMensajes . " ----- " . $mensajesActuales;
					//exit;
					if( $row->total_sms > $mensajesActuales && $user->ilimitado == 0 ){
						ZDBHelper::rollBack($db);
						return JText::_('M_ERROR'). sprintf( JText::_('VE_GUARDAR_MENSAJE_ERROR_CREDITOS') , 
															 $row->total_sms, 
															 $mensajesActuales );
					}
				
					//Guarda en tabla de despacho masivo
					
					if( $this->actualizarMensajesUsuario( $user->id, $mensajesActuales - $row->total_sms ) ){
						ZDBHelper::commit($db);
						//Registra en log
						Log::add("Envio de mensaje", 
								 "Mensajes enviados = " . $row->total_sms . " SMS = " );
						$data = $row;
						Session::setVar("data", array() );
						return JText::_('M_OK') . sprintf( JText::_('VE_GUARDAR_MENSAJE_OK') , $row->id );
					}
					else{
						ZDBHelper::rollBack($db);
						return JText::_('M_ERROR'). JText::_('VE_GUARDAR_MENSAJE_ERROR');
					}
				}
				else{
					ZDBHelper::rollBack($db);
					return JText::_('M_ERROR'). JText::_('VE_GUARDAR_MENSAJE_ERROR');
				}
			}
			else{
				return JText::_('M_ERROR'). JText::_('VE_GUARDAR_MENSAJE_ERROR_TAM_VACIO');
			}
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_GUARDAR_MENSAJE_ERROR_DATOS');
		}
		
	}

	function guardarContactos( $contactos, $mensajeId, $mensaje, $fechaEnvio){
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		if($contactos != ""){
			$contactos = explode(",", $contactos);
			$cantidad = 0;
		
			foreach( $contactos as $contacto){
				$contacto = $this->getContactoById($contacto);			
				
				if( Mensajes::esNumeroValido( $contacto->movil ) ){
				
					$result = $this->programarMensajePlataforma($contacto->movil, $mensaje, $fechaEnvio);
					if(!$result){
						ZDBHelper::rollBack($db);
						return false;
					}
					
					$cantidad = $cantidad + 1;
					
				}
			}
			return $cantidad;
		}
		else{
			return 0;
		}
	}
	
	function guardarContactosTodos( $mensajeId, $mensaje, $fechaEnvio){
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		
		$cantidad = 0;
		$contactos = $this->getContactosTodos();
		foreach( $contactos as $contacto){
			$contacto = $this->getContactoById($contacto->id);
			if( Mensajes::esNumeroValido( $contacto->movil ) ){
				$result = $this->programarMensajePlataforma($contacto->movil, $mensaje, $fechaEnvio);
				if(!$result){
					ZDBHelper::rollBack($db);
					return false;
				}
				$cantidad = $cantidad + 1;
			}
		}	
		return $cantidad;
	}
	
	function guardarGrupos( $grupos, $mensajeId, $mensaje, $fechaEnvio){
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		
		$cantidad = 0;
		
		if($grupos != ""){
			$grupos = explode(",", $grupos);
			
			foreach($grupos as $grupo){
				$contactos = $this->getContactosByGrupo($grupo);
				foreach($contactos as $contacto){
					if( Mensajes::esNumeroValido( $contacto->movil ) ){
						$result = $this->programarMensajePlataforma($contacto->movil, $mensaje, $fechaEnvio);
						if(!$result){
							ZDBHelper::rollBack($db);
							return false;
						}
						$cantidad = $cantidad + 1;
					}
				}
			}
		}
		
		return $cantidad;
	}
	
	function guardarTelefonos( $telefonos, $mensajeId, $mensaje, $fechaEnvio){
		$cantidad = 0;
		if($telefonos != ""){
			$telefonos = explode(";", $telefonos);
			
			$db = JFactory::getDBO();
			JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
			
			foreach( $telefonos as $telefono){
				if( Mensajes::esNumeroValido( $telefono ) ){
					$result = $this->programarMensajePlataforma($telefono, $mensaje, $fechaEnvio);
					if(!$result){
						ZDBHelper::rollBack($db);
						return false;
					}
					$cantidad = $cantidad + 1;
				}
			}
		}
		return $cantidad;
	}
	
	function contarContactosGrupo($grupo){
		$db = JFactory::getDBO();
		$tbGrupoContacto = $db->nameQuote('#__zmgrupocontacto');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						count(*)
				   FROM 
						$tbGrupoContacto as g
				   WHERE 
						 g.grupo = %s
						";
		$query = sprintf( $query, $grupo);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function contarTodosContactos(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbContactos = $db->nameQuote('#__zmcontactos');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						count(*)
				   FROM 
						$tbContactos as c
				   WHERE 
						 c.usuario = %s
						";
		$query = sprintf( $query, $user->id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}

	function contarMensajes($msg, &$maxMensaje ){
		$mensajes = 1; 
		$tam = mb_strlen($msg);
		//$tam = 307;
		$max = $this->tamMaxMensajes($msg); 
		$maxMensaje = $max;
		//echo " tam = $tam max=$max ";
		if( $tam > $max){
         //$tam = $tam - $max;
			$mensajes = ceil($tam / ModelMensaje::TAM_MSG2 ) ;
		}
		//echo "menn= $mensajes";
      //exit;
		return $mensajes;
	}
	
	function tamMaxMensajes($msg){
		//$msg = str_replace("ñ", "n", $msg);
		$msg = htmlentities($msg);
		//echo $msg;
		//echo "pos=0" . strpos($msg, "tilde;");
		/*if( strpos($msg, "tilde;") > 0 && strpos($msg, "tilde;") !== false ){
			return ModelMensaje::TAM_MSG_TILDE;
		}
		else{
		*/
		return ModelMensaje::TAM_MSG ;
		//}
	}
	
	function getMensajesUsuario($user){
		$db = JFactory::getDBO();
		$tbUsers = $db->nameQuote('#__users');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						mensajes
				   FROM 
						$tbUsers as u
				   WHERE 
						 u.id = %s
						";
		$query = sprintf( $query, $user);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getContactosTodos(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbContactos = $db->nameQuote('#__zmcontactos');		
		
		$query = "SELECT 
						*
				   FROM 
						$tbContactos as c
				   WHERE 
						 usuario = %s
						";
		$query = sprintf( $query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function actualizarMensajesUsuario($user, $mensajes){
		$db = JFactory::getDBO();
		$tbUsers = $db->nameQuote('#__users');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbUsers
					SET 
						mensajes = %s		
					WHERE
						id = %s
						";
		$query = sprintf( $query, $mensajes, $user);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function removerCaracteresEspeciales($mensaje){
		//TODO reemplazar caracteres especiales
		$mensaje = str_replace('á', 'a', $mensaje);
		$mensaje = str_replace('é', 'e', $mensaje);
		$mensaje = str_replace('í', 'i', $mensaje);
		$mensaje = str_replace('ó', 'o', $mensaje);
		$mensaje = str_replace('ú', 'u', $mensaje);
		$mensaje = str_replace('Á', 'A', $mensaje);
		$mensaje = str_replace('É', 'E', $mensaje);
		$mensaje = str_replace('Í', 'I', $mensaje);
		$mensaje = str_replace('Ó', 'O', $mensaje);
		$mensaje = str_replace('Ú', 'U', $mensaje);
		return $mensaje;
	}
	
	function programarMensajePlataforma($destino, $mensaje, $fechaEnvio ){
		$user = JFactory::getUser();
		//Programa el mensaje para ser enviado en plataforma
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Outbox', 'Table');
		$row->username = $user->username;
		$row->msgtype = "SMS:TEXT";
		$row->msgid = "";
		$row->sender = $user->username;
		$row->msgsubject = "";
		$row->receiver = $destino;
		$row->msgdata = $mensaje;
		
		//Guarda fecha de envio
		$fechaHoy = Configuration::getDate();
		if( $fechaEnvio > $fechaHoy){
			$row->sendondate = $fechaEnvio;
			$row->status = "program";
		}
		else{
			$row->acceptedfordeliverytime = $fechaEnvio;
			$row->status = "ACCEPTED";
		}
		
		
		$row->user = $user->id;
		
		if(!$row->store()){
			ZDBHelper::rollBack($db);
			return false;
		}
		return true;
	}
	
	function getContactoById($contacto){
		$row = &JTable::getInstance('Contactos', 'Table');
		$row->id = $contacto;
		$row->load();
		return $row;
	}
	
	function getMensajeInboxById($mensaje){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row = &JTable::getInstance('Inbox', 'Table');
		$row->id = $mensaje;
		$row->load();
		return $row;
	}
	
	function getMensajeOutboxById($mensaje){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row = &JTable::getInstance('Outbox', 'Table');
		$row->id = $mensaje;
		$row->load();
		return $row;
	}
	
	function getContactosByGrupo($grupo){
		$db = JFactory::getDBO();
		$tbContacto = $db->nameQuote('#__zmcontactos');		
		$tbGrupoContacto = $db->nameQuote('#__zmgrupocontacto');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						c.*
				   FROM 
					    $tbContacto c,
						$tbGrupoContacto as g
				   WHERE 
				       c.id = g.contacto AND
						 g.grupo = %s
						";
		$query = sprintf( $query, $grupo);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function eliminarMensajeOutbox($mensaje){
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('outbox');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						status = 'deleted'
					WHERE
						id = %s
						";
		$query = sprintf( $query, $mensaje);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajeOutboxFisico($mensaje){
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('outbox');	
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						status = 'deleted_f'
					WHERE
						id = %s
						";
		$query = sprintf( $query, $mensaje);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajesOutboxSalida(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('outbox');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						status = 'deleted'		
					WHERE
						status in ('ACCEPTED', 'sending') AND
						user = %s
						";
		$query = sprintf( $query, $user->id);
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajesOutboxEnviados(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('outbox');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						status = 'deleted'	
					WHERE
						status in ('sent', 'undelivered', 'delivered') AND
						user = %s
						";
		$query = sprintf( $query, $user->id);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajesOutboxNoEnviados(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('outbox');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						status = 'deleted'
					WHERE
						status in ('notsent')  AND
						user = %s
						";
		$query = sprintf( $query, $user->id);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajesOutboxProgramados(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('outbox');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						status = 'deleted'
					WHERE
						status in ('program')  AND
						user = %s
						";
		$query = sprintf( $query, $user->id);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajesOutboxFisico(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('outbox');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						status = 'deleted_f'
               WHERE
                  status = 'deleted' AND
						user = %s
						";
		$query = sprintf( $query, $user->id);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajeInbox($mensaje){
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('inbox');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						activo = 0		
					WHERE
						id = %s
						";
		$query = sprintf( $query, $mensaje);
		echo $query;
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}
	
	function eliminarMensajesInbox(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbMensajes = $db->nameQuote('inbox');	
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "
					UPDATE
						$tbMensajes
					SET 
						activo = 0		
					WHERE
						username = '%s'
						";
		$query = sprintf( $query, $user->username);			
		$db->setQuery($query);
		$result = $db->query();
		return $result;
	}

	function enviarMensajeExcel(&$nombreArchivo){
		$db = JFactory::getDBO();
		jimport('joomla.filesystem.file');
		$fechaHoy = Configuration::getDate();
		
		//Guarda archivo
		$user = JFactory::getUser();
		$id = $user->id;
		$data = JRequest::getVar('archivo', null, 'files'); 
		$nombre = JFile::makeSafe($data['name']);
		$ext 	=  JFile::getExt($nombre);
		$nombre = JFile::stripExt($nombre);
		if($nombre != ""){
			$nombre = "{$id}_{$nombre}_excel";
			FileHelper::guardarArchivo($data, "images/archivos/", $nombre);
		}
		
		//Abre archivo excel
		$excelFile = ZExcelHelper::openExcel("images/archivos/" . $nombre . ".xlsx" );
		
		ZDBHelper::initTransaction($db);
		$mensajesActuales = $this->getMensajesUsuario($user->id);
		
		//Lee datos
		$fila = 2;
		$mensajes = 0;
		while( $fila == 2 || ($movil != "" || $mensaje != "" ) ){
			$movil   = ZExcelHelper::readExcel($excelFile, $fila, 0);
			$mensaje = ZExcelHelper::readExcel($excelFile, $fila, 1);
			$mensaje = $this->removerCaracteresEspeciales($mensaje);
			$cantidadMensajes = $this->contarMensajes($mensaje, $tam );
			
			if(Mensajes::esNumeroValido( $movil )){
				$this->programarMensajePlataforma($movil, $mensaje, $fechaHoy );
				$mensajes = $mensajes + $cantidadMensajes;
				//echo $mensajes;
				//exit;
				//ZExcelHelper::writeExcel($excelFile, $fila, 2, "OK");
			}
			else{
				//ZExcelHelper::writeExcel($excelFile, $fila, 2, "ERROR");
				
			}
			
			$fila = $fila + 1;
		}
		
		$filas = ($fila - 3);
		//echo "filas =" . $filas ;
		
		if( $mensajesActuales  >= $mensajes || $user->ilimitado == 1){
		
			if( $this->actualizarMensajesUsuario( $user->id, $mensajesActuales - $mensajes ) ){
				ZDBHelper::commit($db);
				$nombreArchivo = $nombre . ".xls";
				ZExcelHelper::saveExcel($excelFile, "images/mensajeslog/" . $nombreArchivo );
				
				//Registra en log
				Log::add("Envio de mensaje", 
						 "Mensajes enviados Excel = " . $filas . " SMS = " );
				return JText::_('M_OK') . sprintf( JText::_('VE_ENVIAR_MENSAJE_EXCEL_OK') , $mensajes );
			}
			else{
				ZDBHelper::rollBack($db);
				return JText::_('M_ERROR'). JText::_('VE_GUARDAR_MENSAJE_ERROR');
			}
		}
		else{
			ZDBHelper::rollBack($db);
			return JText::_('M_ERROR'). sprintf( JText::_('VE_GUARDAR_MENSAJE_ERROR_CREDITOS') , 
															 $mensajes, 
															 $mensajesActuales );
		}
		
		
		
		
	}
	
	function dateTimeDiff($date1, $date2) {

		$alt_diff = new stdClass();
		$alt_diff->y =  floor(abs($date1->format('U') - $date2->format('U')) / (60*60*24*365));
		$alt_diff->m =  floor((floor(abs($date1->format('U') - $date2->format('U')) / (60*60*24)) - ($alt_diff->y * 365))/30);
		$alt_diff->d =  floor(floor(abs($date1->format('U') - $date2->format('U')) / (60*60*24)) - ($alt_diff->y * 365) - ($alt_diff->m * 30));
		$alt_diff->h =  floor( floor(abs($date1->format('U') - $date2->format('U')) / (60*60)) - ($alt_diff->y * 365*24) - ($alt_diff->m * 30 * 24 )  - ($alt_diff->d * 24) );
		$alt_diff->i = floor( floor(abs($date1->format('U') - $date2->format('U')) / (60)) - ($alt_diff->y * 365*24*60) - ($alt_diff->m * 30 * 24 *60)  - ($alt_diff->d * 24 * 60) -  ($alt_diff->h * 60) );
		$alt_diff->s =  floor( floor(abs($date1->format('U') - $date2->format('U'))) - ($alt_diff->y * 365*24*60*60) - ($alt_diff->m * 30 * 24 *60*60)  - ($alt_diff->d * 24 * 60*60) -  ($alt_diff->h * 60*60) -  ($alt_diff->i * 60) );
		$alt_diff->invert =  (($date1->format('U') - $date2->format('U')) > 0)? 0 : 1 ;

		return $alt_diff;
}    
	
}







