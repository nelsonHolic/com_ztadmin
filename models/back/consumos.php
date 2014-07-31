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


		
class ModelConsumos extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	
	function getConsumo($lineas, $fechaInicial, $fechaFinal){
	
		$db = & JFactory::getDBO();
		$tbConsumo 		= $db->nameQuote('#__zlineas_consumos');
		$lineas = $this->getLineas($lineas);
		
		$query = "
					SELECT 
						*
					FROM 
						$tbConsumo as consumo
					WHERE
						fecha >= '$fechaInicial' AND
						fecha <= '$fechaFinal' AND
						origen in ($lineas)
					ORDER BY 
						origen, fecha
					
						";
		 $db->setQuery($query);
		 $result = $db->loadObjectList();
		
		 $columnsArray = array(
							array('description' => 'Origen', 'property' => 'origen'),
							array('description' => 'Destino', 'property' => 'destino'),
							array('description' => 'Fecha', 'property' => 'fecha'),
							array('description' => 'Hora', 'property' => 'hora'),
							array('description' => 'Duracion', 'property' => 'duracion')
							);
		 $user = JFactory::getUser();
		 $this->createExcelFile($result, "Cons_{$user->id}_", $columnsArray);
		 return $lineas;
		
	}
	

	function createExcelFile($data, $report, $columns){
		
		echo "<br/><br/>";
		//print_r($data);
		
		error_reporting(E_ALL);
		ini_set('display_errors','On');
		
		date_default_timezone_set('America/Bogota');
		
		$nombre = substr($report, 0 , 10);
		$nombre = str_replace(" ", "" , $nombre);
		$fecha = date("Y-m-d");
		$excelName = "{$nombre}_{$fecha}.xls";
		
		
		$opts = array(
					'title' => $excelName,
					'urlfile' => JURI::root() ."components/com_zipcentrex/reports/" . $excelName ,
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
	
	function getLineas($lineas){
		$lineasStr = "";
		
		foreach($lineas as $linea){
			$lineasStr .= "'$linea',";
		}
		$contador = strlen($lineasStr);
		$lineasStr = substr($lineasStr, 0 , $contador - 1 );
		return $lineasStr;
	}
	
	
	
	
	
	
}









