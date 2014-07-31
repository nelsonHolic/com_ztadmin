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
		
class ModelVendedor extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	//Mensajes
	function listarMensajesBandejaSalida($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		$query = "SELECT 
						*
				  FROM 
						$tbMensajes m
				  WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('ACCEPTED', 'sending') 
				  ORDER BY
						m.acceptedfordeliverytime DESC
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarMensajesBandejaSalida($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMensajes m
				 WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('ACCEPTED', 'sending') 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro, $fechaHoy);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	
	function listarMensajesEnviados($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		$query = "SELECT 
						*
				  FROM 
						$tbMensajes m
				  WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('sent', 'undelivered', 'delivered') 
						 
				  ORDER BY
						id DESC
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarMensajesEnviados($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMensajes m
				 WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('sent', 'undelivered', 'delivered')
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function listarMensajesNoEnviados($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		$query = "SELECT 
						*
				  FROM 
						$tbMensajes m
				  WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('notsent')
				  ORDER BY
						m.acceptedfordeliverytime DESC
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarMensajesNoEnviados($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMensajes m
				 WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('notsent')
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	//Mensajes Programados
	function listarMensajesProgramados($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');			
		$fechaHoy = Configuration::getDate();		
		
		$query = "SELECT 
						*
				  FROM 
						$tbMensajes m
				  WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('program')
				  ORDER BY
						m.acceptedfordeliverytime DESC
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarMensajesProgramados($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMensajes m
				 WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('program')
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro, $fechaHoy);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function listarMensajesEliminados($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		$query = "SELECT 
						*
				  FROM 
						$tbMensajes m
				  WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('deleted') 
				  ORDER BY
						m.acceptedfordeliverytime DESC
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarMensajesEliminados($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('outbox');		
		$fechaHoy = Configuration::getDate();		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMensajes m
				 WHERE 
						 m.user = %s AND
						(m.msgsubject like '%s' OR
						 m.receiver like '%s' OR
						 m.msgdata like '%s' ) AND
						 m.status in ('deleted') 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro, $fechaHoy);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	

	//Distribucion de mensajes
	function listarMensajesVendedores($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');			
		
		//$user->id = 42;
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios u
				  WHERE 
						u.parent = %s AND
						( u.name like '%s' OR
						  u.username like '%s' OR
						  u.email like '%s' )
				  ORDER BY
						u.username 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	//Distribucion de mensajes
	function contarMensajesVendedores($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');			
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbUsuarios u
				  WHERE 
						u.parent = %s AND
						( u.name like '%s' OR
						  u.username like '%s' OR
						  u.email like '%s' )
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	//Mensajes
	function listarMensajesRecibidos($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('inbox');				
		$query = "SELECT 
						*
				  FROM 
						$tbMensajes m
				  WHERE 
						 activo = 1 AND
						 username = '%s'
				  ORDER BY
						m.receivedtime DESC
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->username );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	//Mensajes
	function contarMensajesRecibidos($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbMensajes = $db->nameQuote('inbox');				
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMensajes m
				  WHERE 
						activo = 1 AND
						username = '%s'
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->username );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	//Contactos
	function listarContactos($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbContactos = $db->nameQuote('#__zmcontactos');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbContactos c
				  WHERE 
						c.usuario = %s AND
						( upper(c.nombre) like upper('%s') OR
						  upper(c.movil) like upper('%s') OR
						  upper(c.correo) like upper('%s') )
				  ORDER BY
						c.id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		//echo $query;
		//echo "inicio = " . $inicio . " registros = " . $registros;
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		//print_r($result);
		//exit;
		return $result;
	}
	
	function contarContactos($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbContactos = $db->nameQuote('#__zmcontactos');			
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbContactos c
				  WHERE 
						c.usuario = %s AND
						(c.nombre like '%s' OR
						 c.movil like '%s' OR
						 c.correo like '%s' )
				  ORDER BY
						c.id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getContacto($id){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbContactos = $db->nameQuote('#__zmcontactos');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbContactos c
				  WHERE 
						c.id = %s
						";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	function getContactos(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbContactos = $db->nameQuote('#__zmcontactos');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbContactos c
				  WHERE 
						c.usuario = %s 
				  ORDER BY
						c.id 
						";
						
		$query = sprintf( $query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function eliminarContacto($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Contactos', 'Table');
		
		$row->id = $id;
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('AD_RETIRAR_CONTACTO_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_RETIRAR_CONTACTO_ERROR');
		}
	}
	
	function eliminarTodosContactos(){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbContactos = $db->nameQuote('#__zmcontactos');		
	
		$query = "
					DELETE FROM 
						$tbContactos
					WHERE
						usuario = %s
						";
		$query = sprintf( $query, $user->id);			
		$db->setQuery($query);
		$result = $db->query();
		
		if( $result ){
			return JText::_('M_OK') . sprintf( JText::_('VE_ELIMINAR_CONTACTOS_TODOS_OK') );
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_ELIMINAR_CONTACTOS_TODOS_ERROR');
		}
	}
	
	
	function guardarContacto(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row   =& JTable::getInstance('Contactos', 'Table');
		$user  = JFactory::getUser();
		$id    = JRequest::getVar('id');
		$movil = JRequest::getVar('movil');
		$movil = Mensajes::limpiarNumero($movil);
		
		if( Mensajes::esNumeroValido($movil) ){
			if( ( isset($id) && $id > 0 ) || !Mensajes::existeContacto($movil) ){
				if($row->bind(JRequest::get('post'))){
					date_default_timezone_set('America/Bogota');
					$fechaHoy = date('Y-m-d H:i:s');
					$row->fecha_creacion = $fechaHoy;
					$row->usuario= $user->id;
					$row->movil = $movil;
					
					if($row->store()){
						return JText::_('M_OK') . sprintf( JText::_('VE_GUARDAR_CONTACTO_OK') , $row->id );
					}
					else{
						return JText::_('M_ERROR'). JText::_('VE_GUARDAR_CONTACTO_ERROR');
					}
				}
			}
			else{
				return JText::_('M_ERROR'). JText::_('VE_GUARDAR_CONTACTO_ERROR_REPETIDO');
			}
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_GUARDAR_CONTACTO_ERROR_NUMERO');
		}
	
		
	}
	
	function listarGrupos($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbGrupos = $db->nameQuote('#__zmgrupos');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbGrupos g
				  WHERE 
						g.usuario = %s  AND
						(g.id like '%s' OR
						 g.descripcion like '%s')
				  ORDER BY
						g.id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id,  $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		foreach($result as $item){
			$item->contactos = $this->contarGrupoContactos("",$item->id);
		}
		return $result;
	}
	
	function contarGrupos($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbGrupos = $db->nameQuote('#__zmgrupos');			
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbGrupos g
				  WHERE 
						g.usuario = %s  AND
						(g.id like '%s' OR
						 g.descripcion like '%s'  )
				  ORDER BY
						g.id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getGrupo($id){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbGrupos = $db->nameQuote('#__zmgrupos');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbGrupos g
				  WHERE 
						g.id = %s 
						";
						
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	function getGrupos(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbGrupos = $db->nameQuote('#__zmgrupos');			
		
		$query = "SELECT 
						*
				  FROM 
						$tbGrupos g
				  WHERE
						g.usuario = %s  
				  ORDER BY
						descripcion 
						";
						
		$query = sprintf($query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	function guardarGrupo(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Grupos', 'Table');
		$user = JFactory::getUser();		
		
		if($row->bind(JRequest::get('post'))){
			$row->usuario= $user->id;
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('VE_GUARDAR_GRUPO_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('VE_GUARDAR_GRUPO_ERROR');
			}
		
		}
		
	}
	
	function eliminarGrupo($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Grupos', 'Table');
		$row->id = $id;
		
		if($row->delete()){
			//Borrar contactos del grupo
			$this->eliminarContactosGrupo($id);
			return JText::_('M_OK') . sprintf( JText::_('VE_ELIMINAR_GRUPO_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_ELIMINAR_GRUPO_ERROR');
		}
	}
	
	function eliminarContactosGrupo($id){
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$tbContactos = $db->nameQuote('#__zmgrupocontacto');		
	
		$query = "
					DELETE FROM 
						$tbContactos
					WHERE
						grupo = %s
						";
		$query = sprintf( $query, $id);			
		$db->setQuery($query);
		$result = $db->query();
		
		if( $result ){
			return JText::_('M_OK') . sprintf( JText::_('VE_ELIMINAR_CONTACTOS_GRUPO_OK') );
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_ELIMINAR_CONTACTOS_GRUPO_ERROR');
		}
	}
	
	
	function listarGrupoContactos($filtro, $inicio, $registros, $grupo){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbContactos = $db->nameQuote('#__zmcontactos');
		$tbGrupoContactos = $db->nameQuote('#__zmgrupocontacto');	

		$query = "SELECT 
						*, g.id as id
				  FROM 
						$tbContactos c,
						$tbGrupoContactos g
				  WHERE 
						c.usuario = %s AND
						g.contacto = c.id AND
						g.grupo= %s AND
						(c.nombre like '%s' OR
						 c.movil like '%s' OR
						 c.correo like '%s' )
				  ORDER BY
						g.id 
						";
		
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id,$grupo, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarGrupoContactos($filtro,$grupo){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbContactos = $db->nameQuote('#__zmcontactos');
		$tbGrupoContactos = $db->nameQuote('#__zmgrupocontacto');	
		

		$query = "SELECT 
						count(*)
				  FROM 
						$tbContactos c,
						$tbGrupoContactos g
				  WHERE 
						c.usuario = %s AND
						g.contacto = c.id AND
						g.grupo= %s AND
						(c.nombre like '%s' OR
						 c.movil like '%s' OR
						 c.correo like '%s' )
						";
		
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id,$grupo, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function guardarGrupoContactos(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$user = JFactory::getUser();		
		
		$grupo = JRequest::getVar('grupo');
		$contactos = JRequest::getVar('contactos');
		$contactos = explode(",", $contactos);
		
		
		foreach($contactos as $contacto){
			$row =& JTable::getInstance('GrupoContacto', 'Table');
			$row->grupo = $grupo;
			$row->contacto = $contacto;
			
			if($row->store()){
			}
			else{
				return JText::_('M_ERROR'). JText::_('VE_AGREGAR_CONTACTOS_GRUPO_ERROR');
			}	
		}
		
		return JText::_('M_OK') . sprintf( JText::_('VE_AGREGAR_CONTACTOS_GRUPO_OK') , $row->id );
	}
	
	function eliminarContactoGrupo($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('GrupoContacto', 'Table');
		
		$row->id = $id;
		
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('VE_RETIRAR_CONTACTO_GRUPO_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_RETIRAR_CONTACTO_GRUPO_ERROR');
		}
	}
	
	
	//Usuarios
	function listarUsuarios($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios as u
				  WHERE 
						u.parent = %s  AND
						(u.name like '%s' OR
						 u.username like '%s' OR
						 u.email like '%s')
				  ORDER BY
						u.id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id,$filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
		
		
		
	function contarUsuarios($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');
		$tbTroncales = $db->nameQuote('#__ztroncales');			
		
		$query = "SELECT
						count(*)
				   FROM 
						$tbUsuarios as u
				  WHERE 
						u.parent = %s  AND
						(u.name like '%s' OR
						 u.username like '%s' OR
						 u.email like '%s')
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $user->id, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function bloquearUsuario($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			$row->id = $id;
			$row->block = 1;
			
			if($row->store()){
				$data->user = $row->id;
				return JText::_('M_OK') . sprintf( JText::_('VE_BLOQUEAR_USUARIO_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('VE_BLOQUEAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function desbloquearUsuario($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			$row->id = $id;
			$row->block = 0;
			
			if($row->store()){
				$data->user = $row->id;
				return JText::_('M_OK') . sprintf( JText::_('VE_DESBLOQUEAR_USUARIO_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('VE_DESBLOQUEAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function getUsuarios(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios as u
				  WHERE
						u.parent =%s
				  ORDER BY
						u.id 
						";
						
		$query = sprintf( $query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getPaquetesMensajes(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbPaquetes = $db->nameQuote('#__zmpaquetesmensajes');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbPaquetes as p
				  ORDER BY
						p.cantidad
						";
						
		$query = sprintf( $query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
		
	
	function getUsuario($id){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');	
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios as u
				  WHERE 
						u.id = %s 
						";
						
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	function asignarMensajes(){
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$user = JFactory::getUser();
		$row =& JTable::getInstance('User', 'Table');
		$cantidad = JRequest::getVar('cantidad');
		
		if( is_numeric($cantidad) ){
		
			//Verifica que el vendedor tenga la cantidad suficiente de mensajes
			$user = JFactory::getUser();
			$row->id = $user->id;
			$row->load();
			$row->mensajes = $row->mensajes - $cantidad;
		    echo "mensajes vendedor = " . $row->mensajes;
			if($row->mensajes >= 0){
				
				ZDBHelper::initTransaction($db);
				
				if($row->store()){
					$id = JRequest::getVar('id');
					$row->id = $id;
					$row->load();
					if( $row->mensajes + $cantidad >= 0 ){
						$row->mensajes = $row->mensajes + $cantidad;
						if($row->store()){
							ZDBHelper::commit($db);
							Log::add("Asignacion de puntos", $cantidad, "", $id);
							return JText::_('M_OK') . sprintf( JText::_('VE_ASIGNAR_MENSAJES_OK') , $cantidad );
						}
						else{
							ZDBHelper::rollBack($db);
							return JText::_('M_ERROR'). JText::_('VE_ASIGNAR_MENSAJES_ERROR');
						}
					}
					else{
						ZDBHelper::rollBack($db);
						return JText::_('M_ERROR'). JText::_('VE_ASIGNAR_MENSAJES_ERROR');
					}
					
				}
				else{
					ZDBHelper::rollBack($db);
					return JText::_('M_ERROR'). JText::_('VE_ASIGNAR_MENSAJES_ERROR');
				}
				//Rollback
			}
			else{
				return JText::_('M_ERROR'). JText::_('VE_ASIGNAR_MENSAJES_ERROR');
			}
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_ASIGNAR_MENSAJES_ERROR_VALOR_MENSAJE');
		}
		
		
	}

	function cambiarClaveUsuario(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$user = JFactory::getUser();
		$row =& JTable::getInstance('User', 'Table');
		$clave1 = JRequest::getVar('clave1');
		$clave2 = JRequest::getVar('clave2');
		$id = JRequest::getVar('id');
		$row->id = $id;
		$row->load();
		
		if($clave1 == $clave2){
				$row->password = md5($clave1);
		}
			
		if($row->store()){
			return JText::_('M_OK') . sprintf( JText::_('VE_CAMBIAR_CLAVE_USUARIO_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_CAMBIAR_CLAVE_USUARIO_ERROR');
		}
		
		
	}

	
	function guardarUser(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$row =& JTable::getInstance('User', 'Table');
		
		$id = JRequest::getVar('id');
		$clave1 = JRequest::getVar('clave1');
		$clave2 = JRequest::getVar('clave2');
		$tipo = JRequest::getVar('tipo');
		$username = JRequest::getVar('username');
		
		//print_r( JRequest::get('post') );
		//exit;
		
		if( !$this->existeUsuario($username) || $id > 0) {
				
			if($row->bind(JRequest::get('post'))){
				date_default_timezone_set('America/Bogota');
				$fechaHoy = date('Y-m-d H:i:s');
				$row->registerDate = $fechaHoy;
				
				if($clave1 == $clave2){
					if($clave1 != ""){
						$row->password = md5($clave1);
					}
				}
				
				$row->params= '{"admin_style":"","admin_language":"","language":"","editor":"","helpsite":"","timezone":""}';
				$row->parent= $user->id;
				
				if($row->store()){
					
					if($id == ""){
						$tbGrupo = $db->nameQuote('#__user_usergroup_map');
						$query = "INSERT INTO $tbGrupo(user_id, group_id) VALUES({$row->id}, 2 )";
						$db->setQuery($query);
						$result= $db->query();
						if($result){
							return JText::_('M_OK') . sprintf( JText::_('VE_GUARDAR_USUARIO_OK') , $row->id );
						}
					}
					else{
						return JText::_('M_OK') . sprintf( JText::_('VE_GUARDAR_USUARIO_OK') , $row->id );
					}
				}
				else{
					return JText::_('M_ERROR'). JText::_('VE_GUARDAR_USUARIO_ERROR');
				}
			
			}
		}
		else{
				return JText::_('M_ERROR'). JText::_('VE_GUARDAR_USUARIO_ERROR_EXISTE');
		}
	}
	
	function existeUsuario($username){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__users');		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbUsuarios u
				  WHERE
						username  = '%s'
						";
		$query = sprintf($query, $username);
		$db->setQuery($query);
	    $result = $db->loadResult();
		$result = ($result > 0 ) ? true : false;
		return $result;
	}

	
	function getTiposUsuarios(){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuarios = $db->nameQuote('#__zmtiposusuario');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios u
				  ORDER BY
						u.id 
						";
		$query = sprintf($query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function eliminarUsuario($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		$row->id = $id;
		
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('VE_RETIRAR_USUARIO_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_RETIRAR_USUARIO_ERROR');
		}
	}
	
	
	
	
}







