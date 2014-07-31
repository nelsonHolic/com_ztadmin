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
		
class ModelPlataforma extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	* Funci�n que devuelve la lista de todos los referidos que 
	* se no tienen asesor asignado
	*/	
	function listarReferidosSinAsignar($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$query = "SELECT 
						r.*
				  FROM 
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r  
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = 0
				  ORDER BY
						r.fecha_creacion  
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Funci�n que devuelve un listado de asesores que pertenecen 
	* a la plataforma del grupo al que pertenece el referido
	*/
	function getAsesoresPlataforma(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaAsesores = $db->nameQuote('#__zplataforma_asesores');
		$tbAsesores = $db->nameQuote('#__users');
		
		$query = "SELECT 
						u.* 
				   FROM 
						$tbAsesores u,
						$tbPlataforma p, 
						$tbPlataformaAsesores pa
				   WHERE 
						p.usuario = %s
						AND p.id = pa.plataforma
						AND pa.usuario = u.id 
					ORDER BY 
						u.username
						";
						
		$query = sprintf($query, $user->id );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n que devuelve los datos de los referidos sin asignar 
	* para exportarlos a excel
	*/
	function listarReferidosSinAsignarExcel($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$query = "SELECT 
						r.id, r.cedula, r.nombre, r.telefono, r.celular, r.municipio
				  FROM 
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r  
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = 0
				  ORDER BY
						r.fecha_creacion  
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Funci�n que devuelve la cantidad de referidos sin asignar 
	*/
	function contarReferidosSinAsignar($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r  
				  WHERE 
						 (nombre like '%s' OR						 
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = 0
				  ORDER BY
						r.fecha_creacion  
						"; 
		
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/*
	* Funci�n asigna un referido a un asesor
	*/
	function asignarReferido($referidoId, $asesorId){
		//Guardar la observacion
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Referidos', 'Table');
		$row->id = $referidoId;
		$row->asesor = $asesorId;
		if($row->store()){
			//$this->enviarCorreoAsignacion($referidoId,$asesorId);
			$this->agregarObservacionAsignacion($referidoId, $asesorId, "S");
			return JText::_('M_OK') . sprintf( JText::_('PL_ASIGNAR_REFERIDO_OK') , $row->id );
		}else{
			return JText::_('M_ERROR'). JText::_('PL_ASIGNAR_REFERIDO_ERROR');
		}
	}
	
	/*
	* Funci�n guarda la observaci�n de asignaci�n de 
	* un referido
	*/
	function agregarObservacionAsignacion($referidoId, $asesorId, $tipoObservacion){
		//Guardar la observacion
		$user = JFactory::getUser();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Observaciones', 'Table');
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');
		$infoAsesor = $this->getAsesor($asesorId);
		$comentario = "Asignacion [".$user->username."->".$infoAsesor->username."]";
		//Se mapean los campos de la tabla con los valores a asignar
		$row->referido = $referidoId;
		$row->fecha = $fechaHoy;
		$row->comentario = $comentario;
		$row->usuario = $user->id;
		$row->tipo = $tipoObservacion;
		$row->store();
	}
	
	/*
	* Funci�n devuelve un listado de referidos que se 
	* encuentran en proceso 
	*/
	function listarReferidosEnProceso($filtro, $asesor, $estado, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbRefEstados = $db->nameQuote('#__zrefestados');

		$whereAsesor = ($asesor > 0) ?  " AND r.asesor = $asesor " : "";
		$whereEstado = ($estado != "") ?  " AND r.estado = '$estado' " : "";
		
		$query = "SELECT 
						r.*, a.username, a.id idAse,e.descripcion
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r,
						$tbRefEstados e
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.estado = e.codigo
						 AND r.asesor != 0
						 $whereAsesor
						 $whereEstado
				  ORDER BY
						r.fecha_creacion  
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n que devuelve la cantidad de referidos en proceso 
	*/
	function contarReferidosEnProceso($filtro, $asesor, $estado){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbRefEstados = $db->nameQuote('#__zrefestados');

		$whereAsesor = ($asesor > 0) ?  " AND r.asesor = $asesor " : "";
		$whereEstado = ($estado != "") ?  " AND r.estado = '$estado' " : "";
		
		$query = "SELECT 
						count(*) 
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r,
						$tbRefEstados e
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.estado = e.codigo
						 AND r.asesor != 0
						 $whereAsesor
						 $whereEstado
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/*
	* Funci�n que devuelve los datos de los referidos en proceso 
	* para exportarlos a excel
	*/
	function listarReferidosEnProcesoExcel($filtro, $asesor, $estado){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbRefEstados = $db->nameQuote('#__zrefestados');

		$whereAsesor = ($asesor > 0) ?  " AND r.asesor = $asesor " : "";
		$whereEstado = ($estado != "") ?  " AND r.estado = '$estado' " : "";
		
		$query = "SELECT 
						r.id, r.cedula, r.nombre, r.fecha_creacion fechaCreacion, a.username asesor, a.id idAse, e.descripcion estado
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r,
						$tbRefEstados e
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.estado = e.codigo
						 AND r.asesor != 0
						 $whereAsesor
						 $whereEstado
				  ORDER BY
						r.fecha_creacion  
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	
	/*
	* Funci�n que devuelve la informaci�n de un asesor espec�fico
	*/
	function getAsesor($id){
		$db = JFactory::getDBO();
		$tbAsesores = $db->nameQuote('#__users');
		
		$query = "SELECT 
						* 
				   FROM 
						$tbAsesores 
				   WHERE 
						id = '%s'
						";
						
		$query = sprintf($query, $id );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result[0];
	}
	
	/*
	* Funci�n que devuelve los diferentes tipos de estado 
	* de un referido
	*/
	function getEstados(){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbRefEstados = $db->nameQuote('#__zrefestados');
		
		$query = "SELECT 
						* 
				   FROM 
						$tbRefEstados 
				   ORDER BY 
						id
						";
						
		$query = sprintf($query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n que devuelve el hist�rico para un referido espec�fico	
	*/
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
					o.id DESC
						";
						 
		$query = sprintf( $query, $id );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n que devuelve la informaci�n de un referido espec�fico	
	*/
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
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}

	/**
	* Funci�n que devuelve la lista de todos los referidos que 
	* est�n asignados
	*/	
	function listarReferidosAsignados($filtro, $inicio, $registros){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$query = "SELECT 
						r.*,a.username
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r  
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
				  ORDER BY
						r.fecha_creacion  
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Funci�n que devuelve la cantidad de referidos asignados
	*/
	function contarReferidosAsignados($filtro){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r  
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
				  ORDER BY
						r.fecha_creacion  
						";
		
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/*
	* Funci�n que devuelve los datos de los referidos asignados 
	* para exportarlos a excel
	*/
	function listarReferidosAsignadosExcel($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$query = "SELECT 
						r.id, r.cedula, r.nombre, r.telefono, r.celular, r.municipio, a.username asesor
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r  
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR	
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
				  ORDER BY
						r.fecha_creacion
						";
						
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	

	/*
	* Funci�n que reasigna un referido a un asesor
	*/
	function reasignarReferido($referidoId, $asesorId){
		//Guardar la observacion
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Referidos', 'Table');
		$row->id = $referidoId;
		$row->asesor = $asesorId;
		if($row->store()){
			//$this->enviarCorreoAsignacion($referidoId,$asesorId);
			$this->agregarObservacionAsignacion($referidoId, $asesorId, "S");
			return JText::_('M_OK') . sprintf( JText::_('PL_REASIGNAR_REFERIDO_OK') , $row->id );
		}else{
			return JText::_('M_ERROR'). JText::_('PL_REASIGNAR_REFERIDO_ERROR');
		}
	}
	
	/*
	* Funci�n que env�a un correo de asignaci�n de referidos
	*/
	public function enviarCorreoAsignacion($idReferido, $idAsesor){
		$msg = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'correoAsignacion.html');
		$msg =  preg_replace("/TAG_ID/", $idReferido , $msg);
		$infoAsesor = $this->getAsesor($idAsesor);
		$result =  ZMailHelper::sendMail( $msg, 
										  "televirtual@etp.net.co" , 
										  "Equipo de administraci�n Referidos UNE" , 
										  "Equipo de administraci�n Referidos UNE",
											array(
															array( 
															   'mail' => $infoAsesor->email, 
																	'name' => $infoAsesor->username  
																	 )
															)
									   );
	}

	/*
	* Funci�n devuelve la cantidad de referidos disponibles 
	* para cambio de plataforma
	*/
	function listarReferidosCambioPlataforma($filtro, $fechaInicio, $fechaFinal, $inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";

		$query = "SELECT 
						r.*, a.username, a.id idAse, p.descripcion plataforma
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						r.fecha_creacion  
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n devuelve un listado de referidos que se 
	* encuentran en proceso para cambio de plataforma
	*/
	function contarReferidosCambioPlataforma($filtro, $fechaInicio, $fechaFinal){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";

		$query = "SELECT 
						count(*)
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						r.fecha_creacion  
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n que devuelve los datos de los referidos a los que 
	* disponibles para cambio de plataforma para exportarlos a excel
	*/
	function listarReferidosCambioPlataformaExcel($filtro, $fechaInicio, $fechaFinal){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";

		$query = "SELECT 
						r.id, r.cedula, r.nombre, r.fecha_creacion, a.username asesor, p.descripcion plataformaActual
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r
				  WHERE 
						 (nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						r.fecha_creacion  
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Funci�n que devuelve la informaci�n de las plataformas	
	*/
	function getPlataformas(){
		$db = JFactory::getDBO();
		$tbPlataformas = $db->nameQuote('#__zplataformas');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbPlataformas
				  ORDER BY
						id
						";
						 
		$query = sprintf( $query );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n que devuelve la informaci�n de una plataforma
	*/
	function getPlataforma($plataformaId){
		$db = JFactory::getDBO();
		$tbPlataformas = $db->nameQuote('#__zplataformas');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbPlataformas
				  WHERE 
						plataforma 0 '%s'
				  ORDER BY
						id
						";
						 
		$query = sprintf( $query , $plataformaId);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Funci�n que reasigna un referido a un asesor
	*/
	function cambiarReferidoPlataforma($referidoId, $plataformaId){
		//Guardar la observacion
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Referidos', 'Table');
		echo ($referidoId);
		echo" - ";
		echo ($plataformaId);
		echo" *** ";
		$row->id = $referidoId;
		$row->estado = "S";
		if($row->store()){
			$this->enviarCorreoCambioPlataforma($referidoId, $plataformaId);
			$this->agregarObservacionCambioPlataforma($referidoId, $plataformaId, "S");
			return JText::_('M_OK') . sprintf( JText::_('PL_CAMBIAR_REFERIDO_PLATAFORMA_OK') );
		}else{
			return JText::_('M_ERROR'). JText::_('PL_CAMBIAR_REFERIDO_PLATAFORMA_ERROR');
		}
	}
	
	/*
	* Funci�n guarda la observaci�n de cambio de
	* plataforma de referidos
	*/
	function agregarObservacionCambioPlataforma($referidoId, $plataformaId, $tipoObservacion){
		//Guardar la observacion
		$user = JFactory::getUser();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Observaciones', 'Table');
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');
		$infoReferido = $this->getReferido($referidoId);
		$infoPlataforma = $this->getPlataforma($plataformaId);
		$comentario = "Cambio de plataforma del referido:".$infoReferido->id." (".$infoReferido->nombre.") a la plataforma".$infoPlataforma->descripcion;
		//Se mapean los campos de la tabla con los valores a asignar
		$row->referido = $infoReferido->id;
		$row->fecha = $fechaHoy;
		$row->comentario = $comentario;
		//$row->usuario = $user->id;
		$row->tipo = $tipoObservacion;
		$row->store();
	}
	
	/*
	* Funci�n que env�a un correo de cambio de plataforma
	*/
	public function enviarCorreoCambioPlataforma($referidoId, $plataformaId){
		$msg = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'correoCambioPlataforma.html');
		$comentario = "nombre:".$infoReferido->nombre." c�dula:".$infoReferido->cedula." tel�fono:".$infoReferido->telefono." celular:".$infoReferido->celular." municipio:".$infoReferido->municipio;
		$msg =  preg_replace("/TAG_ID/", $comentario , $msg);
		$infoReferido = $this->getReferido($referidoId);
		$infoPlataforma = $this->getPlataforma($plataformaId);
		$result =  ZMailHelper::sendMail( $msg, 
										  "televirtual@etp.net.co" , 
										  "Equipo de administraci�n Referidos UNE" , 
										  "Equipo de administraci�n Referidos UNE",
											array(
															array( 
															   'mail' => $infoPlataforma->cambioSegmento, 
																	'name' => $infoAsesor->username  
																	 )
															)
									   );
	}
	
	
	
	/****************************************************************/
	
	function infReferidosPorAsesor(){
		$asesores = $this->getAsesoresPlataforma();
		foreach($asesores as $asesor){
			$asesor->referidos = $this->contarReferidosAsesor($asesor->id);
			$asesor->referidosPorc = ($asesor->referidos * 100) / 20;
		}
		return $asesores;
	}
	
	function contarReferidosAsesor($asesorId){
		$db = JFactory::getDBO();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbReferidos
				  WHERE 
						asesor = '%s'
						";
						 
		$query = sprintf( $query , $asesorId);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
}










