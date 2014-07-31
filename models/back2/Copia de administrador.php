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
		
class ModelAdministrador extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	/**
	* Lista de asesores
	*/
	function listarReferidos($filtro, $grupo, $plataforma, $fechaInicio, $fechaFinal, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbGrupos = $db->nameQuote('#__zgrupos');
		$tbPlataformaGrupo= $db->nameQuote('#__zplataforma_grupo');
		$tbPlataformas= $db->nameQuote('#__zplataformas');
		
		$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		$wherePlataforma  = ($plataforma > 0) ?  " AND p.id= $plataforma " : "";
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";
		
		
		//$whereEstado = ($estado > 0) ?  " AND estado = $estado " : "";
		
		$query = "SELECT 
						r.id, r.cedula, r.nombre, r.fecha_creacion, r.celular, g.descripcion, p.descripcion as  plataforma
				  FROM 
						$tbReferidos as r,
						$tbGrupos as g,
						$tbPlataformas as p,
						$tbPlataformaGrupo as pg
				  WHERE 						
						 r.grupo = g.id AND
						 pg.grupo = r.grupo AND
						 p.id = pg.plataforma AND
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' ) 
						 $whereGrupo
						 $wherePlataforma
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						r.id  
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		echo $query;
		$db->setQuery($query, $inicio, $registros);
		//print_r($db);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarReferidos($filtro, $grupo, $plataforma, $fechaInicio, $fechaFinal){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbGrupos = $db->nameQuote('#__zgrupos');
		$tbPlataformaGrupo= $db->nameQuote('#__zplataforma_grupo');
		$tbPlataformas= $db->nameQuote('#__zplataformas');
		
		$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		$wherePlataforma  = ($plataforma > 0) ?  " AND p.id= $plataforma " : "";
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbReferidos as r,
						$tbGrupos as g,
						$tbPlataformas as p,
						$tbPlataformaGrupo as pg
				  WHERE 						
						 r.grupo = g.id AND
						 pg.grupo = r.grupo AND
						 p.id = pg.plataforma AND
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' ) 
						 $whereGrupo
						 $wherePlataforma
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						r.id  
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/**
	* Lista de tiquetes
	*/
	function listarReferidosTodos($filtro, $grupo, $plataforma, $fechaInicio, $fechaFinal){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbGrupos = $db->nameQuote('#__zgrupos');
		$tbPlataformaGrupo= $db->nameQuote('#__zplataforma_grupo');
		$tbPlataformas= $db->nameQuote('#__zplataformas');
		
		$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		$wherePlataforma  = ($plataforma > 0) ?  " AND p.id= $plataforma " : "";
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";
		
		
		//$whereEstado = ($estado > 0) ?  " AND estado = $estado " : "";
		
		$query = "SELECT 
						r.id, r.cedula, r.nombre, r.fecha_creacion, r.celular, g.descripcion, p.descripcion as  plataforma
				  FROM 
						$tbReferidos as r,
						$tbGrupos as g,
						$tbPlataformas as p,
						$tbPlataformaGrupo as pg
				  WHERE 						
						 r.grupo = g.id AND
						 pg.grupo = r.grupo AND
						 p.id = pg.plataforma AND
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' ) 
						 $whereGrupo
						 $wherePlataforma
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						r.id  
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function listarReferidores($filtro, $grupo, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidores = $db->nameQuote('#__zrefusers');		
		$tbGrupos = $db->nameQuote('#__zgrupos');
		
		$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						r.codigo, r.cedula, r.nombre, r.grupo, r.fecha_creacion, g.descripcion
				  FROM 
						$tbReferidores as r,						
						$tbGrupos as g
				  WHERE 
						 r.grupo = g.id AND
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 $whereGrupo
				  ORDER BY
						codigo 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		echo $query;
		$db->setQuery($query, $inicio, $registros);
		//print_r($db);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function listarReferidoresTodos($filtro, $grupo){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidores = $db->nameQuote('#__zrefusers');		
		$tbGrupos = $db->nameQuote('#__zgrupos');
		
		
		$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						r.codigo, r.cedula, r.nombre, r.grupo, r.fecha_creacion, g.descripcion
				  FROM 
						$tbReferidores as r,						
						$tbGrupos as g
				  WHERE 
						 r.grupo = g.id AND
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 $whereGrupo
				  ORDER BY
						codigo 
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarReferidores($filtro, $grupo){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidores = $db->nameQuote('#__zrefusers');		
		$tbGrupos = $db->nameQuote('#__zgrupos');
		
		$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbReferidores as r,						
						$tbGrupos as g
				  WHERE 
						 r.grupo = g.id AND
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 $whereGrupo
				  ORDER BY
						codigo 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function listarBaseDeDatos($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbBaseDeDatos = $db->nameQuote('#__zcwdatabases');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						r.id, r.db, r.user
				  FROM 
						$tbBaseDeDatos as r	
				  WHERE 
						 (id like '%s' OR
						 db like '%s' OR
						 user like '%s' )
				  ORDER BY
						id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		echo $query;
		$db->setQuery($query, $inicio, $registros);
		//print_r($db);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarBaseDeDatos($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbBaseDeDatos = $db->nameQuote('#__zcwdatabases');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbBaseDeDatos as r,	
				  WHERE 
						 (id like '%s' OR
						 db like '%s' OR
						 user like '%s' )
				  ORDER BY
						codigo 
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
		
	function listarRoles($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRoles = $db->nameQuote('#__zcwroles');		
		//$tbGrupos = $db->nameQuote('#__zgrupos');
		
		
		$query = "SELECT 
						r.id, r.descripcion
				  FROM 
						$tbRoles as r	
				  WHERE 
						(id like '%s' OR
						 descripcion like '%s')
				  ORDER BY
						id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		echo $query;
		$db->setQuery($query, $inicio, $registros);
		//print_r($db);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarRoles($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRoles = $db->nameQuote('#__zcwroles');		
		//$tbGrupos = $db->nameQuote('#__zgrupos');
		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbRoles as r	
				  WHERE 
						(id like '%s' OR
						 descripcion like '%s')
				  ORDER BY
						id 
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function listarReportes($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbBaseDeDatos = $db->nameQuote('#__zcwdatabases');		
		$tbReportes = $db->nameQuote('#__zcwreports');	
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						r.id, r.nombre, r.descripcion, r.query, r.fecha_creacion, r.database, b.db
				  FROM 
						$tbBaseDeDatos as b,
						$tbReportes  as r	
				  WHERE 
						r.database = b.id
				  ORDER BY
						id 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro);
		echo $query;
		$db->setQuery($query, $inicio, $registros);
		//print_r($db);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function guardarReferidor(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Referidores', 'Table');
		
		$grupo = JRequest::getVar("grupo","");
		if( $grupo != "" ){
			if($row->bind(JRequest::get('post'))){
			
				date_default_timezone_set('America/Bogota');
				$fechaHoy = date('Y-m-d H:i:s');
				$row->fecha_creacion = $fechaHoy;
				
				//Enlace OSF codigo referidor
				$resultRefOsf = $this->guardarReferidorOsf( JRequest::get('post') );
				$result = explode("|", $result);
				$row->codigo = $result[1];
				
				if($row->store()){
					
					return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_REFERIDOR_OK') , $row->id );
				}
				else{
					return JText::_('M_ERROR'). JText::_('AD_GUARDAR_REFERIDOR_ERROR');
				}
			}
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_GUARDAR_REFERIDOR_ERROR');
		}
	}
	
	function guardarReferidorOsf( $data ){
		$client = new SoapClient("http://10.4.251.17/etp/ws/referidos.php?wsdl");
		$result = $client->createReferer($data['cedula'], $data['nombre'], $data['telefono'], "" , "");
		return $result;
	}
	
	function guardarBaseDeDatos(){
	
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('BaseDeDatos', 'Table');
		
		if($row->bind(JRequest::get('post'))){
		
			date_default_timezone_set('America/Bogota');
			/*$fechaHoy = date('Y-m-d H:i:s');
			$row->fecha_creacion = $fechaHoy;*/
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_BASE_DE_DATOS_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_BASE_DE_DATOS_ERROR');
			}
		
		}
	}
	
	
	function asignarReportesRol(){
	
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('reportesRoles', 'Table');
		
		if($row->bind(JRequest::get('post'))){
		
			date_default_timezone_set('America/Bogota');
			/*$fechaHoy = date('Y-m-d H:i:s');
			$row->fecha_creacion = $fechaHoy;*/
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_ASIGNAR_REPORTE_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_ASIGNAR_REPORTE_ERROR');
			}
		
		}
	}
	
	
	function asignarUsuarioRol(){
	
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('UsuarioRol', 'Table');
		
		if($row->bind(JRequest::get('post'))){
		
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_ASIGNAR_USUARIO_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_ASIGNAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function asignarUsuarioReporte(){
	
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('UsuarioReporte', 'Table');
		
		if($row->bind(JRequest::get('post'))){
		
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_ASIGNAR_USUARIO_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_ASIGNAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function guardarRol(){
	
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Roles', 'Table');
		
		if($row->bind(JRequest::get('post'))){
		
			date_default_timezone_set('America/Bogota');
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_ROL_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_ROL_ERROR');
			}
		
		}
	
		
	}
	
	function guardarReporte(){
	
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('reportes', 'Table');
		
		if($row->bind(JRequest::get('post'))){
		
			date_default_timezone_set('America/Bogota');
			$fechaHoy = date('Y-m-d H:i:s');
			$row->fecha_creacion = $fechaHoy;
			
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_REPORTE_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_REPORTE_ERROR');
			}
		
		}
	}
	
	
	function getReferido($id){
		$db = JFactory::getDBO();
		$tbRefUsers 	= $db->nameQuote('#__zrefusers');		
		$tbRefRecomends = $db->nameQuote('#__zrefrecomends');	
		$tbEstados = $db->nameQuote('#__zrefestados');			
		
		$query = "SELECT 
						r.*, u.cedula as cedulaReferidor, u.nombre as nombreReferidor, e.descripcion as estadoActual
				  FROM 
						$tbRefUsers as u,
						$tbRefRecomends as r,
						$tbEstados as e
				  WHERE 
						r.referidor = u.codigo AND
						r.estado = e.codigo AND
						r.id = %s
						";
						 
		$query = sprintf( $query, $id );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObject();
		
	
		return $result;
	}
	
	function getHistoricoReferido($id){
		$db = JFactory::getDBO();
		$tbRefObservations = $db->nameQuote('#__zrefobservations');		
		$tbUsers = $db->nameQuote('#__users');		
		
		$query = "SELECT 
						o.*, u.username
				  FROM 
						$tbRefObservations as o,
						$tbUsers as u
				  WHERE 
						o.usuario = u.id AND
						o.referido = %s
				  ORDER BY 
					fecha DESC
						";
						 
		$query = sprintf( $query, $id );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getEstados(){
		$db = JFactory::getDBO();
		$tbRefEstados = $db->nameQuote('#__zrefestados');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbRefEstados as e
				  ORDER BY 
					id
						";
						 
		$query = sprintf( $query );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getGrupos(){
		$db = JFactory::getDBO();
		$tbGrupos = $db->nameQuote('#__zgrupos');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbGrupos as e
				  ORDER BY 
					descripcion
						";
						 
		$query = sprintf( $query );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getPlataformas(){
		$db = JFactory::getDBO();
		$tbPlataformas = $db->nameQuote('#__zplataformas');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbPlataformas as e
				  ORDER BY 
					descripcion
						";
						 
		$query = sprintf( $query );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getReportes(){
		$db = JFactory::getDBO();
		$tbReportes = $db->nameQuote('#__zcwreports');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbReportes as e
				  ORDER BY 
					nombre
						";
						 
		$query = sprintf( $query );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getRoles(){
		$db = JFactory::getDBO();
		$tbRoles = $db->nameQuote('#__zcwroles');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbRoles as e
				  ORDER BY 
					descripcion
						";
						 
		$query = sprintf( $query );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getUsuarios(){
		$db = JFactory::getDBO();
		$tbUsuarios = $db->nameQuote('#__users');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsuarios as u
				  ORDER BY 
					name
						";
						 
		$query = sprintf( $query );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	function getBaseDeDatos(){
		$db = JFactory::getDBO();
		$tbBaseDeDatos = $db->nameQuote('#__zcwdatabases');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbBaseDeDatos as b
				  ORDER BY 
					db
						";
						 
		$query = sprintf( $query );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getBaseDatos($id){
		$db = JFactory::getDBO();
		$tbBaseDeDatos 	= $db->nameQuote('#__zcwdatabases');		
	
		$query = "SELECT 
						*
				  FROM 
						$tbBaseDeDatos as f
				  WHERE 
						
						f.id = %s
						";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	
	function referidoCambiarEstado(){
		//Guardar la observacion
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Observaciones', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			date_default_timezone_set('America/Bogota');
			$fechaHoy = date('Y-m-d H:i:s');
			$row->fecha = $fechaHoy;
			
			$user = JFactory::getUser();
			$row->usuario = $user->id;
			
			if($row->store()){
				//Actualizar estado de referido
				$row = &JTable::getInstance('Referidos', 'Table');
				$row->load(JRequest::getVar('referido'));
				$row->estado = JRequest::getVar('estado');
				if($row->store()){
					return JText::_('M_OK') . sprintf( JText::_('REFERIDOS_MSG_GUARDAR') , $row->id );
				}
				else{
					return JText::_('M_ERROR'). JText::_('TIQUETES_MSG_GUARDAR_ERROR');
				}
			}
			else{
				//ERR
			}

		}
		
	}
	
	function eliminarBaseDeDatos($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('BaseDeDatos', 'Table');
		
		$row->id = $id;
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('AD_ELIMINAR_BASE_DE_DATOS_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_ELIMINAR_BASE_DE_DATOS_ERROR');
		}
	}


	
	
	
	function contarReferidosPorEstado($estado, $plataforma, $fechaInicio, $fechaFin){
		$db = JFactory::getDBO();
		$tbRecomends = $db->nameQuote('#__zrefrecomends');		
		$tbUsers = $db->nameQuote('#__zrefusers');		
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');		
		$tbPlataforma = $db->nameQuote('#__zplataformas');	
		
		$query = "SELECT 
						count(*)
				   FROM 
						$tbRecomends as r,
						$tbUsers as u,
						$tbPlataformaGrupo as pg,
						$tbPlataforma as p
				  WHERE 
						 r.estado in ( %s ) AND
						 r.referidor = u.codigo AND
						 u.grupo = pg.grupo AND
						 pg.plataforma = p.id AND
						 p.id = %s AND
						 r.fecha_creacion >= '%s' AND
						 r.fecha_creacion <= '%s' 
						";
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
		
	}
	
	function contarReferidosActivosPorPlataforma($plataforma){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRecomends = $db->nameQuote('#__zrefrecomends');		
		$tbUsers = $db->nameQuote('#__zrefusers');		
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');		
		$tbPlataforma = $db->nameQuote('#__zplataformas');	
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						count(*)
				   FROM 
						$tbRecomends as r,
						$tbUsers as u,
						$tbPlataformaGrupo as pg,
						$tbPlataforma as p
				  WHERE 
						 r.estado in ('N','P') AND
						 r.referidor = u.codigo AND
						 u.grupo = pg.grupo AND
						 pg.plataforma = p.id AND
						 p.id = %s
				  
						";
						
		$query = sprintf( $query, $plataforma);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function listarFaq($filtro, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbFaq = $db->nameQuote('#__zfaq');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						id, pregunta, titulo, contenido
				  FROM 
						$tbFaq as f
				  WHERE 
						 f.pregunta like '%s' OR
						 f.titulo like '%s'  
				  ORDER BY
						f.id
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro);
		echo $query;
		$db->setQuery($query, $inicio, $registros);
		//print_r($db);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarFaq($filtro){	
	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbFaq = $db->nameQuote('#__zfaq');		
		
		//$whereGrupo 	  = ($grupo > 0) ?       " AND r.grupo= $grupo " : "";
		
		$query = "SELECT 
						count(*)
				   FROM 
						$tbFaq as f
				  WHERE 
						 f.pregunta like '%s' OR
						 f.titulo like '%s'
				  ORDER BY
						codigo 
						";
						
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getFaq($id){
		$db = JFactory::getDBO();
		$tbFaq 	= $db->nameQuote('#__zfaq');		
	
		$query = "SELECT 
						*
				  FROM 
						$tbFaq as f
				  WHERE 
						
						f.id = %s
						";
						 
		$query = sprintf( $query, $id );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}

	function guardarFaq(){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Faq', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			$contenido 	= JRequest::getVar('contenido', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$row->contenido = $contenido;
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_FAQ_OK') , $row->id );
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_FAQ_ERROR');
			}
		
		}
	}
	
	function eliminarFaq($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Faq', 'Table');
		
		$row->id = $id;
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('AD_ELIMINAR_FAQ_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_ELIMINAR_FAQ_ERROR');
		}
		
		
	}
}










