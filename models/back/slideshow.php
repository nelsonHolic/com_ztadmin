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
		
class ModelSlideshow extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/*Ruta a las imagenes del slideshow*/
	public static function rutaSlideshowTienda(){
		$ruta = "../celured/images/slideshow/";
		return $ruta;
	}
	
	/*Ruta a las imagenes del slideshow*/
	public static function rutaInternaSlideshow(){
		$ruta = "images/slideshow/";
		return $ruta;
	}
	
	
	function listar(){
		$db = Configuration::getTiendaDB();

		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = 433
					
						";
		$db->setQuery($query);
	    $result = $db->loadObject();
		
		$params = json_decode($result->params);
		$images = json_decode($params->image_show_data);
		//print_r($params);
		
		//Recorre imagenes
		$posicion = 1;
		foreach($images as $image){
			$image->tipo = $this->getTipo($image->type);
			$image->posicion = $posicion;
			/*echo "nombre =" . $image->name . "<br/>";
			echo "type =" . $image->type . "<br/>";
			echo "image =" . $image->image . "<br/>";
		    echo "strecth =" . $image->stretch . "<br/>";
			echo "art_id =" . $image->art_id . "<br/>";
			echo "art_title =" . $image->art_title . "<br/>";
			*/
			
			//Crea una imagen reducida para mostrar en listado
			$nombreImagen = explode("/",$image->image);
			Image::resize( Configuration::rutaImagenesTienda() . $image->image, Configuration::rutaImagenes() . "temp/" . $nombreImagen[2] , 200 );
			
			$image->nombreImagen = $nombreImagen[2];
			$posicion = $posicion + 1;
		}
		
		return $images;
		
	}
	
	function getImage($indice){
	
		$db = Configuration::getTiendaDB();

		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = 433
					
						";
		$db->setQuery($query);
	    $result = $db->loadObject();
		
		$params = json_decode($result->params);
		$images = json_decode($params->image_show_data);
		
		$posicion = 1;
		foreach($images as $image){
			if($indice == $posicion){
				$nombreImagen = explode("/",$image->image);
				$image->nombreImagen = $nombreImagen[2];
				$image->contenidoActual = ( strcmp($image->type, "article") == 0 ) ? $image->art_id . " - " . $image->art_title : "" ;
				return $image;
			}
			$posicion = $posicion + 1;
		}
		
		return "";
	}
	
	function getTipo($tipo){
	
		if( $tipo == "text"){
			return "Texto";
		}
		else if($tipo == "article"){
			return "Art&iacute;culo";
		}
	}
	
	
	
	function saveImage($id, $titulo, $ajustable, $tipo, $datos, $imagen, $url){
		jimport('joomla.filesystem.file');
		$db = Configuration::getTiendaDB();
		
		//Validaciones de datos
		if($titulo == "") return JText::_('M_ERROR') . JText::_('SLIDESHOW_ERROR_CAMPOS_OBLIGATORIOS');
		if(  strcmp( $imagen['name'], "" ) == 0 ) return JText::_('M_ERROR') . JText::_('SLIDESHOW_ERROR_IMAGEN_VACIA');
		
		//Obtiene datos de las imagenes
		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = 433
						";
		$db->setQuery($query);
	    $result = $db->loadObject();
		
		$params = json_decode($result->params);
		$images = json_decode($params->image_show_data, true);
		
		//Guarda la nueva imagen al final
		$image = new StdClass();
		$image->name = $titulo;
		$image->stretch = $ajustable;
		$image->access = "public";
		$image->published = 1;
		$image->content = "";
		$image->artK2_id = "";
		$image->artK2_title = "";

		if($tipo == "AR" && $url == ""){
			$image->art_id = $datos;
			$image->art_title = $this->getTituloArticulo($datos) ;
			$image->type = "article";
		}
		else{
			$image->type = "text";
			$image->art_id = "";
			$image->art_title = "Enlace";
			$image->url = $url;
		}
		$tmp 	= $imagen['tmp_name'];
		$nombre = $imagen['name'];
		$rutaSlideshow 	= ModelSlideshow::rutaSlideshowTienda();
		$nombreImagen 	= $rutaSlideshow . "/" . $nombre;
		JFile::copy( $tmp, $nombreImagen );
		Image::resize($nombreImagen, $nombreImagen, 1000 , 500 );
		$image->image = ModelSlideshow::rutaInternaSlideshow() . $nombre;
		
		//Guarda nueva imagen
		$images[] = $image;
		$params->image_show_data = json_encode($images);
		$params = json_encode($params);
		$this->actualizarImagenes($params);
		return JText::_('M_OK') . JText::_('SLIDESHOW_GUARDAR_OK');
		
		
	}
	
	
	function updateImage($id, $titulo, $ajustable, $tipo, $datos, $imagen, $url){
		jimport('joomla.filesystem.file');
		$db = Configuration::getTiendaDB();

		//Validaciones de datos
		if($titulo == ""){
			return JText::_('M_ERROR') . JText::_('SLIDESHOW_ERROR_CAMPOS_OBLIGATORIOS');
		}
		
		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = 433
						";
		$db->setQuery($query);
	    $result = $db->loadObject();
		
		$params = json_decode($result->params);
		$images = json_decode($params->image_show_data);
		
		echo "buscando id = $id";
		$posicion = 1;
		foreach($images as &$image){
			
			if($id == $posicion){
				$nombreImagen = explode("/",$image->image);
				//$image->nombreImagen = $nombreImagen[1];
				//$image->contenidoActual = ( strcmp($image->type, "article") == 0 ) ? $image->art_id . " - " . $image->art_title : "" ;
				
				$image->name = $titulo;
				$image->stretch = $ajustable;
				
				if($tipo == "AR" && $url == ""){
					$image->art_id = $datos;
					$image->art_title = $this->getTituloArticulo($datos) ;
					$image->type = "article";
				}
				else{
					$image->type = "text";
					$image->art_id = "";
					$image->art_title = "Enlace";
					$image->url = $url;
				}
				
				//Actualiza la imagen solo si fue enviada
				if( strcmp( $imagen['name'], "" ) != 0   ){
					//Actualiza la imagen
					$tmp 	= $imagen['tmp_name'];
					$nombre = $imagen['name'];
					
					$rutaSlideshow 	= ModelSlideshow::rutaSlideshowTienda();
					$nombreImagen 	= $rutaSlideshow . "/" . $nombre;
					JFile::copy( $tmp, $nombreImagen );
					Image::resize($nombreImagen, $nombreImagen, 1000 , 500 );
					$image->image = ModelSlideshow::rutaInternaSlideshow() . $nombre;
					break;
				}
			}
			
			$posicion = $posicion + 1;
		}
		
		print_r($images);
		
		$params->image_show_data = json_encode($images);
		$params = json_encode($params);
		$this->actualizarImagenes($params);
		
		return JText::_('M_OK') . JText::_('SLIDESHOW_GUARDAR_OK');
	}
	
	
	function eliminarImage($id){
	
		jimport('joomla.filesystem.file');
		$db = Configuration::getTiendaDB();

		$tbModules = $db->nameQuote('#__modules');
		$query = "SELECT 
						* 
				  FROM 
						$tbModules 
				  WHERE 
						published = 1 AND 
						id = 433
						";
		$db->setQuery($query);
	    $result = $db->loadObject();
		
		$params = json_decode($result->params);
		$images = json_decode($params->image_show_data, true);
		
		echo "buscando id = $id";
		print_r($images);
		$posicion = 1;
		foreach($images as $key => &$image){
			if($id == $posicion){
				echo "<br/><br/><br/>";
				print_r($images[$key]);
				echo "<br/><br/><br/>";
				unset($images[$key]);
			}
			$posicion = $posicion + 1;
		}
		
		
		echo "<br/><br/><br/>";
		print_r($images);
		
		$images = json_encode($images);
		$images = json_decode($images);
		$params->image_show_data = json_encode($images);
		$params = json_encode($params);
		$this->actualizarImagenes($params);
		
		return JText::_('M_OK') . JText::_('SLIDESHOW_GUARDAR_OK');
	}

	
	function actualizarImagenes($params){
	
		$db = Configuration::getTiendaDB();
		$tbModules = $db->nameQuote('#__modules');
		
		$params =  mysql_real_escape_string($params);
		$query = "
			  UPDATE
					$tbModules 
			  SET 
					params = '$params'
			  WHERE 
					published = 1 AND 
					id = 433
					";
		
		$db->setQuery($query);
		$result = $db->query();
		
	}
	
	function getTituloArticulo($id){
		$db = Configuration::getTiendaDB();

		$tbArticles = $db->nameQuote('#__content');
		$query = "SELECT 
						title 
				  FROM 
						$tbArticles
				  WHERE 
						id = $id
					
						";
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result->title;
	}
}
	











