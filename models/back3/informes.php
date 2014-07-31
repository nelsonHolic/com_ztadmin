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


		
class ModelInformes extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	
	function importarContactosGrupo(&$nombre){
		jimport('joomla.filesystem.file');
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		
		//Obtiene datos enviados
		$grupo  = JRequest::getVar('grupo'); 
		$data   = JRequest::getVar('archivo', null, 'files'); 
		$nombre = JFile::makeSafe($data['name']);
		
		
		if( $nombre != "" && $grupo != "" ){
			$nombreArchivo = $this->guardarArchivoImportar();
			$this->procesarInforme( $nombreArchivo, $grupo );
			$nombre = $nombreArchivo;
			return JText::_('M_OK') . sprintf( JText::_('VE_CARGAR_CONTACTOS_GRUPO_OK'));
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_CARGAR_CONTACTOS_GRUPO_ERROR');
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
		return $nombreArchivo.".$ext";
	}
	
	
	public function procesarInforme( $nombreArchivo, $grupo ){
		//Obtiene db
		$db = JFactory::getDBO();
		
		//Obtiene usuario actual
		$user = JFactory::getUser();
		
		//Obtiene fecha actual
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');
		
		//Establece configuracion del servidor para evitar errores
		ini_set('memory_limit', '512M');
		set_time_limit(0);

		//Abre archivo excel
		$excelFile = ZExcelHelper::openExcel("images/archivos/" . $nombreArchivo );
		
		//Lee datos
		$fila = 2;
		$nombre = "";
		
		ZDBHelper::initTransaction($db);
		while( $fila == 2 || $nombre != "" ){
			$col = 1;
			$tmp = "";
			
			$nombre 	   = ZExcelHelper::readExcel($excelFile, $fila, 0);
			$telefono 	   = ZExcelHelper::readExcel($excelFile, $fila, 1);
			$telefono      = Mensajes::limpiarNumero($telefono);
			$correo 	   = ZExcelHelper::readExcel($excelFile, $fila, 2);
			
			
			if( $nombre != "" && Mensajes::esNumeroValido( $telefono )  ){
         
            $contacto = Mensajes::getContacto( $telefono );
            $contacto = isset($contacto->id) ? $contacto->id : 0 ;
            if( $contacto == 0){
               //Guarda el contacto en el sistema
               $query = "INSERT INTO jos_zmcontactos( nombre, movil, correo, fecha_creacion, usuario) 
                       VALUES('%s','%s','%s','%s', %s)";
                     
               $query = sprintf( $query, $nombre, $telefono, $correo, $fechaHoy, $user->id );
               $db->setQuery($query);
               $result = $db->query();
               //echo $query;
               
               if( $result !== false ){
                  $contacto  = $db->insertid();
               }
            }
            
            if($contacto > 0){
               //Guarda el contacto en el grupo
               if($grupo != "" && !Grupo::existeContacto($grupo, $contacto) ){
                  $query = "INSERT INTO jos_zmgrupocontacto( grupo, contacto) 
                     VALUES(%s, %s)";
                  $query = sprintf( $query, $grupo, $contacto );
                  $db->setQuery($query);
                  $result = $db->query();
                  if( $result === false ){
                     ZDBHelper::rollBack($db);
                     $fila = 2;
                     break;
                  }
               }
               ZExcelHelper::writeExcel($excelFile, $fila, 3, "OK");
            }else{
               //ZDBHelper::rollBack($db);
               ZExcelHelper::writeExcel($excelFile, $fila, 3, "ERROR BD");
               //$fila = 2;
               //break;
            }	
			}
			else{
				if($nombre != ""){
					ZExcelHelper::writeExcel($excelFile, $fila, 3, "ERROR NUMERO");
				}
			}
			$fila++;
		}
		
		ZDBHelper::commit($db);
		ZExcelHelper::saveExcel($excelFile, "images/archivoslog/" . $nombreArchivo . ".xls" );
		
		//$row->movimientos = $fila - 2;
		//$row->store();
		
		//ZExcelHelper::getData("images/informes/" . $row->archivo );
		
	}
	
	
	
	function importarContactos(&$nombre){
		jimport('joomla.filesystem.file');
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');
		
		//Obtiene datos enviados
		$data = JRequest::getVar('archivo', null, 'files'); 
		$nombre = JFile::makeSafe($data['name']);
		
		
		if( $nombre != ""){
			$nombreArchivo = $this->guardarArchivoImportar();
			$this->procesarInforme( $nombreArchivo, "" );
			$nombre = $nombreArchivo;
			return JText::_('M_OK') . sprintf( JText::_('VE_CARGAR_CONTACTOS_OK'));
		}
		else{
			return JText::_('M_ERROR'). JText::_('VE_CARGAR_CONTACTOS_ERROR');
		}
		
	}
	
	
	
}









