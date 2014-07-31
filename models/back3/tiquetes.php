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
		
class ModelTiquetes extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	/**
	* Lista de tiquetes
	*/
	function listar($filtro, $usuario, $estado, $inicio, $registros){	
		$db = JFactory::getDBO();
		$tbTiquetes = $db->nameQuote('#__ztiquetes');		
		$whereUsuario = ($usuario > 0) ?  " AND usuario_asignado = $usuario " : "";
		$whereEstado = ($estado > 0) ?  " AND estado = $estado " : "";
		
		$query = "SELECT 
						*
				  FROM 
						$tbTiquetes as tiquetes
				  WHERE 
						 resumen like '%s'
						 $whereUsuario
						 $whereEstado
				  ORDER BY
						id  
						";
		$query = sprintf( $query, "%". $filtro . "%");
		echo $query;
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		foreach($result as $item){
			$item->usuario_asignado = ($item->usuario_asignado > 0 ) ? $this->getUsuario($item->usuario_asignado) : "";
			$item->usuario_creacion = ($item->usuario_creacion > 0 ) ? $this->getUsuario($item->usuario_creacion) : "";
			$item->estado = $this->getEstado($item->estado);
		}
		return $result;
	}
	
	function contar($filtro){
		$db = JFactory::getDBO();
		$tbTiquetes = $db->nameQuote('#__ztiquetes');
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbTiquetes as tiquetes
				  WHERE 
						 resumen like '%s'";
						 
		$query = sprintf( $query, "%".$filtro."%" );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	/**
	* Lista de tiquetes
	*/
	function listarTodos($filtro, $usuario, $estado){	
		$db = JFactory::getDBO();
		$tbTiquetes = $db->nameQuote('#__ztiquetes');		
		$tbEstados = $db->nameQuote('#__zestados');		
		$tbPrioridades = $db->nameQuote('#__zprioridades');		
		$tbProyectos = $db->nameQuote('#__zproyectos');		
		
		$whereUsuario = ($usuario > 0) ?  " AND usuario_asignado = $usuario " : "";
		$whereEstado = ($estado > 0) ?  " AND estado = $estado " : "";
		
		$query = "SELECT 
						t.id, t.resumen, t.descripcion, t.usuario_asignado, 
						e.descripcion as estado, p.descripcion as prioridad, 
						py.nombre, 
						t.fecha_creacion, t.fecha_cierre
				  FROM 
						$tbTiquetes as t,
						$tbEstados as e,
						$tbPrioridades as p,
						$tbProyectos as py
				  WHERE 
						 resumen like '%s' AND
						 t.estado = e.id AND
						 t.prioridad = p.id AND
						 t.proyecto = py.id 
						 $whereUsuario
						 $whereEstado
				  ORDER BY
						id  
						";
		$query = sprintf( $query, "%". $filtro . "%");
		$db->setQuery($query);
		//echo $query;
	    $result = $db->loadObjectList();
		//print_r($db);
		//exit;
		foreach($result as $item){
			$item->usuario_asignado = ($item->usuario_asignado > 0 ) ? $this->getUsuario($item->usuario_asignado) : "";
			//$item->estado = $this->getEstado($item->estado);
		}
		return $result;
	}
	
	function getTiquete($id){
	
		$db = JFactory::getDBO();
		$tbTiquetes 	= $db->nameQuote('#__ztiquetes');		
		$tbEstados 		= $db->nameQuote('#__zestados');		
		$tbPrioridades 	= $db->nameQuote('#__zprioridades');		
		$tbComplejidades= $db->nameQuote('#__zcomplejidades');		
		$tbProyectos 	= $db->nameQuote('#__zproyectos');		
		$tbUsuarioAsig 	= $db->nameQuote('#__users');		
		
		$query = "SELECT 
						t.id, 
						t.resumen, 
						t.descripcion, 
						t.usuario_asignado, 
						e.descripcion as estado, 
						p.descripcion as prioridad, 
						py.nombre, 
						t.fecha_creacion, 
						t.fecha_cierre,
						t.usuario_asignado,
						ua.username as usuarioCreacion,
						c.descripcion as complejidad
				  FROM 
						$tbTiquetes as t,
						$tbEstados as e,
						$tbPrioridades as p,
						$tbComplejidades as c,
						$tbProyectos as py,
						$tbUsuarioAsig as ua
				  WHERE 
						 t.estado = e.id AND
						 t.prioridad = p.id AND
						 t.proyecto = py.id AND
						 t.complejidad = c.id AND
						 t.usuario_creacion = ua.id AND
						 t.id = %s
						";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
		echo $query;
	    $result = $db->loadObject();
		$result->usuarioAsignado = ($result->usuario_asignado > 0) ?  $this->getUsuario($result->usuario_asignado) : "";
		$result->adjuntos = $this->getAdjuntos($id);
		$result->historial = $this->getHistorial($id);
		return $result;
	}
	
	function getUsuario($id){
		$db = JFactory::getDBO();
		$tbUsuarios = $db->nameQuote('#__users');
		
		$query = "SELECT 
						username
				  FROM 
						$tbUsuarios as u
				  WHERE 
						 id = %s";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getEstado($id){
		$db = JFactory::getDBO();
		$tbEstados = $db->nameQuote('#__zestados');
		
		$query = "SELECT 
						descripcion
				  FROM 
						$tbEstados as e
				  WHERE 
						 id = %s";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		$result = ($result == "Nuevo") ? "<span class='badge'>$result</span>" : $result;
		$result = ($result == "Aceptado") ? "<span class='label label-warning'>$result</span>" : $result;
		$result = ($result == "Corregido") ? "<span class='label label-success'>$result</span>" : $result;
		$result = ($result == "Inválido") ? "<span class='label label-important'>$result</span>" : $result;
		$result = ($result == "En pruebas") ? "<span class='label label-info'>$result</span>" : $result;
		
		return $result;
	}

	
	function getPrioridades(){
		$db = JFactory::getDBO();
		$tbPrioridades = $db->nameQuote('#__zprioridades');
		
		$query = "SELECT 
						*
				  FROM 
						$tbPrioridades
				  ORDER BY
						id DESC
						";
		
		//$query = sprintf( $query, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getProyectos(){
		$db = JFactory::getDBO();
		$tbProyectos = $db->nameQuote('#__zproyectos');
		
		$query = "SELECT 
						*
				  FROM 
						$tbProyectos
				  ORDER BY
						nombre
						";
		
		//$query = sprintf( $query, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function guardarTiquete(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Tiquete', 'Table');
		
		//Valida datos obligatorios
		
		//Guarda datos
		if($row->bind(JRequest::get('post')))
		{
			date_default_timezone_set('America/Bogota');
			$fechaHoy = date('Y-m-d H:i:s');

			$user = JFactory::getUser();
			$row->usuario_creacion = $user->id;
			$row->estado = Constantes::TIQUETE_NUEVO;
			$row->complejidad = Constantes::COMPLEJIDAD_MEDIA;
			$row->fecha_creacion = $fechaHoy;
			
			if($row->store())
			{
				$this->guardarArchivosAdjuntos($row->id);
				$this->guardarHistorialCreacion($row->id);
				return JText::_('M_OK') . sprintf( JText::_('TIQUETES_MSG_GUARDAR') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('TIQUETES_MSG_GUARDAR');
			}
			
		}
		else{
			return JText::_('M_ERROR'). JText::_('TIQUETES_ERROR_GUARDAR');
		}
	}
	
	public function guardarArchivosAdjuntos($id){
		jimport('joomla.filesystem.file');
		$user = JFactory::getUser();
		$fechaHoy = date('Y-m-d H:i:s');
		
		//Guarda adjunto 1
		$data = JRequest::getVar('file1', null, 'files'); 
		$nombre = JFile::makeSafe($data['name']);
		$nombre = JFile::stripExt($nombre);
		$ext =  JFile::getExt($data['name']);
		if($nombre != ""){
			FileHelper::guardarArchivo($data, "images/adjuntos/", "{$id}_{$nombre}");
			$row =& JTable::getInstance('TiqueteAdjunto', 'Table');
			$row->tiquete = $id;
			$row->descripcion = $nombre;
			$row->ruta = "images/adjuntos/" . "{$id}_{$nombre}.{$ext}";
			$row->usuario = $user->id;
			$row->fecha = $fechaHoy;
			$row->store();
		}
		
		//Guarda adjunto 2
		$data = JRequest::getVar('file2', null, 'files'); 
		$nombre = JFile::makeSafe($data['name']);
		$nombre = JFile::stripExt($data['name']);
		$ext =  JFile::getExt($data['name']);
		if($nombre != ""){
			FileHelper::guardarArchivo($data, "images/adjuntos/", "{$id}_{$nombre}");
			$row =& JTable::getInstance('TiqueteAdjunto', 'Table');
			$row->tiquete = $id;
			$row->descripcion = $nombre;
			$row->ruta = "images/adjuntos/" . "{$id}_{$nombre}.{$ext}";
			$row->usuario = $user->id;
			$row->fecha = $fechaHoy;
			$row->store();
		}
		
		return true;
	}
	
	
	function getAdjuntos($id){
		$db = JFactory::getDBO();
		$tbAdjuntos = $db->nameQuote('#__ztiquetes_adjuntos');
		
		$query = "SELECT 
						*
				  FROM 
						$tbAdjuntos 
				  WHERE 
						tiquete = %s";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getHistorial($id){
		$db = JFactory::getDBO();
		$tbHistorial = $db->nameQuote('#__ztiquetes_historial');
		$tbTiposCambio = $db->nameQuote('#__ztipos_cambio');
		$tbUsuarios = $db->nameQuote('#__users');
		
		$query = "SELECT 
						*
				  FROM 
						$tbHistorial as h,
						$tbTiposCambio as c,
						$tbUsuarios as u
				  WHERE 
						h.tipo_cambio = c.codigo AND
						h.usuario = u.id AND
						tiquete = %s 
				  ORDER BY h.id DESC
				 ";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		foreach($result as $item){
			if($item->tipo_cambio == Constantes::CAMBIO_ESTADO){
				$item->estado_anterior = TiquetesHelper::getEstado($item->estado_anterior);
				$item->estado_nuevo = TiquetesHelper::getEstado($item->estado_nuevo);
			}
		}
		return $result;
	}
	
	
	public function guardarHistorialCreacion($id){
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');
			
		$row =& JTable::getInstance('TiqueteHistorial', 'Table');
		$row->tiquete = $id;
		$row->fecha = $fechaHoy;
		$row->tipo_cambio = 'A';
		$row->store();
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
	
	
}










