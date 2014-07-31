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
 *
 * @author      aquintero
 * @package		Joomla
 * @subpackage	ztelecliente
 * @since 1.6
 */


		
class ModelExcel extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	
	function importarContactosGrupo(){
		jimport('joomla.filesystem.file');
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		
		//Obtiene datos enviados
		$grupo = JRequest::getVar('grupo'); 
		$data = JRequest::getVar('archivo', null, 'files'); 
		$nombre = JFile::makeSafe($data['name']);
		
		
		if( $nombre != "" ){//&& $grupo != "" ){
			$nombreArchivo = $this->guardarArchivoImportar();
			echo $nombreArchivo; 
			exit;
			//$this->procesarArchivo($row);
			return JText::_('M_OK') . sprintf( JText::_('AD_CARGAR_INFORME_OK'));
		}
		else{
			return JText::_('M_ERROR'). JText::_('AD_CARGAR_INFORME_ERROR_DATOS');
		}
		
	}
	
	public function guardarArchivoImportar(){
		jimport('joomla.filesystem.file');
		//Guarda adjunto
		$fechaHoy = date('Y-m-d');
		$data 	= JRequest::getVar('archivo', null, 'files'); 
		$nombre = JFile::makeSafe($data['name']);
		$nombre = JFile::stripExt($nombre);
		$ext 	=  JFile::getExt($data['name']);
		$nombreArchivo = "{$fechaHoy}_{$nombre}";
		FileHelper::guardarArchivo($data, "images/archivos/", $nombreArchivo );
		return $nombreArchivo;
	}
	
	
	public function procesarInforme( $row ){
		print_r($row);
		$db = & JFactory::getDBO();
		
		//Abre archivo excel
		$excelFile = ZExcelHelper::openExcel("images/informes/" . $row->archivo );
		
		//Lee datos
		$fila = 10;
		$cuenta = "";
		
		ZDBHelper::initTransaction($db);
		while( $fila == 10 || $cuenta != "" ){
			$col = 1;
			$tmp = "";
			
			//mysql_real_escape_string
			$cuenta = ZExcelHelper::readExcel($excelFile, $fila, 1);
			$desc 	= ZExcelHelper::readExcel($excelFile, $fila, 2);
			$cgn 	= ZExcelHelper::readExcel($excelFile, $fila, 3);
			$dcgn 	= ZExcelHelper::readExcel($excelFile, $fila, 4);
			$vc 	= ZExcelHelper::readExcel($excelFile, $fila, 5);
			$vnc 	= ZExcelHelper::readExcel($excelFile, $fila, 6);
			
			if( $cuenta != ""){
				$query = "INSERT INTO jos_zmovimientos(cuenta,descripcion,codigo_cgn,nombre_cgn,valor_corriente,valor_no_corriente,informe) 
				          VALUES('%s','%s','%s','%s', %s, %s, %s )";
						
				$query = sprintf( $query, $cuenta, $desc, $cgn, $dcgn, $vc, $vnc, $row->id);
			
				
				$db->setQuery($query);
				$result = $db->query();
				if( !$result ){
					ZDBHelper::rollBack($db);
					$fila = 11;
					break;
				}
				if( ! $this->existeUsuario($cgn) ){
					$this->crearUsuario($cgn, $dcgn );
				}
				
				$id  = $db->insertid();
				
			}
			
			
			$fila++;
		}
		
		ZDBHelper::commit($db);
		
		$row->movimientos = $fila - 11;
		$row->store();
		
		//ZExcelHelper::getData("images/informes/" . $row->archivo );
		
	}
	
	
	function getColumns($data){
		$columnsArray = array();
		foreach($data as $column => $value){
			$columnsArray[] = array(
									'description' => $column,
									'property' => $column
								   );
		}
		//echo "<br/><br/>";
		//print_r($columnsArray);
		return $columnsArray;
	}
	
	function createExcelFile($name, $data, $columns){
	
		$nombre = substr($name, 0 , 10);
		$nombre = str_replace(" ", "" , $nombre);
		$fecha = date("Y-m-d");
		$excelName = "{$name}_{$fecha}.xls";
		
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
	
	
}









