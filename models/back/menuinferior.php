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


		
class ModelMenuInferior extends JModel{

	const MENU_NAME = 'footmenu';
	
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function listar($filtro, $estado, $inicio, $registros){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						* 
				  FROM 
						$tbMenu 
				  WHERE 
						published = 1 AND 
						parent_id = 1 and 
						menutype = 'footmenu'
					ORDER BY
						lft
					
						";
		
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		
		
		//Establece el tipo de cada menu
		foreach($result as $item){
			$item->tipo = $this->getTipo($item->link);
		}
		
		return $result;
	}
	
	function getTipo($link){
		if(strpos($link, "com_content&view=featured")){
			$tipo = JText::_('TIPO_PAGINA_INICIO');
		}
		else if(strpos($link, "com_virtuemart&view=categories")){
			$tipo = JText::_('TIPO_CATEGORIA_PRODUCTOS');
		}
		else if(strpos($link, "com_content&view=article")){
			$tipo = JText::_('TIPO_CONTENIDO');
		}
		else if(strpos($link, "com_virtuemart&view=virtuemart")){
			$tipo = JText::_('TIPO_TIENDA');
		}
		else if(strpos($link, "com_content&view=category&layout=blog")){
			$tipo = JText::_('TIPO_BLOG');
		}
		else if(strpos($link, "option=com_contact&view=contact")){
			$tipo = JText::_('TIPO_CONTACTO');
		}
		else if( $link == "#" ){
			$tipo = JText::_('TIPO_SEPARADOR');
		}
		else{
			$tipo = JText::_('TIPO_NO_IDENTIFICADO');
		}
	
		return $tipo;
	}
	
	function getTipoDescripcion($link){
		$tipo = "";
		
		if(strpos($link, "com_virtuemart&view=categories")){
			$data = explode("virtuemart_category_id=", $link);
			$tipo = $data[1];
			$tipo = Productos::getCategoria($tipo);
			$tipo = $tipo->virtuemart_category_id ." - ". $tipo->category_name;
		}
		else if(strpos($link, "option=com_content&view=article&id=")){
			$data = explode("option=com_content&view=article&id=", $link);
			$tipo = $data[1];
			$tipo = Contenidos::getContenido($tipo);
			$tipo = $tipo->id . " - " . $tipo->title;
		}
		else if(strpos($link, "com_content&view=category&layout=blog")){
			$data = explode("layout=blog&id=", $link);
			$tipo = $data[1];
			$tipo = Contenidos::getCategoria($tipo);
			$tipo = $tipo->id ." - ". $tipo->title;
		}
		else if(strpos($link, "option=com_contact&view=contact")){
			$data = explode("option=com_contact&view=contact&id=", $link);
			$tipo = $data[1];
			$tipo = Contactos::getContacto($tipo);
			$tipo = $tipo->id . " - " . $tipo->name;
		}
		else if( $link == "#" ){
			$tipo = "";
		}
		
		return $tipo;
	}
	
	function contar($filtro, $estado){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMenu 
				  WHERE 
						published = 1 AND 
						parent_id = 1 and 
						menutype = 'footmenu' 
						";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	
	function mover($id, $desp){
		jimport('joomla.database.tablenested');
		JTable::addIncludePath(JPATH_COMPONENT .DS.'tables');
		
		$row =& JTable::getInstance('Menu', 'Table');
		
		if ( $row->load( $id ) ) {
		
			if($desp <= -1){
				$anterior = $this->itemAnterior($id, $this->itemLft($id), $this->parentItem($id));
				$row->moveByReference( $anterior, 'before', $id );
			}
			else{
				$siguiente = $this->itemSiguiente($id, $this->itemLft($id), $this->parentItem($id));
				$row->moveByReference( $siguiente, 'after', $id );
			}
			
			return JText::_('M_OK') . JText::_('MENU_ACTUALIZAR_POS_ITEM');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}
	
	function eliminar($id){
		jimport('joomla.database.tablenested');
		JTable::addIncludePath(JPATH_COMPONENT .DS.'tables');
		
		$row =& JTable::getInstance('Menu', 'Table');
		
		if ( $row->load( $id ) ) {
			if($row->delete($id))
				return JText::_('M_OK'). JText::_('MENU_ELIMINAR_ITEM');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}
	
	function parentItem($id){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						parent_id
				  FROM 
						$tbMenu 
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		//echo "parent = $result";
		return $result;
	}
	
	function itemLft($id){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						lft
				  FROM 
						$tbMenu 
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		//echo "lft = $result";
		return $result;
	}
	
	function itemAnterior($id , $lft, $parent){
	
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						* 
				  FROM 
						$tbMenu 
				  WHERE 
						parent_id = $parent AND
						published = 1 AND
						lft < $lft AND
						menutype = 'footmenu'
				  ORDER by lft DESC
						";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
		//echo "anterior = {$result->id}";
		return $result->id;
	}
	
	function itemSiguiente($id , $lft, $parent){
	
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						* 
				  FROM 
						$tbMenu 
				  WHERE 
						parent_id = $parent AND
						published = 1 AND
						lft > $lft AND
						menutype = 'footmenu'
				  ORDER by lft ASC
						";
		echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
		//echo "sig = {$result->id}";
		return $result->id;
		
	}
	
	function contenidoPorTipo($tipo){
		$options = "";
		if($tipo == "AR"){
			$options = Contenidos::lista();
		}
		else if($tipo == "CA"){
			$options = Contenidos::categorias();
		}
		else if($tipo == "BL"){
			$options = Contenidos::categorias();
		}
		else if($tipo == "CP"){
			$options = Productos::categorias();
		}
		else if($tipo == "CO"){
			$options = Contactos::lista();
		}
		
		
		return $options;
	}
	
	function categoriasArticulos(){
		$db = Configuration::getTiendaDB();

		$tbContent = $db->nameQuote('#__content');
		$query = "SELECT 
						* 
				  FROM 
						$tbContent
				  WHERE 
						state = 1
				  ORDER BY 
						title
						";
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getItem($id){
		$db = Configuration::getTiendaDB();
		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						*
				  FROM 
						$tbMenu 
				  WHERE 
						id = $id ";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
		$result->tipo = $this->getTipo($result->link);
		$result->tipoDescripcion = $this->getTipoDescripcion($result->link);
		
		if(isset($result->params)){
			$jsonData = json_decode($result->params);
			$result->descripcion = isset($jsonData->gk_desc) ? $jsonData->gk_desc : "";
		}
		
		return $result;
	}
	
	function moverMayorRgt($parent, $rgt, $posiciones){
		$db = Configuration::getTiendaDB();
		$tbMenu = $db->nameQuote('#__menu');
		
		$query = "UPDATE
					$tbMenu 
			  SET 
					lft = lft + $posiciones,
					rgt = rgt + $posiciones
			  WHERE 
					parent_id = $parent AND
					menutype = 'footmenu' AND
					rgt > $rgt
					";
		echo $query;
		
		$db->setQuery($query);
		$result = $db->query();
		
	}
	
	
	function guardar($id, $titulo, $descripcion, $tipo, $datos){
	
		$db 	= Configuration::getTiendaDB();
		$tbMenu = $db->nameQuote('#__menu');
		
		
		$enlace = $this->crearEnlace($tipo, $datos);
		$enlace = ($enlace != "") ? "link = '$enlace'," : "";
		
		//Guarda la descripcion del menu
		$params = "";
		$menu 	= $this->getItem($id);
		
		//Guarda la descripcion
		$params = Json::set($menu->params, "gk_desc", $descripcion);
	
		
		$params = $db->getEscaped($params);
		$params = "params = '$params',"; 
		
		$query = "UPDATE
						$tbMenu 
				  SET 
						$enlace
						$params
					    title = '$titulo',
						alias = '$titulo',
						path =  '$titulo'
				  WHERE 
						id = $id ";
		//echo $query;
		$db->setQuery($query);
		$result = $db->query();
		
		if($result){
			return JText::_('M_OK'). JText::_('MENU_ACTUALIZAR_ITEM');
		}
		else{
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
	}
	
	function crearEnlace($tipo, $datos){
		
		$enlace = "";
		
		if($tipo == "AR"){
			$enlace = "index.php?option=com_content&view=article&id=$datos";
		}
		else if($tipo == "CP"){
			$enlace = "index.php?option=com_virtuemart&view=categories&virtuemart_category_id=$datos";
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
			$enlace = "index.php?option=com_contact&view=contact&id=$datos";
		}
		
		return $enlace;
	}
	
	
	function guardarNuevoPariente($data , $tipo='H'){
	
		jimport('joomla.database.tablenested');
		JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_ztadmin'.DS.'tables');
	
	
		$item = $data['id'];
		
		//Obtiene papa
		//Si es un hermano busca el papa del item
		if($tipo == 'H'){
			$parent = $this->parentItem($item);
			
			//Obtiene rgt de la posicion actual
			$item = $this->getItem($item);
			$rgt  = $item->rgt;
			//echo "rgt  = $rgt";
			$this->moverMayorRgt($parent, $rgt, 2);
			$position = $rgt ;	
		}
		else{
			//Si es un hijo el papa del item es el actual
			$parent = $item;
			//Obtiene  maxima posicion a la derecha
			$position = $this->maximoDerecha($parent);
		}
		
		
		//Guarda el nuevo item 
		$item = &JTable::getInstance('Menu', 'Table');
		$item->menutype = 'footmenu';
		$item->title    = $data['title'];
		$item->alias    = $data['title'];
		$item->link    = $this->crearEnlace($data['tipo'], $data['datos']);
		$item->type    = 'component';
		$item->published    = 1;
		$item->level = 1;
		$item->language    = '*';
		
		
		//Guarda
		$item->store();
		
		$item->lft = $position + 1;
		$item->rgt = $position + 2;
		
		if($this->esPrimerNivel($parent)){
			$params = $this->paramsPorTipo("GROUP");
		}
		else{
			$params = $this->paramsPorTipo($data['tipo']);
		}
		
		//Guarda la descripcion
		$params = Json::set($params, "gk_desc", $data['descripcion']);
		
		$item->params = $params;
		$item->parent_id = $parent;
		
		if($item->store()){
			return  JText::_('M_OK') . JText::_('MENU_GUARDAR_ITEM'); 
		}
		else{
			//Reportar error por correo y en log de errores
			return JText::_('M_ERROR') .  JText::_('PROCESO_ERROR');
		}
		
		
	}
	
	
	
	function getParams($id){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						params
				  FROM 
						$tbMenu 
				  WHERE 
						menutype ='footmenu' AND
						published = 1 AND
						id = $id 
				 ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function maximoDerecha($id){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						max(rgt)
				  FROM 
						$tbMenu 
				  WHERE 
						menutype ='footmenu' AND
						published = 1 AND
						parent_id = $id 
				 ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	
	function paramsPorTipo($tipo){
		$options = "";
		if($tipo == "AR"){
			$options = Params::articulo();
		}
		else if($tipo == "CA"){
			$options = Contenidos::categorias();
		}
		else if($tipo == "BL"){
			$options = Contenidos::categorias();
		}
		else if($tipo == "CP"){
			$options = Productos::categorias();
		}
		else if($tipo == "CO"){
			$options = Contactos::lista();
		}
		else if($tipo == "GROUP"){
			$options = Params::agrupador();
		}
		
		
		return $options;
	}
	
	
	function esPrimerNivel($item){
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						parent_id
				  FROM 
						$tbMenu 
				  WHERE 
						menutype ='footmenu' AND
						published = 1 AND
						id = $item
				 ";
		
		$db->setQuery($query);
	    $result = $db->loadResult();
		
		if($result == 1){
			return true;
		}
		else{
			return false;
		}
		return $result;
	}
	
	
	function crearItemMenu(){
		
		$db = Configuration::getTiendaDB();

		$tbMenu = $db->nameQuote('#__menu');
		$query = "SELECT 
						* 
				  FROM 
						$tbMenu 
				  WHERE 
						published = 1 AND 
						parent_id = 1 and 
						menutype = 'footmenu' 
						and type='component'";
		
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		
		foreach($result as $item){
			echo $item->title . "  ";
			echo $item->link . "  ";
			echo $item->level . "  ";
			echo $item->parent_id . "  ";
			echo "<br/>";
		}
	}
	
	
	function registrar($accion, $anterior, $nuevo, $valor){
		JTable::addIncludePath(JPATH_SITE .DS. 'components'.DS. 'com_zcorreos'.DS.'tables');
		
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');

		$user = JFactory::getUser();
		
		$log =& JTable::getInstance('Log', 'Table');
		$log->accion = $accion;
		$log->fecha = $fechaHoy;
		$log->usuario = $user->id;
		$log->valor = $valor;
		$log->ip = Util::getIp();
		$log->store();
	
	}
	
	
	
	
	
	
	
	
	
	
	

	
	
	
}










