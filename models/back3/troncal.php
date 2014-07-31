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
		
class ModelTroncal extends JModel{
  
  /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	function getSedes(){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbSedes = $db->nameQuote('#__zsedes');	
		$tbTroncales = $db->nameQuote('#__ztroncales');
		$tbSedeLineas = $db->nameQuote('#__zsede_lineas');	
		
		$query = "SELECT 
						*,s.id as id,s.descripcion, t.descripcion as troncal,  sl.linea
				  FROM 
						$tbSedes s,
						$tbTroncales t,
						$tbSedeLineas sl
				  WHERE 
						t.id = s.troncal  AND
						s.id = sl.sede AND
						sl.principal = 'S' AND
						t.user = %s 
				  ORDER BY
						s.id 
						";
						
		$query = sprintf( $query, $user->id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	function guardarPermisosSede($data){
		$user = JFactory::getUser();
		
		if( $data['sede'] > 0 ){
			//Traer informacion de sede y troncal
			$sede = $this->getInfoSede($data['sede']);
			
			//Armar cadena de permisos
			$local = isset($data['local']) ? " Local " : " ";
			$movil = isset($data['movil']) ? " Movil " : " ";
			$l113 = isset($data['l113']) ? " 113 " : " ";
			$ddn = isset($data['ddn']) ? " DDN " : " ";
			$ddi = isset($data['ddi']) ? " DDI " : " ";
			$l901 = isset($data['l901']) ? " L901 " : " ";
			
			$msg = "Actualizar permisos Troncal:<br/>
					Nit: {$sede->nit}<br/>
					Sede: {$sede->descripcionSede}<br/>
					Permisos: $local $movil $l113 $ddn $ddi $l901
				   ";
			
			$sql = "INSERT INTO jos_zcontact_mensajes(nombre, correo, mensaje, tipo, nit, fecha_registro, estado)
					VALUES('{$user->name}', '{$user->email}', '{$msg}', 'Z','{$sede->nit}', now(), 'P' );
					";
			
			//Guardar datos en of virtual troncal
			$result = $this->guardarOfVirtual($sql);
			
			if( $result ){
				return JText::_('M_OK') . sprintf( JText::_('AD_CAMBIAR_PERMISOS_SEDE_OK'));
			}
			else{
				return JText::_('M_ERROR').  sprintf( JText::_('AD_CAMBIAR_PERMISOS_SEDE_ERROR') );
			}
		}
		else{
			return JText::_('M_ERROR').  sprintf( JText::_('AD_CAMBIAR_PERMISOS_SEDE_FALTAN_DATOS') );
		}
		
		
	}
	
	function guardarOfVirtual($sql){
		error_reporting(E_ALL);
		$enlace =  mysql_connect('10.4.251.17', 'root', 'giga');
		//$enlace =  mysql_connect('localhost', 'root', '');
		mysql_select_db('intranet_beta', $enlace);
		echo mysql_errno($enlace) . ": " . mysql_error($enlace). "\n";
		$resultado = mysql_query( $sql, $enlace );
		echo mysql_errno($enlace) . ": " . mysql_error($enlace). "\n";
		mysql_select_db('troncalsipadm', $enlace);
		//mysql_close($enlace);
		return $resultado;
	}
	
	
	function guardarUser(){
		$db = JFactory::getDBO();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		$clave1 = JRequest::getVar('clave1');
		$clave2 = JRequest::getVar('clave2');
		
		if($row->bind(JRequest::get('post'))){
			date_default_timezone_set('America/Bogota');
			$fechaHoy = date('Y-m-d H:i:s');
			$row->registerDate = $fechaHoy;
			$row->tipo ='S';
			
			if($clave1 == $clave2){
				$row->password = md5($clave1);
			}
			
			$row->params= '{"admin_style":"","admin_language":"","language":"","editor":"","helpsite":"","timezone":""}';
			
			if($row->store()){
				//Enlaza usuario con grupo
				$tbGrupo = $db->nameQuote('#__user_usergroup_map');
				$query = "INSERT INTO $tbGrupo(user_id, group_id) VALUES({$row->id}, 2 )";
				$db->setQuery($query);
				$db->query();
				
				//Enviar correo usuario con usuario y clave
				if( $this->guardarUsuarioCorreo($row->name, $row->username, $row->email, $clave1) ){
					return $row->id ;
				}
				else{
					return $row->id;
				}
				
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function guardarUsuarioCorreo($nombre, $usuario, $correo, $clave){
		//Arma cuerpo correo
		$body = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'inscripciontroncalsip.html');
		$body =  preg_replace("/TAG_NOMBRE/", $nombre, $body);
		$body =  preg_replace("/TAG_USUARIO/", $usuario, $body);
		$body =  preg_replace("/TAG_CLAVE/", $clave, $body);
		
		//Envia correo
		$result = ZMailHelper::sendMail( $body , "troncalsip@etp.com.co", "Servicio Troncal Sip" , "Inscripción Troncal Sip" , 
							   array( array('mail' => $correo, 'name' => $nombre) ) , NULL , NULL,  NULL 
									 );
		if( $result == true){
			return true;
		}
		else{
			return false;
		}
		
	}
	
	function guardarSedeUsuario($sede, $id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('UsuarioSede', 'Table');
		
		if($row->bind(JRequest::get('post'))){
			$row->sede =$sede;
			$row->usuario =$id;
			
			if($row->store()){
				$contUsuarios = $this->conteoUsuariosSede($sede);
				if( $contUsuarios >= 2 ){
				
					$sede = $this->getInfoSede($sede);
					$user = JFactory::getUser();
					$msg = "Adicionar cobro usuario:<br/>
					Nit: {$sede->nit}<br/>
					Sede: {$sede->descripcionSede}<br/>
					";
			
					$sql = "INSERT INTO jos_zcontact_mensajes(nombre, correo, mensaje, tipo, nit, fecha_registro, estado)
						VALUES('{$user->name}', '{$user->email}', '{$msg}', 'Z','{$sede->nit}', now(), 'P' );
						";
				
					//Guardar datos en of virtual troncal
					$result = $this->guardarOfVirtual($sql);
				
					return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_USUARIO_OK_COBRO') , $row->id );
				}
				else{
					return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_USUARIO_OK') , $row->id );
				}
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_USUARIO_ERROR');
			}
		
		}
	}
	
	function getInfoSede($sede){
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbSedes = $db->nameQuote('#__zsedes');	
		$tbTroncales = $db->nameQuote('#__ztroncales');
		$tbSedeLineas = $db->nameQuote('#__zsede_lineas');	
		
		
		$query = "SELECT 
						
						concat(s.descripcion,' [', sl.linea, ']') as descripcionSede, t.* , sl.linea
				  FROM 
						$tbSedes s,
						$tbTroncales t,
						$tbSedeLineas sl
				  WHERE 
						t.id = s.troncal  AND
						s.id = sl.sede AND
						sl.principal = 'S' AND
						s.id = %s 
						";
						
		$query = sprintf( $query, $sede);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result[0];
	}
	
	function conteoUsuariosSede($sede){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbUsuariosSede = $db->nameQuote('#__zsede_usuarios');	
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbUsuariosSede us
				  WHERE 
						us.sede = %s 
						";
						
		$query = sprintf( $query, $sede);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getUsuarioSede($id){	
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$tbSedeUsuarios = $db->nameQuote('#__zsede_usuarios');	
		$tbUsuarios = $db->nameQuote('#__users');	
		
		$query = "SELECT 
						*,g.id as id
				  FROM 
						$tbSedeUsuarios g,
						$tbUsuarios u
				  WHERE 
						g.usuario = u.id AND
						u.id = %s
				  ORDER BY
						u.id 
						";
						
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function eliminarUsuario($id){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('User', 'Table');
		
		$row->id = $id;
		if($row->delete()){
			return JText::_('M_OK') . sprintf( JText::_('AD_RETIRAR_USUARIO_OK') , $row->id );
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_RETIRAR_USUARIO_ERROR');
		}
	}
	
	function eliminarSedeUsuario($id, $sede){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('UsuarioSede', 'Table');
		
		$row->id = $id;
		$contUsuarios = $this->conteoUsuariosSede($sede);
		
		if($row->delete()){
			
			if($contUsuarios >= 2){
				$user = JFactory::getUser();
				$sede = $this->getInfoSede($sede);
				
				$msg = "Retirar cobro usuario:<br/>
					Nit: {$sede->nit}<br/>
					Sede: {$sede->descripcionSede}<br/>
				   ";
			
				$sql = "INSERT INTO jos_zcontact_mensajes(nombre, correo, mensaje, tipo, nit, fecha_registro, estado)
					VALUES('{$user->name}', '{$user->email}', '{$msg}', 'Z','{$sede->nit}', now(), 'P' );
					";
			
				//Guardar datos en of virtual troncal
				$result = $this->guardarOfVirtual($sql);
			
				return JText::_('M_OK') . sprintf( JText::_('AD_RETIRAR_USUARIO_OK_COBRO') , $row->id );
			}
			else{
				return JText::_('M_OK') . sprintf( JText::_('AD_RETIRAR_USUARIO_OK') , $row->id );
			}
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_RETIRAR_USUARIO_ERROR');
		}
	}
}







