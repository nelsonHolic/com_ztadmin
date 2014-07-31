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


		
class ModelZWebReports extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function getReports( ){
		$db 	= &JFactory::getDBO();
		$user 	= &JFactory::getUser(); 
		
		$tableReports = $db->nameQuote('#__zcwreports');
		$tableUserReport = $db->nameQuote('#__zcwuserreport');
		$tableUserRol = $db->nameQuote('#__zcwuserrol');
		$tableReportRol = $db->nameQuote('#__zcwreportrol');
		
		//Obtiene reportes globales
		$query = "
					SELECT 
						reports.* 
					FROM 
						$tableReports reports
				    WHERE
						reports.global = 1
				  ";
		$db->setQuery($query);
	    $resultGlobal = $db->loadObjectList();
		
		//Obtiene reportes del usuario
		$query = "
					SELECT 
						reports.* 
					FROM 
						$tableReports reports,
						$tableUserReport userreport
				    WHERE
							(reports.id = userreport.report AND
							userreport.user = {$user->id} ) 
				  ";
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		//Obtiene reportes de los roles del usuario
		$query = "
					SELECT 
						reports.* 
					FROM 
						$tableReports reports,
						$tableUserRol userrol,
						$tableReportRol reportrol
				    WHERE
						userrol.user = {$user->id} AND
						userrol.rol = reportrol.rol AND
						reports.id = reportrol.report
						
						
				  ";
		$db->setQuery($query);
	    $result2 = $db->loadObjectList();
		
		$result = array_merge($result, $result2);
		$result = array_merge($result, $resultGlobal);
		return $result;
	}
	
	function getReport($id){
		$db = & JFactory::getDBO();
		$tableReports = $db->nameQuote('#__zcwreports');
		$query = "SELECT * FROM $tableReports where id=$id";
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result[0];
	}
	
	function getQueryParameters($query){
		preg_match_all("/\{\w*\}/", $query, $parameters);
		$params = "";
		if( is_array($parameters)){
			foreach($parameters[0] as $parameter){
				$fieldName = str_replace(array("{","}"), "", $parameter );
				$params[] = $fieldName;
			}
		}
		return $params;
	}
	
	function executeReport($report){
		
		//Obtener paramatros de base de datos
		$options = $this->getDatabaseParams($report->database);
		
		//Conexion a base de datos
		$db = & JDatabase::getInstance( $options );
	
		//Ejecutar consulta y retornar resultados
		$db->setQuery( $report->query );
		//echo $report->query;
		
	    $result = $db->loadObjectList();
		//print_r($result);
		
		$columns = $this->getColumns($report->query);
		//Exportar a excel
		$this->createExcelFile($result, $report, $columns);
	}
	
	function getColumns($query){
		
		//Evita problemas con mayusculas y minusculas
		$query = str_ireplace("select", "select", $query );
		$query = str_ireplace("from", "from", $query );
		
		
		$data = explode("select",$query);
		$data = $data[1];
		$data = explode("from",$data);
		$data = $data[0];

		$columnsArray = array();
		$columns = explode(",",$data);
		foreach($columns as $column){
			//echo "<br/>$column</br>";
			$column = trim($column);
			if(strpos( $column, "as") > 0 ){
				$column = explode(" ",$column);
				$column = $column[2];
				//print_r($column);
			}
			$column = str_replace(" " , "", $column);
			$columnsArray[] = array(
											'description' => $column,
											'property' => $column
										);
		}
					
		//echo "<br/><br/>";
		//print_r($columnsArray);
		return $columnsArray;
	}
	
	
	function createExcelFile($data, $report, $columns){
		$user = JFactory::getUser();
		ini_set('max_execution_time', 360);
		echo "<br/><br/>";
		//print_r($data);
		///print_r($columns);
		
		$nombre = substr($report->nombre, 0 , 10);
		$nombre = str_replace(" ", "" , $nombre);
		$fecha = date("Y-m-d");
		$excelName = "{$report->id}_{$user->id}_{$nombre}_{$fecha}.xls";
		
		
		
		$opts = array(
					'title' => $excelName,
					'urlfile' => JURI::root() ."components/com_ztadmin/reports/" . $excelName ,
					//'urlfile' => "http://localhost/reportes/ " . $excelName ,
					'path' =>   JPATH_COMPONENT . DS . 'reports' . DS . $excelName,
					//'path' =>   "http://localhost/reportes/ " . $excelName,
					//'template' =>   JPATH_COMPONENT . DS . 'excel' . DS . "plantilla.xlsx",
					//'header' =>   false,
					'initialRow' => 2,
					'initialColumn' => 0,
					'columns' =>  $columns
										
					);
					
		if(isset($data)){
			ZExcelHelper::exportData( $data , $opts  );
			return true;
		}
		else{
			return false;
		}
	}
	
	
	
	
	function getDatabaseParams($database){
		
		$db 		= & JFactory::getDBO();
		$tableDB 	= $db->nameQuote('#__zcwdatabases');
		$query = "SELECT * FROM $tableDB where id=$database";
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
		
		$option = array(); //prevent problems
		
		//Produccion
		$option['driver']   = 'mysql';            // Database driver name
		$option['host']     = $result->host;    // Database host name
		$option['user']     = $result->user;       // User for database authentication
		$option['password'] = $result->pass;   // Password for database authentication
		$option['database'] = $result->db;      // Database name
		$option['prefix']   = '';             // Database prefix (may be empty)
		
		return $option;
		
	}
	
	function reportLog($reportId){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		$user = JFactory::getUser();
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');


		$row =& JTable::getInstance('ReporteLog', 'Table');
		$row->report = $reportId;
		$row->usuario = $user->id;
		$row->fecha = $fechaHoy;
		$row->store();
	}
}









