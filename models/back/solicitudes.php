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


		
class ModelSolicitudes extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	/*
	* Solicitudes
	*/
	function listar($filtro, $estado, $inicio, $resultados){
		$db = & JFactory::getDBO();
		
		$tbSolicitudes = $db->nameQuote('#__zcambio_servicios');
		$tbEstados = $db->nameQuote('#__zestados');
		$user = JFactory::getUser();
		
		$estado = ($estado != "") ?  " AND estado = '$estado' " : ""; 
		$query = "
					SELECT
						zsolicitudes.* , estados.descripcion as descripcionEstado
					FROM 
						$tbSolicitudes as zsolicitudes,
						$tbEstados as estados
					WHERE
						zsolicitudes.usuario_registro = {$user->id} AND
						zsolicitudes.estado = estados.estado AND 
						(
							lower(lineas) LIKE lower('%$filtro%') 
						)
						$estado
						
					ORDER BY fecha_registro DESC
						";
		//echo  $query;
		$db->setQuery($query, $inicio, $resultados);
	    $result = $db->loadObjectList();
		foreach($result as $row){
			$row->mensajeCambio = $this->mensajeCambio($row);
		}
		return $result;
	}
	
	/*
	* Solicitudes
	*/
	function contar($filtro, $estado){
		$db = & JFactory::getDBO();
		
		$tbSolicitudes = $db->nameQuote('#__zcambio_servicios');
		$user = JFactory::getUser();
		
		$estado = ($estado != "") ?  " AND estado = '$estado' " : ""; 
		$query = "
					SELECT
						count(*)
					FROM 
						$tbSolicitudes as zsolicitudes
					WHERE
						zsolicitudes.usuario_registro = {$user->id} AND
						(
							lower(lineas) LIKE lower('%$filtro%') 
						)
						$estado
						";
		//echo  $query;
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function mensajeCambio($row){
		$msg = "";
		
		if( $row->local == 1){
			$msg .=   JText::_('IPCENTREX_CAT_LOCAL_TOOLTIP') . " + " ;
		}
		
		if( $row->nacional == 1){
			$msg .=   JText::_('IPCENTREX_CAT_DDN_TOOLTIP') . " + " ;
		}
		
		if( $row->internacional == 1){
			$msg .=   JText::_('IPCENTREX_CAT_DDI_TOOLTIP') . " + " ;
		}
		
		if( $row->celular == 1){
			$msg .=   JText::_('IPCENTREX_CAT_CEL_TOOLTIP') . " + " ;
		}
		
		if( $row->c901 == 1){
			$msg .=   JText::_('IPCENTREX_CAT_901_TOOLTIP') . " + " ;
		}
		
		if( $row->c113 == 1){
			$msg .=   JText::_('IPCENTREX_CAT_113_TOOLTIP') . " + " ;
		}
		
		if( $row->il == 1){
			$msg .=   JText::_('IPCENTREX_SER_IL_TOOLTIP') . " + " ;
		}
		
		if( $row->ct == 1){
			$msg .=   JText::_('IPCENTREX_SER_CT_TOOLTIP') . " + " ;
		}
		
		if( $row->gt == 1){
			$msg .=   JText::_('IPCENTREX_SER_GT_TOOLTIP') . "[{$row->grupo_timbrado}] + " ;
		}
		
		if( $row->tt == 1){
			$msg .=   JText::_('IPCENTREX_SER_TE_TOOLTIP') . "[{$row->minutos_espera}] + " ;
		}
		
		return substr($msg, 0, strlen($msg) -2 );
		
	}
	
	
	
	
	
	
}









