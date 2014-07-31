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


		
class ModelActualizaciones extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function existeIpCentrex($nit){
		$db = & JFactory::getDBO();
		
		$tbIpCentrex = $db->nameQuote('#__zipcentrex');
		
		$query = "
					SELECT
						count(*)
					FROM 
						$tbIpCentrex
					WHERE
						nit = '$nit'
						";
						
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	/**
	* Retorna un usuario de un IpCentrex
	* @param nit
	*/
	function getUserIpCentrex($nit){
		
		$db = & JFactory::getDBO();
		
		$tbZUsers = $db->nameQuote('#__zusers');
		$tbUsers = $db->nameQuote('#__users');
		
		$query = "
					SELECT
						users.id
					FROM 
						$tbZUsers zusers,
						$tbUsers users
					WHERE
						zusers.usuario = users.username AND
						zusers.nit = '$nit'
						";
						
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
	
	
	function actualizarInfoIpCentrex($nit){
		//Establece timezone
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');
		
		//Conexion a bd
		$db = & JFactory::getDBO();
		$tbIpCentrex = $db->nameQuote('#__zipcentrex');
		
		//Web service
		$client = new SoapClient("http://10.4.251.17/etp/ws/ipcentrex/ipcentrex.php?wsdl");
		$result = $client->infoIpCentrex($nit);
		$data = explode("|", $result);
		
		$groupId = $data[0];
		
		if( $this->existeIpCentrex($nit) ){
			$query = "
					UPDATE
						$tbIpCentrex
					SET
						group_id = {$groupId},
						nombre = '{$data[3]}',
						direccion = '{$data[6]}',
						ult_act = '{$fechaHoy}'
					WHERE
						nit = '$nit'
						";
			$db->setQuery($query);
			$result = $db->query($query);
		}
		else{
			JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_zipcentrex'.DS.'tables');
			$row =& JTable::getInstance('IpCentrex', 'Table');
			
			$user = $this->getUserIpCentrex($nit);
			
			$row->nombre = $data[1];
			$row->direccion = $data[6];
			$row->ult_act = $fechaHoy;
			$row->group_id = $groupId;
			$row->nit = $nit;
			$row->usuario = $user;
			
			$result = $row->store();
		}
		
		$resultIpCentrex = (($result == true) ? "  OK" : "  ERROR");
		
		$result = $client->infoLineasIpCentrex($groupId);
		
		echo "<br/>Actualizaci&oacute;n IpCentrex ...... $resultIpCentrex";
		
		//Carga informacion de las lineas del IPCentrex y sus servicios
		$this->actualizarLineasIpCentrex($groupId, $result);
		
	}
	
	function actualizarLineasIpCentrex($groupId, $lineas){
		JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_zipcentrex'.DS.'tables');
		$db = & JFactory::getDBO();
		
		//borra las lineas del ipcentrex
		$tbLineas = $db->nameQuote('#__zlineas');
		$query = " DELETE FROM $tbLineas WHERE group_id = $groupId";
		$db->setQuery($query);
		$result = $db->query($query);
		
		$data = explode("||", $lineas);
		print_r($data);
		
		foreach($data as $linea){
			$lineaInfo = explode("|", $linea); 
			//print_r($lineaInfo);
			if(isset($lineaInfo[1])){
				$groupId 	= $lineaInfo[0];
				$ext 		= $lineaInfo[1];
				$tel 		= $lineaInfo[2];
				$operadora 	= $lineaInfo[3];
				$direccion 	= $lineaInfo[5];
				$categoria 	= $lineaInfo[6];
				$servicios 	= (isset($lineaInfo[7]) ? $lineaInfo[7] : "");
				
				$lineaDB =& JTable::getInstance('Linea', 'Table');
				$lineaDB->linea = $tel;
				$lineaDB->ext = $ext;
				$lineaDB->direccion = $direccion;
				$lineaDB->operadora = $operadora;
				$lineaDB->group_id = $groupId;
				
				$this->cargarCategoria($lineaDB, $categoria);
				$this->cargarServicios($lineaDB, $servicios);
				
				//$lineaDB->cat_local = $categoria;
				$dataResult = $lineaDB->store();
			}
		
		}
	}
	
	function cargarCategoria(&$lineaDB, $categoria){
		if($categoria == 2017 ){
			$lineaDB->cat_local = 1;
		}
		else if($categoria == 2018 ||  $categoria == 2019 ){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
		}
		else if($categoria == 2020 ){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_celular = 1;
		}
		else if($categoria == 2021 ){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_901 = 1;
		}
		else if($categoria == 2022 ){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_celular = 1;
			$lineaDB->cat_901 = 1;
		}
		else if($categoria == 2023 ){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_901 = 1;
		}
		else if($categoria == 2024 ){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_celular = 1;
			$lineaDB->cat_901 = 1;
		}
		else if($categoria == 2025){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_celular = 1;
			$lineaDB->cat_ddi = 1;
		}
		else if($categoria == 2026){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_901 = 1;
			$lineaDB->cat_ddi = 1;
		}
		else if($categoria == 2026){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_901 = 1;
			$lineaDB->cat_ddi = 1;
		}
		else if($categoria == 2027){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
			$lineaDB->cat_901 = 1;
			$lineaDB->cat_ddi = 1;
			$lineaDB->cat_celular = 1;
		}
		else if($categoria == 2028){
			$lineaDB->cat_local = 1;
			$lineaDB->cat_113 = 1;
			$lineaDB->cat_ddn = 1;
		}
		
	}
	
	
	const MARCACION_ABREVIADA = 40;
	const IDENTIFICADOR_LLAMADA = 33;
	const CONFERENCIA_TRIPARTITA = 37;
	const CAPT_LLAM_GRUPO_TIMBRADO = 2253;
	const SERVICIO_SECRETARIA = 2255;
	const GRUPO_TIMBRADO = 2277;
	const TEMPORIZADOR = 2279;
	const NO_MOLESTAR = 6024;
	const CODIGO_SECRETO = 34;
	const TELEMEMO = 2002;
	const CONEXION_INMEDIATA = 35;
	const TRANSFERENCIA_LLAMADAS = 2254;
	const TRANSFERENCIA_LLAMADAS_LINEAS = 2260;
	const DESVIO_INCONDICIONAL = 41;
	const DESVIO_OCUPADO = 2281;
	const DESVIO_NO_RESPUESTA = 2282;
	
	
	
	function cargarServicios(&$lineaDB, $servicios){
	
		$servicios = explode("," , $servicios );
		foreach($servicios as $servicio){
			if($servicio == self::MARCACION_ABREVIADA){
				$lineaDB->ser_ma = 1;
			}
			else if($servicio == self::IDENTIFICADOR_LLAMADA ){
				$lineaDB->ser_il = 1;
			}
			else if($servicio == self::CONFERENCIA_TRIPARTITA ){
				$lineaDB->ser_ct = 1;
			}
			else if($servicio == self::CAPT_LLAM_GRUPO_TIMBRADO){
				$lineaDB->ser_clgt = 1;
			}
			else if($servicio == self::SERVICIO_SECRETARIA){
				$lineaDB->ser_ss = 1;
			}
			else if($servicio == self::GRUPO_TIMBRADO){
				$lineaDB->ser_gt = 1;
			}
			else if($servicio == self::TEMPORIZADOR){
				$lineaDB->ser_te = 1;
			}
			else if($servicio == self::NO_MOLESTAR){
				$lineaDB->ser_nm = 1;
			}
			else if($servicio == self::CODIGO_SECRETO){
				$lineaDB->ser_cs = 1;
			}
			else if($servicio == self::TELEMEMO){
				$lineaDB->ser_tm = 1;
			}
			else if($servicio == self::CONEXION_INMEDIATA){
				$lineaDB->ser_ci = 1;
			}
			else if($servicio == self::TRANSFERENCIA_LLAMADAS){
				$lineaDB->ser_tl = 1;
			}
			else if($servicio == self::TRANSFERENCIA_LLAMADAS_LINEAS){
				$lineaDB->ser_tll = 1;
			}
			else if($servicio == self::DESVIO_INCONDICIONAL){
				$lineaDB->ser_di = 1;
			}
			else if($servicio == self::DESVIO_OCUPADO){
				$lineaDB->ser_do = 1;
			}
			else if($servicio == self::DESVIO_NO_RESPUESTA){
				$lineaDB->ser_dnr = 1;
			}
			
		}
	}
	
	
	
	
	
}










