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
	* Función que devuelve la lista de todos los referidos que 
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = 0
				  ORDER BY
						r.fecha_creacion , r.id
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Función que devuelve un listado de asesores que pertenecen 
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
	* Función que devuelve los datos de los referidos sin asignar 
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = 0
				  ORDER BY
						r.fecha_creacion, r.id  
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Función que devuelve la cantidad de referidos sin asignar 
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR						 
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = 0
						"; 
		
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/*
	* Función asigna un referido a un asesor
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
	* Función guarda la observación de asignación de 
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
	* Función devuelve un listado de referidos que se 
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
						r.*, a.username, a.id idAse, e.descripcion
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r,
						$tbRefEstados e
				  WHERE 
						 (r.id like '%s' OR 
						 nombre like '%s' OR
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
						r.fecha_creacion, r.id
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		foreach($result as $data ){
			$data->descripcion = $this->getEstado($data->descripcion);
		}
		return $result;
	}
	
	/*
	* Función que devuelve la cantidad de referidos en proceso 
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR
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
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/*
	* Función que devuelve los datos de los referidos en proceso 
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
						r.id, r.cedula, r.nombre, r.fecha_creacion fechaCreacion, e.descripcion estado, a.username asesor, a.id idAse
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r,
						$tbRefEstados e
				  WHERE 
						 (r.id like '%s' OR 
						 nombre like '%s' OR
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
						r.fecha_creacion, r.id
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	
		
	/*
	* Función que devuelve la información de un asesor específico
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
	* Función que devuelve los diferentes tipos de estado 
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
	
	function getEstado($estado){
		$estado = ( strpos($estado, "Nuevo") !== FALSE ) ? "<span class='badge'>$estado</span>" : $estado;
		$estado = ( strpos($estado, "con venta") !== FALSE ) ? "<span class='label label-warning'>$estado</span>" : $estado;
		$estado = ( strpos($estado, "sin venta") !== FALSE ) ? "<span class='label label-success'>$estado</span>" : $estado;
		$estado = ( strpos($estado, "Anulado") !== FALSE ) ? "<span class='label label-important'>$estado</span>" : $estado;
		$estado = ( strpos($estado, "Pendiente") !== FALSE) ? "<span class='label label-info'>$estado</span>" : $estado;
		
		return $estado;
	}
	
	/*
	* Función que devuelve el histórico para un referido específico	
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
	* Función que devuelve la información de un referido específico	
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
	* Función que devuelve la lista de todos los referidos que 
	* están asignados
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
				  ORDER BY
						r.fecha_creacion, r.id
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Función que devuelve la cantidad de referidos asignados
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
						";
		
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/*
	* Función que devuelve los datos de los referidos asignados 
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR	
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.asesor != 0
				  ORDER BY
						r.fecha_creacion, r.id
						";
						
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	

	/*
	* Función que reasigna un referido a un asesor
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
	* Función que envía un correo de asignación de referidos
	*/
	public function enviarCorreoAsignacion($idReferido, $idAsesor){
		$msg = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'correoAsignacion.html');
		$msg =  preg_replace("/TAG_ID/", $idReferido , $msg);
		$infoAsesor = $this->getAsesor($idAsesor);
		$result =  ZMailHelper::sendMail( $msg, 
										  "televirtual@etp.net.co" , 
										  "Equipo de administración Referidos UNE" , 
										  "Equipo de administración Referidos UNE",
											array(
															array( 
															   'mail' => $infoAsesor->email, 
																	'name' => $infoAsesor->username  
																	 )
															)
									   );
	}

	/*
	* Función devuelve la cantidad de referidos disponibles 
	* para cambio de plataforma
	*/
	function listarReferidosCambioPlataforma($filtro, $fechaInicio, $fechaFinal, $estado,$inicio, $registros){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbRefEstados = $db->nameQuote('#__zrefestados');
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";
		$whereEstado = ($estado != "") ?  " AND r.estado = '$estado' " : "";

		$query = "SELECT 
						r.*, a.username, a.id idAse, p.descripcion plataforma, e.descripcion estado
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r,
						$tbRefEstados e
				  WHERE 
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.estado = e.codigo
						 AND r.asesor != 0
						 AND r.notificado_uc = 0
						 AND r.estado = 'C'
						 $whereFechaInicio
						 $whereFechaFinal
						 $whereEstado
				  ORDER BY
						r.fecha_creacion, r.id  
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Función devuelve un listado de referidos que se 
	* encuentran en proceso para cambio de plataforma
	*/
	function contarReferidosCambioPlataforma($filtro, $fechaInicio, $fechaFinal, $estado){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbRefEstados = $db->nameQuote('#__zrefestados');
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";
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
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.estado = e.codigo
						 AND r.asesor != 0
						 AND r.notificado_uc = 0
						 $whereFechaInicio
						 $whereFechaFinal
						 $whereEstado
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	/*
	* Función que devuelve los datos de los referidos a los que 
	* disponibles para cambio de plataforma para exportarlos a excel
	*/
	function listarReferidosCambioPlataformaExcel($filtro, $fechaInicio, $fechaFinal, $estado){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbAsesores = $db->nameQuote('#__users');
		$tbPlataforma = $db->nameQuote('#__zplataformas');
		$tbPlataformaGrupo = $db->nameQuote('#__zplataforma_grupo');
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbRefEstados = $db->nameQuote('#__zrefestados');
		
		$whereFechaInicio = ($fechaInicio > 0) ? " AND r.fecha_creacion >= '$fechaInicio'" : "";
		$whereFechaFinal  = ($fechaFinal > 0) ?  " AND r.fecha_creacion <= '$fechaFinal'" : "";
		$whereEstado = ($estado != "") ?  " AND r.estado = '$estado' " : "";

		$query = "SELECT 
						r.id, r.cedula, r.nombre, r.fecha_creacion, a.username asesor, p.descripcion plataformaActual, e.descripcion estado
				  FROM 
						$tbAsesores a,
						$tbPlataforma p, 
						$tbPlataformaGrupo pg,
						$tbReferidos r,
						$tbRefEstados e
				  WHERE 
						 (r.id like '%s' OR 
						 nombre like '%s' OR
						 cedula like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND p.usuario = %s
						 AND p.id= pg.plataforma
						 AND pg.grupo = r.grupo
						 AND r.asesor = a.id
						 AND r.estado = e.codigo
						 AND r.asesor != 0
						 AND r.notificado_uc = 0
						 $whereFechaInicio
						 $whereFechaFinal
				  ORDER BY
						r.fecha_creacion, r.id
						";

		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $filtro, $filtro, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}	
	
	/*
	* Función que devuelve la información de las plataformas	
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
	* Función que devuelve la información de una plataforma
	*/
	function getPlataforma($plataformaId){
		$db = JFactory::getDBO();
		$tbPlataformas = $db->nameQuote('#__zplataformas');		
		
		$query = "SELECT 
						*
				  FROM 
						$tbPlataformas
				  WHERE 
						id = '%s'
				  ORDER BY
						id
						";
						 
		$query = sprintf( $query , $plataformaId);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result[0];
	}
	
	/*
	* Función que reasigna un referido a un asesor
	*/
	function cambiarReferidoPlataforma($referidoId, $plataformaId){
		//Guardar la observacion
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Referidos', 'Table');
		$row->id = $referidoId;
		$row->notificado_uc = "1";
		if($row->store()){
			$this->enviarCorreoCambioPlataforma($referidoId, $plataformaId);
			$this->agregarObservacionCambioPlataforma($referidoId, $plataformaId, "S");
			return JText::_('M_OK') . sprintf( JText::_('PL_CAMBIAR_REFERIDO_PLATAFORMA_OK') );
		}else{
			return JText::_('M_ERROR'). JText::_('PL_CAMBIAR_REFERIDO_PLATAFORMA_ERROR');
		}
	}
	
	/*
	* Función guarda la observación de cambio de
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
	* Función que envía un correo de cambio de plataforma
	*/
	public function enviarCorreoCambioPlataforma($referidoId, $plataformaId){
		$infoReferido = $this->getReferido($referidoId);
		$infoPlataforma = $this->getPlataforma($plataformaId);
		$msg = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'correoCambioPlataforma.html');
		$comentario = "<br/><br/>Nombre:".$infoReferido->nombre."<br></br>Cédula:".$infoReferido->cedula."<br></br>Teléfono:".$infoReferido->telefono."<br></br>Celular:".$infoReferido->celular."<br></br>Municipio:".$infoReferido->municipio."<br></br>";
		$msg =  preg_replace("/TAG_ID/", $comentario , $msg);		
		$result =  ZMailHelper::sendMail( $msg, 
										  "televirtual@etp.net.co" , 
										  "Equipo de administración Referidos UNE" , 
										  "Equipo de administración Referidos UNE",
											array(
															array( 
															   'mail' => $infoPlataforma->cambio_segmento, 
																	'name' => $infoPlataforma->cambio_segmento  
																	 )
															)
									   );
	}
	
	
	function infReferidosPorAsesor(){
		$asesores = $this->getAsesoresPlataforma();
		foreach($asesores as $asesor){
			$asesor->referidos = $this->contarReferidosPendientesAsesor($asesor->id);
			$asesor->referidosPorc = ($asesor->referidos * 100) / 20;
		}
		return $asesores;
	}
	
	function contarReferidosPendientesAsesor($asesorId){
		$db = JFactory::getDBO();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbReferidos
				  WHERE 
						estado in ('N','P') AND
						asesor = '%s'
						";
						 
		$query = sprintf( $query , $asesorId);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function infCumplimientoAsesores($fechaInicial, $fechaFinal){
		$asesores = $this->getAsesoresPlataforma();
		foreach($asesores as $asesor){
			$asesor->fcv = $this->contarReferidosEnEstadoAsesor($asesor->id, 'V', $fechaInicial, $fechaFinal);
			$asesor->fsv = $this->contarReferidosEnEstadoAsesor($asesor->id, 'S', $fechaInicial, $fechaFinal);
			$asesor->anu = $this->contarReferidosEnEstadoAsesor($asesor->id ,'A', $fechaInicial, $fechaFinal);
			$porcentajeCumplimiento = 0;
			if($asesor->fcv + $asesor->fsv + $asesor->anu > 0){
				$porcentajeCumplimiento = ($asesor->fcv / ($asesor->fcv + $asesor->fsv + $asesor->anu) ) * 100;
			}
			$asesor->pc = $porcentajeCumplimiento;
			
		}
		return $asesores;
	}
	
	function contarReferidosEnEstadoAsesor($asesorId, $estado, $fechaInicial, $fechaFinal){
		$db = JFactory::getDBO();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');		
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbReferidos
				  WHERE 
						estado in ('%s') AND
						fecha_creacion >= '%s' AND
						fecha_creacion <= '%s' AND
						asesor = '%s'
						";
						 
		$query = sprintf( $query , $estado, $fechaInicial, $fechaFinal, $asesorId);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
}
