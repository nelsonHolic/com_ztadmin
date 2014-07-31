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
		
class ModelProducto extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function listarCategorias($filtro, $estado, $inicio, $registros){
	
		$db = Configuration::getTiendaDB();
		$tbCategorias = $db->nameQuote('#__virtuemart_categories_es_es');
		$query = "SELECT 
						*
				  FROM 
						$tbCategorias
				  WHERE
						category_name like '%s'
				  ORDER BY
						category_name	
						";
		
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarCategorias($filtro, $estado){
		$db = Configuration::getTiendaDB();

		$tbCategorias = $db->nameQuote('#__virtuemart_categories_es_es');
		$query = "SELECT 
						count(*)
				  FROM 
						$tbCategorias
				  WHERE
						category_name like '%s'
						";
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getItem($id){
		$db = Configuration::getTiendaDB();
		$tbCategorias = $db->nameQuote('#__virtuemart_categories_es_es');
		$tbCategorias2 = $db->nameQuote('#__virtuemart_category_medias');
		
		$query = "SELECT 
						c1.* , c2.*
				  FROM 
						$tbCategorias c1,
						$tbCategorias2 as c2
				  WHERE 
						c1.virtuemart_category_id = c2.virtuemart_category_id AND
						c1.virtuemart_category_id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadObject();
		$result->imagen = $this->getMedia($result->virtuemart_media_id);
		if(isset($result->imagen->file_url_thumb)){
			$result->imagen->file_url_thumb = Configuration::rutaImagenesTienda() . $result->imagen->file_url_thumb;
		}
		return $result;
	}
	
	function getMedia($id){
		$db = Configuration::getTiendaDB();
		$tbMedias = $db->nameQuote('#__virtuemart_medias');
		$query = "SELECT 
						*
				  FROM 
						$tbMedias as med
				  WHERE
					    med.virtuemart_media_id = %s
				
				 ";
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadObject();
		return $result;
	}
	
	public function guardarCategoria($id, $nombre){
		jimport('joomla.filesystem.file');
		$db = Configuration::getTiendaDB();
		
		if($id == ""){
			//Crear nueva categoria
			$id = $this->crearNuevaCategoria();
		}
		
		$tbCategorias = $db->nameQuote('#__virtuemart_categories_es_es');
		$nombre = mysql_real_escape_string($nombre);
		$slug = str_replace(" ", "-", $nombre);
		
		$query = "
			  UPDATE
					$tbCategorias
			  SET 
					category_name = '%s',
					slug = '%s'
			  WHERE 
					virtuemart_category_id = %s
					";
		$query = sprintf( $query, $nombre, $slug, $id);
		$db->setQuery($query);
		$result = $db->query();
		
		//Guardar las imagenes del producto
		$imagen = JRequest::getVar('imagen', null, 'files', 'array');
		if( $imagen['name'] != "" ){
		
			$ext =  JFile::getExt($imagen['name']);		
			//Copia imagen del producto, maximo de 1000 px de ancho
			$nombreImagen 		= Configuration::rutaImagenesTiendaArchivos() . "images/categorias/$id.$ext";
			JFile::copy( $imagen['tmp_name'], $nombreImagen );
			Image::resize($nombreImagen, $nombreImagen, 1000 , 0 );
			
			//Copia imagen thumb
			$nombreImagenThumb 	= Configuration::rutaImagenesTiendaArchivos() . "images/categorias/thumb/$id.$ext";
			JFile::copy( $imagen['tmp_name'], $nombreImagenThumb );
			Image::resize($nombreImagenThumb, $nombreImagenThumb, 150 , 150 );
			
			//Guardar en tabla de media
			$imagenProducto = $this->guardarImagenProducto($imagen['name'], $imagen['type'],  "images/categorias/$id.$ext",  "images/categorias/thumb/$id.$ext" );
			
			//Actualizar tabla de categoria
			$tbMedia = $db->nameQuote('#__virtuemart_category_medias');
			$query = "
					UPDATE
						$tbMedia
					SET
						virtuemart_media_id = %s
					WHERE
						virtuemart_category_id = %s
						";
			$query = sprintf( $query, $imagenProducto , $id );
			$db->setQuery($query);
			$result = $db->query();
		}

		if($result){
			return JText::_('M_OK'). JText::_('PRODUCTOS_CATEGORIAS_GUARDAR');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
		
	}
	
	public function crearNuevaCategoria(){
		$db = Configuration::getTiendaDB();
		
		$tbCategorias = $db->nameQuote('#__virtuemart_categories');
		$query = "
				INSERT  INTO
					$tbCategorias
			    VALUES(
				0,1,0,0,0,0,0,10,0,10,0,'','',1,0,1,CURDATE(),0,CURDATE(),0,CURDATE(),0
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		$id = $db->insertid();
		
		
		$tbCategorias = $db->nameQuote('#__virtuemart_categories_es_es');
		$query = "
				INSERT  INTO
					$tbCategorias(virtuemart_category_id, category_name, slug)
			    VALUES(
					$id,'',''
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		
		$tbCategorias = $db->nameQuote('#__virtuemart_category_categories');
		$query = "
				INSERT  INTO
					$tbCategorias
			    VALUES(
					0,0,$id,1
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		
		//Guarda en la tabla de imagenes
		$tbCategorias = $db->nameQuote('#__virtuemart_category_medias');
		$query = "
				INSERT  INTO
					$tbCategorias(virtuemart_category_id, virtuemart_media_id, ordering)
			    VALUES(
					$id, 0, 1
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		
		return $id;
	}
	
	public function eliminarCategoria( $id ){
		$db = Configuration::getTiendaDB();
		
		$productosAsociados = $this->getProductosCategoria($id);

		if($productosAsociados == 0){
		
			//Borra la categoria
			$tbCategorias = $db->nameQuote('#__virtuemart_categories_es_es');
			$query = "DELETE FROM $tbCategorias WHERE virtuemart_category_id = %s";
			$query = sprintf( $query, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			$tbCategorias = $db->nameQuote('#__virtuemart_categories');
			$query = "DELETE FROM $tbCategorias WHERE virtuemart_category_id = %s";
			$query = sprintf( $query, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			$tbCategorias = $db->nameQuote('#__virtuemart_category_categories');
			$query = "DELETE FROM $tbCategorias WHERE category_child_id = %s";
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
			return JText::_('M_ERROR') .  JText::_('PRODUCTOS_CATEGORIAS_PRODUCTOS_ASOCIADOS');
		}
		
	}
	
	function getProductosCategoria($categoria){
		$db = Configuration::getTiendaDB();
		$tbCategorias = $db->nameQuote('#__virtuemart_product_categories');
		$query = "SELECT 
						count(*)
				  FROM 
						$tbCategorias 
				  WHERE 
						virtuemart_category_id = $categoria ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	//Productos ******************************************************************************
	
	function listar($filtro, $categoria, $inicio, $registros){	
		$db = Configuration::getTiendaDB();

		$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
		$tbProductos2 = $db->nameQuote('#__virtuemart_products');
		$tbProductos3 = $db->nameQuote('#__virtuemart_product_categories');
		$tbProductos4 = $db->nameQuote('#__virtuemart_product_prices');
		
		$filtroCategoria = ($categoria != "") ? " AND p3.virtuemart_category_id = $categoria " : "";
		$query = "SELECT 
						*
				  FROM 
						$tbProductos as p1,
						$tbProductos2 as p2,
						$tbProductos3 as p3,
						$tbProductos4 as p4
				  WHERE
						product_name like '%s' AND
						p1.virtuemart_product_id = p2.virtuemart_product_id AND
						p1.virtuemart_product_id = p3.virtuemart_product_id AND
						p1.virtuemart_product_id = p4.virtuemart_product_id 
						$filtroCategoria
				  ORDER BY
						product_name	
						";
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		foreach($result as $data){
			$data->product_price =  $data->product_price +  $data->product_price * 0.16;
		}
		return $result;
	}
	
	function contar($filtro, $categoria){
		$db = Configuration::getTiendaDB();

		$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
		$tbProductos2 = $db->nameQuote('#__virtuemart_products');
		$tbProductos3 = $db->nameQuote('#__virtuemart_product_categories');
		$filtroCategoria = ($categoria != "") ? " AND p3.virtuemart_category_id = $categoria " : "";
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbProductos as p1,
						$tbProductos2 as p2,
						$tbProductos3 as p3
				  WHERE
						product_name like '%s' AND
						p1.virtuemart_product_id = p2.virtuemart_product_id AND
						p1.virtuemart_product_id = p3.virtuemart_product_id
						$filtroCategoria	
						";
		
		$query = sprintf( $query, mysql_real_escape_string("%".$filtro."%"));
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	function getProducto($id){
		$db = Configuration::getTiendaDB();
		$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
		$tbProductos2 = $db->nameQuote('#__virtuemart_products');
		$tbProductos3 = $db->nameQuote('#__virtuemart_product_categories');
		$tbProductos4 = $db->nameQuote('#__virtuemart_product_prices');
		
		$query = "SELECT 
						*
				  FROM 
						$tbProductos as p1,
						$tbProductos2 as p2,
						$tbProductos3 as p3,
						$tbProductos4 as p4
				  WHERE
						p1.virtuemart_product_id = p2.virtuemart_product_id AND
						p2.virtuemart_product_id = p3.virtuemart_product_id AND
						p2.virtuemart_product_id = p4.virtuemart_product_id AND
						p1.virtuemart_product_id = %s
						";
						
		$query = sprintf( $query, $id );			
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
		$result->product_price =  $result->product_price +  $result->product_price * 0.16;
		$imagenes = $this->getMediasProducto($id);
		$result->imagenes = $imagenes;
		return $result;
	}
	
	function getCaracteristicas($id){
		$db = Configuration::getTiendaDB();
		$tbTipos = $db->nameQuote('#__ztipo_caracteristicas');
		$tbCaracteristicas = $db->nameQuote('#__zcaracteristicas');
		
		$query = "SELECT 
						*
				  FROM 
						$tbTipos as t,
						$tbCaracteristicas as c
				  WHERE
						t.id = c.tipo
						";
						
		//$query = sprintf( $query, $id );			
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getMediasProducto($id){
		$db = Configuration::getTiendaDB();
		$tbProductos = $db->nameQuote('#__virtuemart_product_medias');
		$tbMedia 	  = $db->nameQuote('#__virtuemart_medias');
		
		$query = "SELECT 
						*
				  FROM 
						$tbProductos as p,
						$tbMedia as m
				  WHERE
						p.virtuemart_media_id =  m.virtuemart_media_id AND
						p.virtuemart_product_id = %s
				  ORDER BY 
						p.ordering
						";
	
		$query = sprintf( $query, $id );
		$db->setQuery($query);
		//echo $query;
	    $result = $db->loadObjectList();
		foreach($result as $imagen){
			$imagen->file_url_thumb = Configuration::rutaImagenesTienda() . $imagen->file_url_thumb;
		}
		return $result;
	}
	
	
	public function guardarProducto($id, $codigo, $nombre, $categoria, $precio, $cantidad, $descripcion, $descripcion_completa,
									$eliminarImg2, $eliminarImg3, $eliminarImg4){
		jimport('joomla.filesystem.file');
		$db = Configuration::getTiendaDB();
		
		//TODO realizar validaciones de datos obligatorios
		if( $codigo != "" && $nombre != "" && $precio != "" && $cantidad != "" && $descripcion != "" ){
			//Actualiza el precio para no incluir iva
			$precio = $precio / 1.16;
			
			if($id == 0){
				$id = $this->crearNuevoProducto();
			}	
		
			//Guarda en la tabla de productos	
			$tbProductos = $db->nameQuote('#__virtuemart_products');
			$query = "
					UPDATE
						$tbProductos
					SET
						product_sku = '%s',
						product_in_stock = %s
					WHERE
						virtuemart_product_id = %s
						";
			$query = sprintf( $query, $codigo, $cantidad, $id );			
			$db->setQuery($query);
			$result = $db->query();
			
			$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
			$query = "
					UPDATE
						$tbProductos
					SET
						product_s_desc = '%s',
						product_desc = '%s', 
						product_name ='%s', 
						slug = '%s'
					WHERE
						virtuemart_product_id = %s
						";
			$query = sprintf( $query, mysql_real_escape_string($descripcion), mysql_real_escape_string($descripcion_completa), 
							  mysql_real_escape_string($nombre), strtolower(mysql_real_escape_string($nombre)), $id );			
			
			$db->setQuery($query);
			$result = $db->query();
			
			$tbProductos = $db->nameQuote('#__virtuemart_product_categories');
			$query = "
					UPDATE
						$tbProductos
					SET
						virtuemart_category_id = %s
					WHERE
						virtuemart_product_id = %s
						";
			$query = sprintf( $query, $categoria , $id );			
			$db->setQuery($query);
			$result = $db->query();
			
			$tbProductos = $db->nameQuote('#__virtuemart_product_prices');
			$query = "
					UPDATE
						$tbProductos
					SET
						product_price = %s
					WHERE
						virtuemart_product_id = %s
						";
			$query = sprintf( $query, $precio , $id );			
			$db->setQuery($query);
			$result = $db->query();
		
		
			//Elimina las imagenes 
			$this->eliminarImagenesProducto($id, $eliminarImg2, $eliminarImg3, $eliminarImg4);
			//Guardar las imagenes del producto
			$this->guardarImagenesProducto($id);
			
			
			return JText::_('M_OK'). JText::_('PRODUCTOS_GUARDAR_PRODUCTO');
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
	
	
	function guardarImagenesProducto($id){
		$db = Configuration::getTiendaDB();
		$indice = 1;
		while($indice <= 4){
			$nombre = $id . "_" . $indice;
			$imagen = JRequest::getVar('imagen' . $indice , null, 'files', 'array');
			if( $imagen['name'] != "" ){
				echo "guardar imagen $indice";
				$ext =  JFile::getExt($imagen['name']);		
				//Copia imagen del producto, maximo de 1000 px de ancho
				$nombreImagen 		= Configuration::rutaImagenesTiendaArchivos() . "images/productos/$nombre.$ext";
				JFile::copy( $imagen['tmp_name'], $nombreImagen );
				Image::resize($nombreImagen, $nombreImagen, 1000 , 0 );
				
				//Copia imagen thumb
				$nombreImagenThumb 	= Configuration::rutaImagenesTiendaArchivos() . "images/productos/thumb/$nombre.$ext";
				JFile::copy( $imagen['tmp_name'], $nombreImagenThumb );
				Image::resize($nombreImagenThumb, $nombreImagenThumb, 150 , 150 );
				
				//Guardar en tabla de media
				$imagenProducto = $this->guardarImagenProducto($imagen['name'], $imagen['type'],  "images/productos/$nombre.$ext",  "images/productos/thumb/$nombre.$ext" );
				
				//Actualizar tabla de producto
				if($imagenProducto > 0 ){
					$tbProductos = $db->nameQuote('#__virtuemart_product_medias');
					$query = "
							UPDATE
								$tbProductos
							SET
								virtuemart_media_id = %s
							WHERE
								virtuemart_product_id = %s AND
								ordering = %s
								";
					$query = sprintf( $query, $imagenProducto , $id, $indice );
					$db->setQuery($query);
					$result = $db->query();
				}
			}
			else{
				
			}
			
			$indice = $indice + 1;
		}
	}
	
	function eliminarImagenesProducto($id, $eliminarImg2, $eliminarImg3, $eliminarImg4){
		jimport('joomla.filesystem.file');
		
		$medias = $this->getMediasProducto($id);
		print_r($medias);
		foreach($medias as $image ){
			if($image->ordering == 2 && $eliminarImg2 == 'S' && $image->file_url != ""){
				$rutaImagen = Configuration::rutaImagenesTiendaArchivos() . "images/productos/" . $image->file_url;
				$rutaThumb  = Configuration::rutaImagenesTiendaArchivos() . "images/productos/thumb/" . $image->file_url_thumb;
				JFile::delete($rutaImagen);
				JFile::delete($rutaThumb);
				echo "borra imagen 2";
				//Borrar en bd
				$this->borrarMedia($image->virtuemart_media_id);
				
			}
			if($image->ordering == 3 && $eliminarImg3 == 'S' && $image->file_url != ""){
				$rutaImagen = Configuration::rutaImagenesTiendaArchivos() . "images/productos/" . $image->file_url;
				$rutaThumb  = Configuration::rutaImagenesTiendaArchivos() . "images/productos/thumb/" . $image->file_url_thumb;
				JFile::delete($rutaImagen);
				JFile::delete($rutaThumb);
				echo "borra imagen 3";
				$this->borrarMedia($image->virtuemart_media_id);
			}
			if($image->ordering == 4 && $eliminarImg4 == 'S' && $image->file_url != ""){
				$rutaImagen = Configuration::rutaImagenesTiendaArchivos() . "images/productos/" . $image->file_url;
				$rutaThumb  = Configuration::rutaImagenesTiendaArchivos() . "images/productos/thumb/" . $image->file_url_thumb;
				JFile::delete($rutaImagen);
				JFile::delete($rutaThumb);
				echo "borra imagen 4";
				$this->borrarMedia($image->virtuemart_media_id);
			}
		}
		//Eliminar en bd
	}
	
	public function borrarMedia($mediaId){
		$db = Configuration::getTiendaDB();					
		$tbMedia 	  = $db->nameQuote('#__virtuemart_medias');
		$query = "
				UPDATE
					$tbMedia
				SET
					file_url = '',
					file_url_thumb = ''
				WHERE
					virtuemart_media_id = %s
					";
		$query = sprintf($query, $mediaId );			
		$db->setQuery($query);
		$result = $db->query();
	}
	 
	
	public function crearNuevoProducto(){
		$db = Configuration::getTiendaDB();
		
		//Guarda en la tabla de productos
		$tbProductos = $db->nameQuote('#__virtuemart_products');
		$query = "
				INSERT  INTO
					$tbProductos
				(
					virtuemart_product_id, virtuemart_vendor_id, product_parent_id, product_sku, product_weight, 
					product_weight_uom, product_length, product_width, product_height, product_lwh_uom, 
					product_url, product_in_stock, product_ordered, low_stock_notification, product_available_date, 
					product_availability, product_special, product_sales, product_unit, product_packaging, 
					product_params, hits, intnotes, metarobot, metaauthor, 
					layout, published, created_on, created_by, modified_on, 
					locked_on, locked_by )
			    VALUES(
				0,1,0,'',1,
				'KG',0,0,0,'M',
				'',0,0,10,CURDATE(),
				'',0,0,'',0,
				'min_order_level=\"0\"|max_order_level=\"0\"|',0,'','','',
				'default',1,CURDATE(),42,CURDATE(),
				'0000-00-00 00:00:00',0
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		$id = $db->insertid();
	
		//Guarda en la tabla de idiomas
		$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
		$query = "
				INSERT  INTO
					$tbProductos(virtuemart_product_id, product_s_desc, product_desc, product_name, slug)
			    VALUES(
					$id,'','','','$id'
				)
					";
		$db->setQuery($query);
		$result = $db->query();
	
		//Guarda en la tabla de categorias
		$tbProductos = $db->nameQuote('#__virtuemart_product_categories');
		$query = "
				INSERT  INTO
					$tbProductos(virtuemart_product_id, virtuemart_category_id, ordering )
			    VALUES(
					$id, 1,1
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		
		
		
		//Guarda en la tabla de precios
		$tbProductos = $db->nameQuote('#__virtuemart_product_prices');
		$query = "
				INSERT  INTO
					$tbProductos(virtuemart_product_id, virtuemart_shoppergroup_id, product_price, product_currency )
			    VALUES(
					$id, NULL, 0, 31
				)
					";
		$db->setQuery($query);
		$result = $db->query();
		
		//Guarda en la tabla de imagenes (Cuatro imagenes por producto)
		$tbProductos = $db->nameQuote('#__virtuemart_product_medias');
		$indice = 1;
		while($indice <= 4){
			//Guarda imagen vacia
				
			//Guardar en tabla de media
			$imagenProducto = $this->guardarImagenProducto('', '',  "",  "" );
				
			$query = "
					INSERT  INTO
						$tbProductos(virtuemart_product_id, virtuemart_media_id, ordering)
					VALUES(
						$id, $imagenProducto, $indice
					)
						";
			$db->setQuery($query);
			$result = $db->query();
			$indice = $indice + 1;
		}
		
		return $id;
	}
	
	
	public function eliminarProducto( $id ){
		jimport('joomla.filesystem.file');

		$db = Configuration::getTiendaDB();	
		//TODO validar pedidos del producto
		//$productoEnPedidos = $this->tienePedidosProducto();
		$productoEnPedidos = 0;
		
		if($productoEnPedidos == 0){
		
			//Borra las  imagenes del producto
			$producto 	= $this->getProducto($id);
			$rutaImagen = Configuration::rutaImagenesTiendaArchivos() . $producto->file_url;
			$rutaThumb 	= Configuration::rutaImagenesTiendaArchivos() . $producto->file_url_thumb;
			JFile::delete($rutaImagen);
			JFile::delete($rutaThumb);
			
			$tbProductos = $db->nameQuote('#__virtuemart_products');
			$query = "DELETE FROM $tbProductos WHERE virtuemart_product_id = %s";
			$query = sprintf( $query, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			$tbProductos = $db->nameQuote('#__virtuemart_products_es_es');
			$query = "DELETE FROM $tbProductos WHERE virtuemart_product_id = %s";
			$query = sprintf( $query, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			$tbProductos = $db->nameQuote('#__virtuemart_product_categories');
			$query = "DELETE FROM $tbProductos WHERE virtuemart_product_id = %s";
			$query = sprintf( $query, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			$tbProductos = $db->nameQuote('#__virtuemart_product_prices');
			$query = "DELETE FROM $tbProductos WHERE virtuemart_product_id = %s";
			$query = sprintf( $query, $id);
			$db->setQuery($query);
			$result = $db->query();
			
			
			$tbProductos = $db->nameQuote('#__virtuemart_product_medias');
			$query = "DELETE FROM $tbProductos WHERE virtuemart_product_id = %s";
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
		else{
			return JText::_('M_ERROR') .  JText::_('PRODUCTOS_ELIMINAR_ERROR_PEDIDOS');
		}
		
	}

}










