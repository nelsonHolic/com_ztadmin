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


		
class ModelLogo extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function actualizarLogo($data){
		jimport('joomla.filesystem.file');
		$nombre = JFile::makeSafe($data['name']);
		$tmp 	= $data["tmp_name"];
		
		//Copia logo en carpeta temporal
		$nombreTmp = "images/files/" . $nombre;
		JFile::copy($tmp,$nombreTmp);
		
		$nombre = "logo.png";
		$dest = "../celured/templates/gk_bikestore/images/style1/" . DS . $nombre ;
		
		//Redimensionar imagen antes de copiar
		if(Image::resize($nombreTmp, $dest, 175 , 0 )){
			return JText::_("M_OK") . JText::_("LOGO_CAMBIAR");
		}
		else{
			return JText::_("M_ERR") . JText::_("PROCESO_ERROR");
		}

	}
	
	function recuperarLogo(){
		jimport('joomla.filesystem.file');
		$logoOriginal = "../celured/templates/gk_bikestore/images/style1/logo_bck.png"; 
		$logo = "../celured/templates/gk_bikestore/images/style1/logo.png";
		if(JFile::copy($logoOriginal, $logo)){
			return JText::_("M_OK") . JText::_("LOGO_RECUPERAR");
		}
		else{
			return JText::_("M_ERR") . JText::_("PROCESO_ERROR");
		}
		
	}
	
	
	
}










