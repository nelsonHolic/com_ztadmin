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
		
class ModelContacto extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function listar($filtro, $inicio, $registros){
		$db = Configuration::getTiendaDB();
		$tbContactos = $db->nameQuote('#__zcontactos');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		
		$query = "SELECT 
						*
				  FROM 
						$tbContactos
				  WHERE
						nombre like '%s' OR
						correo like '%s'
				  ORDER BY
						nombre
						";
		
		$query = sprintf( $query, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro){
		$db = Configuration::getTiendaDB();
		$tbContactos = $db->nameQuote('#__zcontactos');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbContactos
				  WHERE
						nombre like '%s' OR
						correo like '%s'
						";
		$query = sprintf( $query, $filtro, $filtro );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getContacto($id){
		$db = Configuration::getTiendaDB();
		$tbContactos = $db->nameQuote('#__zcontactos');
		$query = "SELECT 
						*
				  FROM 
						$tbContactos
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	public function guardarContacto($id, $nombre, $titulo, $correo){
		$db = Configuration::getTiendaDB();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =&JTable::getInstance('Contacto', 'Table');
		if($id > 0 ){
			$row->id = $id;
		}
		$row->nombre = $nombre;
		$row->titulo = $titulo;
		$row->correo = $correo;

		if($row->store()){
			return JText::_('M_OK'). JText::_('CONTACTOS_MSG_GUARDAR');
		}
		else{
		
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}
	
	
	
	public function eliminar( $id ){
	
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =&JTable::getInstance('Contacto', 'Table');
		
		$row->id = $id;
		$result = $row->delete();

		if($result){
			return JText::_('M_OK'). JText::_('CONTACTOS_ELIMINAR_MSG');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}
	
	function listarMensajes($filtro, $estado, $inicio, $registros){
		$db = Configuration::getTiendaDB();
		$tbContactos = $db->nameQuote('#__zcontactos');
		$tbMensajes = $db->nameQuote('#__zcontactos_mensajes');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		$estado = ($estado != "" ) ? " estado = '$estado' AND " : "";
		
		$query = "SELECT 
						mensajes.*, contactos.nombre as nombreContacto
				  FROM 
						$tbContactos contactos,
						$tbMensajes mensajes
				  WHERE
						mensajes.contacto = contactos.id AND
						$estado
						(
						mensajes.nombre like '%s' OR
						mensajes.telefono like '%s' OR
						mensajes.correo like '%s' OR
						asunto like '%s' OR
						mensaje like '%s' 
						)
				  ORDER BY
						id desc
						";
		
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarMensajes($filtro, $estado){
		$db = Configuration::getTiendaDB();
		$tbMensajes = $db->nameQuote('#__zcontactos_mensajes');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		$estado = ($estado != "" ) ? " estado = '$estado' AND " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMensajes
				  WHERE
						$estado
						(
						nombre like '%s' OR
						telefono like '%s' OR
						correo like '%s' OR
						asunto like '%s' OR
						mensaje like '%s' 
						)
						";
		$query = sprintf( $query,$filtro, $filtro, $filtro, $filtro, $filtro );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	public function guardarMensaje($id, $estado, $observaciones){
		$db = Configuration::getTiendaDB();
		$tbMensajes = $db->nameQuote('#__zcontactos_mensajes');
		
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');

		$fechaRespuesta = ($estado == "A") ? $fechaHoy : "";
		$estado = mysql_real_escape_string($estado);
		$observaciones = mysql_real_escape_string($observaciones);
		$query = "
			  UPDATE
					$tbMensajes
			  SET 
					observaciones = '%s',
					estado = '%s',
					fecha_respuesta = '%s'
			  WHERE 
					id = %s
					";
		$query = sprintf( $query, $observaciones, $estado, $fechaRespuesta, $id);
		$db->setQuery($query);
		$result = $db->query();
		
		if($result){
			return JText::_('M_OK'). JText::_('CONTACTOS_MENSAJES_GUARDAR');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}
	
	function getMensaje($id){
		$db = Configuration::getTiendaDB();
		$tbMensajes = $db->nameQuote('#__zcontactos_mensajes');
		$query = "SELECT 
						*
				  FROM 
						$tbMensajes
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
}










