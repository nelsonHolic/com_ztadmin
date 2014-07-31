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
		
class ModelAsesores extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	/**
	* Lista de asesores
	*/
	function listarReferidos($filtro, $estado,  $inicio, $registros){	
	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		$tbEstados = $db->nameQuote('#__zrefestados');
		
		//$whereUsuario = ($usuario > 0) ?  " AND usuario_asignado = $usuario " : "";
		$whereEstado = ($estado != '') ?  " AND estado = '$estado' " : "";
		
		$query = "SELECT 
						r.* , e.descripcion
				  FROM 
						$tbReferidos as r,
						$tbEstados as e
				  WHERE 
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' ) AND
						 r.estado = e.codigo 
						 $whereEstado
						 AND asesor = %s
				  ORDER BY
						id  
						";
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro, $user->id );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		foreach($result as $data ){
			$data->descripcion = $this->getEstado($data->descripcion);
		}
		return $result;
	}
	
	function contarReferidos($filtro, $estado){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$whereEstado = ($estado != '') ?  " AND estado = '$estado' " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbReferidos as r
				  WHERE 
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 $whereEstado
						 AND asesor = %s
						 ";
		
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro, $filtro,  $user->id );
		$db->setQuery($query);
	    $result = $db->loadResult();
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
	
	/**
	* Lista de tiquetes
	*/
	function listarReferidosTodos($filtro){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbReferidos = $db->nameQuote('#__zrefrecomends');
		
		$query = "SELECT 
						id,asesor, referidor, nombre, cedula
				  FROM 
						$tbReferidos as r
				  WHERE 
						 (nombre like '%s' OR
						 telefono like '%s' OR
						 celular like '%s' )
						 AND asesor = %s
				  ORDER BY
						id  
						";
						
		$filtro = "%". $filtro . "%";			
		$query = sprintf( $query, $filtro, $filtro, $filtro, $user->id );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
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
	
	function referidoCambiarEstado(){
		//Guardar la observacion
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Observaciones', 'Table');
		
		$estado     = JRequest::getVar('estado');
		$comentario = JRequest::getVar('comentario');
		
		if( $comentario != "" ){
			if($row->bind(JRequest::get('post'))){
				date_default_timezone_set('America/Bogota');
				$fechaHoy = date('Y-m-d H:i:s');
				$row->fecha = $fechaHoy;
				
				$user = JFactory::getUser();
				$row->usuario = $user->id;
				$row->tipo = "U";
				
				if($row->store()){
					//Guarda cambio de estado en observaciones
					$estadoAnt = JRequest::getVar('estadoAnt');
					$estadoAntDesc = $this->getDescripcionEstado($estadoAnt);
					$estado = JRequest::getVar('estado');
					$estadoDesc = $this->getDescripcionEstado($estado);
					
					if($estadoAnt != $estado && $estado != ""){
						$row->id = "";
						$row->comentario = "Cambio de estado: {$estadoAntDesc} -> {$estadoDesc}";
						$row->tipo = "S";
						$row->store();
						
						//Actualizar estado de referido
						$row = &JTable::getInstance('Referidos', 'Table');
						$row->load(JRequest::getVar('referido'));
						$row->tv = JRequest::getVar('tv');
						$row->ba = JRequest::getVar('ba');
						$row->to = JRequest::getVar('to');
						$row->iptv = JRequest::getVar('iptv');
						$row->hd = JRequest::getVar('hd');
						$row->ltge4 = JRequest::getVar('ltge4');
						$row->softphone = JRequest::getVar('softphone');
						$row->soletpcarr = JRequest::getVar('soletpcarr');
						$row->estado = $estado;
						
						if( in_array($estado, array('V','S','C'))){
							$row->fecha_atencion = $fechaHoy;
							$row->asesor_final = $user->id;
						}
						
						if($row->store()){
							return JText::_('M_OK') . sprintf( JText::_('AS_REFERIDOS_CAMBIAR_ESTADO_MSG_GUARDAR_OK') , $row->id );
						}
						else{
							return JText::_('M_ERROR'). JText::_('AS_REFERIDOS_CAMBIAR_ESTADO_MSG_GUARDAR_ERROR');
						}
					}else{
						return JText::_('M_OK') . sprintf( JText::_('AS_REFERIDOS_CAMBIAR_ESTADO_MSG_GUARDAR_OK') , $row->id );
					}
					
					
					
				}
				else{
					//ERR
				}

			}
		}
		
		return JText::_('M_ERROR'). JText::_('AS_REFERIDOS_CAMBIAR_ESTADO_MSG_GUARDAR_ERROR');
		
	}
	
	function getDescripcionEstado($codigo){
		$db = JFactory::getDBO();
		$tbRefEstados = $db->nameQuote('#__zrefestados');		
		
		$query = "SELECT 
						descripcion
				  FROM 
						$tbRefEstados as e
				  WHERE
					    e.codigo = '%s'
						";
						 
		$query = sprintf($query, $codigo );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
}










