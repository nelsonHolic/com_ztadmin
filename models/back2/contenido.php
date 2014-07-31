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
		
class ModelContenido extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function listarCategorias($filtro, $estado, $inicio, $registros){
	
		$db = Configuration::getTiendaDB();

		$tbCategorias = $db->nameQuote('#__categories');
		$query = "SELECT 
						*
				  FROM 
						$tbCategorias
				  WHERE
						title like '%s' AND
						extension = 'com_content'
				  ORDER BY
						title
						";
		
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarCategorias($filtro, $estado){
		$db = Configuration::getTiendaDB();

		$tbCategorias = $db->nameQuote('#__categories');
		$query = "SELECT 
						count(*)
				  FROM 
						$tbCategorias
				 WHERE
						title like '%s' AND
						extension = 'com_content'
						";
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getItem($id){
	
		$db = Configuration::getTiendaDB();
		$tbCategorias = $db->nameQuote('#__categories');
		$query = "SELECT 
						*
				  FROM 
						$tbCategorias 
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	public function guardarCategoria($id, $nombre){
	
	
		$db = Configuration::getTiendaDB();
		$nombre =  mysql_real_escape_string($nombre);
		
		if($id == ""){
			//Crear nueva categoria
			$id = $this->crearNuevaCategoria();
		}
		
		$tbCategorias = $db->nameQuote('#__categories');
		$query = "
			  UPDATE
					$tbCategorias
			  SET 
					title = '%s',
					path = '%s',
					alias = '%s'
			  WHERE 
					id = %s
					";
		$path = mysql_real_escape_string(str_replace(" " , "-", $nombre));
		$query = sprintf( $query, $nombre, $path, $path,  $id);
		$db->setQuery($query);
		$result = $db->query();
		
		if($result){
			return JText::_('M_OK'). JText::_('CONTENIDOS_CATEGORIAS_GUARDAR');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
		
	}
	
	public function crearNuevaCategoria(){
	
		$db = Configuration::getTiendaDB();
		
		$tbAssets = $db->nameQuote('#__assets');
		$query = "
				INSERT  INTO
					$tbAssets
			    VALUES(
				0,8,94,95,2,'com_content.category','','{\"core.create\":[],\"core.delete\":[],\"core.edit\":[],\"core.edit.state\":[],\"core.edit.own\":[]}'
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		$id = $db->insertid();
		
		
		$tbCategorias = $db->nameQuote('#__categories');
		$query = "
				INSERT  INTO
					$tbCategorias(
						id, asset_id, parent_id, lft, rgt, level,
						path, extension, title, alias, published, checked_out,
						checked_out_time, access, params, metadata, created_time,
						language
					)
			    VALUES(
					0,$id,1,40,40,1,
					'', 'com_content', '','',1,0,
					CURDATE(),1,'{\"category_layout\":\"\",\"image\":\"\"}', '{\"author\":\"\",\"robots\":\"\"}',CURDATE(),
					'*'
				)";
		$db->setQuery($query);
		$result = $db->query();
		$id = $db->insertid();
		return $id;
	}
	
	public function eliminarCategoria( $id ){
		$db = Configuration::getTiendaDB();
		
		$contenidosAsociados = $this->getContenidosCategoria($id);
		if($contenidosAsociados == 0){
		
			$assetId = $this->getAsset($id);
			
			//Borra la categoria
			$tbCategorias = $db->nameQuote('#__assets');
			$query = "DELETE FROM $tbCategorias WHERE id = %s";
			$query = sprintf( $query, $assetId);
			$db->setQuery($query);
			$result = $db->query();
			
			$tbCategorias = $db->nameQuote('#__categories');
			$query = "DELETE FROM $tbCategorias WHERE id = %s";
			$query = sprintf( $query, $id);
			$db->setQuery($query);
			$result = $db->query();
		
			if($result){
				return JText::_('M_OK'). JText::_('PRODUCTOS_CATEGORIAS_ELIMINAR');
			}
			else{
				return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
			}
		}
		else{
			return JText::_('M_ERROR') .  JText::_('CONTENIDOS_CATEGORIAS_CONTENIDO_ASOCIADO');
		}
		
	}
	
	function getContenidosCategoria($categoria){
		$db = Configuration::getTiendaDB();
		$tbContent = $db->nameQuote('#__content');
		$query = "SELECT 
						count(*)
				  FROM 
						$tbContent 
				  WHERE 
						catid = $categoria ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getAsset($id){
		$db = Configuration::getTiendaDB();
		$tbCategories = $db->nameQuote('#__categories');
		$query = "SELECT 
						asset_id
				  FROM 
						$tbCategories 
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	//Contenidos ******************************************************************************
	
	function listar($filtro, $categoria, $inicio, $registros){
		$db = Configuration::getTiendaDB();

		$tbContent = $db->nameQuote('#__content');	
		$filtroCategoria = ($categoria != "") ? " AND catid = $categoria " : "";
		$query = "SELECT 
						*
				  FROM 
						$tbContent as co
				  WHERE
						co.title like '%s' 
						$filtroCategoria
				  ORDER BY
						title	
						";
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro, $categoria){
		$db = Configuration::getTiendaDB();

		$tbContent = $db->nameQuote('#__content');	
		$filtroCategoria = ($categoria != "") ? " AND catid = $categoria " : "";
		$query = "SELECT 
						count(*)
				  FROM 
						$tbContent as co
				  WHERE
						co.title like '%s' 
						$filtroCategoria
						";
		
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	function getContenido($id){
		$db = Configuration::getTiendaDB();
		$tbContent = $db->nameQuote('#__content');
		$query = "SELECT 
						*
				  FROM 
						$tbContent
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	public function guardarContenido($id, $nombre, $categoria, $descCorta, $descCompleta ){
		$db = Configuration::getTiendaDB();
		
		//TODO realizar validaciones de datos obligatorios
		if(  $nombre != "" && $descCorta != "" ){
		
			if($id == 0){
				$id = $this->crearNuevoContenido();
			}	
			
			//Corregir problema con url en tiny mce
			$descCorta = str_replace( "src=\"http://mitiendaadmin.tusconsultores.comes/" , "src=\"http://mitienda.tusconsultores.com/images/", $descCorta);
			$descCompleta = str_replace( "src=\"http://mitiendaadmin.tusconsultores.comes/" , "src=\"http://mitienda.tusconsultores.com/images/", $descCompleta);
			
			
			//Escapar datos
			$nombre = mysql_real_escape_string($nombre); 
			$alias =  mysql_real_escape_string(str_replace(" " , "-", $nombre));
			$descCorta = mysql_real_escape_string($descCorta); 
			$descCompleta = mysql_real_escape_string($descCompleta); 
		
			//Guarda en la tabla de productos	
			$tbContent = $db->nameQuote('#__content');
			$query = "
					UPDATE
						$tbContent
					SET
						title = '%s',
						alias = '%s',
						catid = %s,
						introtext = \"%s\",
						`fulltext` = \"%s\"
					WHERE
						id = %s
						";
			$query = sprintf( $query, $nombre, $alias, $categoria, $descCorta, $descCompleta, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			return JText::_('M_OK'). JText::_('CONTENIDOS_GUARDAR_CONTENIDO');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('ERROR_DATOS_FALTANTES');
		}
	}
	
	function guardarImagenProducto($nombre, $tipo, $imagen, $thumb){
	
	
		$db = Configuration::getTiendaDB();
		$tbMedia = $db->nameQuote('#__virtuemart_medias');
		$query = "
				INSERT  INTO
					$tbMedia(virtuemart_vendor_id, file_title, file_mimetype, file_type, file_url, file_url_thumb, published, created_on, created_by )
			    VALUES(
					1, '%s' , '%s', 'product', '%s', '%s', 1 , CURDATE(), 42 
				)
					";
		$query = sprintf( $query, mysql_real_escape_string($nombre) , mysql_real_escape_string($tipo), $imagen, $thumb );
		$db->setQuery($query);
		$result = $db->query();
		$id = $db->insertid();
		return $id;
	}
	
	 
	
	public function crearNuevoContenido(){
		$db = Configuration::getTiendaDB();
		
		//Guarda en la tabla de productos
		$tbContent = $db->nameQuote('#__content');
		$query = "
				INSERT  INTO
					$tbContent
				(
					id, asset_id, title, alias, title_alias,
					introtext, `fulltext`, state, catid, created,
					attribs, access, language
				)
			    VALUES(
				0,0,'','','',
				'','',1,0,CURDATE(),	'{\"show_title\":\"\",\"link_titles\":\"\",\"show_intro\":\"\",\"show_category\":\"\",\"link_category\":\"\",\"show_parent_category\":\"\",\"link_parent_category\":\"\",\"show_author\":\"\",\"link_author\":\"\",\"show_create_date\":\"\",\"show_modify_date\":\"\",\"show_publish_date\":\"\",\"show_item_navigation\":\"\",\"show_icons\":\"\",\"show_print_icon\":\"\",\"show_email_icon\":\"\",\"show_vote\":\"\",\"show_hits\":\"\",\"show_noauth\":\"\",\"alternative_readmore\":\"\",\"article_layout\":\"\"}', 1, '*'
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		$id = $db->insertid();
		return $id;
	}
	
	
	public function eliminarContenido( $id ){
		$db = Configuration::getTiendaDB();	
		
		$tbContenido = $db->nameQuote('#__content');
		$query = "DELETE FROM $tbContenido WHERE id = %s";
		$query = sprintf( $query, $id);
		$db->setQuery($query);
		$result = $db->query();
	
		if($result){
			return JText::_('M_OK'). JText::_('PRODUCTOS_ELIMINAR_ITEM');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}

	
	
}










