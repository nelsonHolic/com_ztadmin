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
		
class ModelEnlacesInferiores extends JModel{


    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function getInfo($id){
		$db = Configuration::getTiendaDB();

		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = %s
						";
		$query = sprintf( $query, $db->quote($id) );
		$db->setQuery($query);
	    $result = $db->loadObject();	
		$enlaces = $this->procesarEnlaces($result->content);
		$result->enlaces = $enlaces;
		return $result;
	}

	
	function procesarEnlaces($content){
		$content = str_replace("<ul>", "" , $content);
		$content = str_replace("</ul>", "" , $content);
		$content = str_replace("</li>", "" , $content);
		$content = explode("<li>", $content);
		
		$enlaces = array();
		$pos = 0;
		foreach( $content as $enlace){
			
			if( strlen($enlace) >= 5 ){
				$texto = explode(">", $enlace);
				$texto = $texto[1];
							
				$link = explode("\"", $enlace);
				$link = isset($link[1]) ? $link[1] : "";
				
				$texto = str_replace("</a", "" , $texto);
				$enlaces[$pos][] = $link;
				$enlaces[$pos][] = $texto;
			
				$pos = $pos + 1;
			}
		}
		
		return $enlaces;
	}
	
	function guardarEnlaces($id){
		$data	= JRequest::get('post');
		$tituloModulo = $data['titulo'];
		
		if($tituloModulo != ""){
			$enlace ="<ul>";
			
			//Procesa enlace 1
			if($data['titulo1'] != "" ){
				$titulo = $data['titulo1'];
				$tipo1 = $data['tipo1'];
				$tipoContenido1 = $data['tipoContenido1'];
				
				$lnk =  ($tipo1 != "") ? $this->crearEnlace($tipo1, $tipoContenido1) : "";
				
				//Guarda enlace anterior si no han seleccionado uno nuevo
				$lnk = ($lnk == "") ? $data['actual1'] : $lnk;
			
				$enlace .= "<li><a href=\"$lnk\">$titulo</a></li>";
				
			}
			
			//Procesa enlace 2
			if($data['titulo2'] != "" ){
				$titulo = $data['titulo2'];
				$tipo2 = $data['tipo2'];
				$tipoContenido2 = $data['tipoContenido2'];
				
				$lnk =  ($tipo2 != "") ? $this->crearEnlace($tipo2, $tipoContenido2) : "";
				
				//Guarda enlace anterior si no han seleccionado uno nuevo
				$lnk = ($lnk == "") ? $data['actual2'] : $lnk;
				
				$enlace .= "<li><a href=\"$lnk\">$titulo</a></li>";
			}
			
			//Procesa enlace 3
			if($data['titulo3'] != "" ){
				$titulo = $data['titulo3'];
				$tipo3 = $data['tipo3'];
				$tipoContenido3 = $data['tipoContenido3'];
				$lnk =  ($tipo3 != "") ? $this->crearEnlace($tipo3, $tipoContenido3) : "";
				//Guarda enlace anterior si no han seleccionado uno nuevo
				$lnk = ($lnk == "") ? $data['actual3'] : $lnk;
				$enlace .= "<li><a href=\"$lnk\">$titulo</a></li>";
			}
			
			//Procesa enlace 4
			if($data['titulo4'] != "" ){
				$titulo = $data['titulo4'];
				$tipo4 = $data['tipo4'];
				$tipoContenido4 = $data['tipoContenido4'];
				$lnk =  ($tipo4 != "") ? $this->crearEnlace($tipo4, $tipoContenido4) : "";
				//Guarda enlace anterior si no han seleccionado uno nuevo
				$lnk = ($lnk == "") ? $data['actual4'] : $lnk;
				$enlace .= "<li><a href=\"$lnk\">$titulo</a></li>";
				
			}
			
			//Procesa enlace 5
			if($data['titulo5'] != "" ){
				$titulo = $data['titulo5'];
				$tipo5 = $data['tipo5'];
				$tipoContenido5 = $data['tipoContenido5'];
				$lnk =  ($tipo5 != "") ? $this->crearEnlace($tipo5, $tipoContenido5) : "";
				//Guarda enlace anterior si no han seleccionado uno nuevo
				$lnk = ($lnk == "") ? $data['actual5'] : $lnk;
				$enlace .= "<li><a href=\"$lnk\">$titulo</a></li>";
				
			}
			
			$enlace .= "<ul>";
			
			$db = Configuration::getTiendaDB();
			$tbModules = $db->nameQuote('#__modules');
			$query = "
				  UPDATE
						$tbModules 
				  SET 
						title = %s,
						content = %s
				  WHERE 
						published = 1 AND 
						id = %s
						";
			$query = sprintf( $query,  $db->quote($tituloModulo),  $db->quote($enlace), $id );
			$db->setQuery($query);
			echo htmlentities($query);
			$result = $db->query();
			return JText::_('M_OK') . JText::_('ENLACESI_FORM_EDITAR_GUARDAR_OK');
		}
		else{
			return JText::_('M_ERROR') . JText::_('ENLACESI_FORM_EDITAR_GUARDAR_ERR');
		}
	}
	
	function crearEnlace($tipo, $datos){
		$enlace = "";
		
		if($tipo == "AR"){
			$enlace = "index.php?option=com_content&view=article&id=$datos";
		}
		else if($tipo == "CP"){
			$enlace = "index.php?option=com_virtuemart&view=category&virtuemart_category_id=$datos";
		}
		else if($tipo == "PT"){
			$enlace = "option=com_virtuemart&view=virtuemart";
		}
		else if($tipo == "CA"){
			$enlace = "index.php?option=com_content&view=category&id=$datos";
		}
		else if($tipo == "BL"){
			$enlace = "index.php?option=com_content&view=category&layout=blog&id=$datos";
		}
		else if($tipo == "CO"){
			$enlace = "index.php?option=com_zcontacto&task=contactoForm&id=$datos";
		}
		else if($tipo == "EX"){
			$enlace = $datos;
		}
		
		return $enlace;
	}

	
}
	











