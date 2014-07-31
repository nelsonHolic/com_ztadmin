<?php
/**
 * Logo
 *
 * @version $Id:  
 * @author Andres Quintero
 * @package Joomla
 * @subpackage zschool
 * @license GNU/GPL
 *
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


		
class ModelFondo extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function actualizarFondo($data){	
		jimport('joomla.filesystem.file');
		$nombre = JFile::makeSafe($data['name']);
		$tmp 	= $data["tmp_name"];
		
		//Copia fondo en carpeta temporal
		$nombreTmp = "images/files/" . $nombre;
		JFile::copy($tmp,$nombreTmp);
		
		$nombre = "bg_abstract.png";
		$dest = "../celured/templates/gk_bikestore/images/style1/" . DS . $nombre ;
		
		//Redimensionar imagen antes de copiar
		if(Image::resize($nombreTmp, $dest, 1910 , 996 )){
			return JText::_("M_OK") . JText::_("LOGO_CAMBIAR");
		}
		else{
			return JText::_("M_ERR") . JText::_("PROCESO_ERROR");
		}

	}
	
	function recuperarFondo(){
		jimport('joomla.filesystem.file');
		$logoOriginal = "../celured/templates/gk_bikestore/images/style1/bg_abstract_bck.png"; 
		$logo = "../celured/templates/gk_bikestore/images/style1/bg_abstract.png";
		if(JFile::copy($logoOriginal, $logo)){
			return JText::_("M_OK") . JText::_("LOGO_RECUPERAR");
		}
		else{
			return JText::_("M_ERR") . JText::_("PROCESO_ERROR");
		}
		
	}
	
	
	
}










