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
		
class ModelCupon extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}

	function listar($filtro,  $inicio, $registros){
	
		$db = & JFactory::getDBO();
		$tbCupones = $db->nameQuote('#__zcupones');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		
		$query = "SELECT 
						*
				  FROM 
						$tbCupones as c
				  WHERE
						codigo like '%s' 
				  ORDER BY
						id 
						";
		$query = sprintf( $query, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro){
	
		$db = & JFactory::getDBO();
		$tbCupones = $db->nameQuote('#__zcupones');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbCupones as c
				  WHERE
						codigo like '%s' 
						";
		
		$query = sprintf( $query, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function listarUsados($filtro,  $inicio, $registros){
	
		$db = Configuration::getTiendaDB();
		$tbCuponesUsados = $db->nameQuote('#__virtuemart_orders');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		
		$query = "SELECT 
						*
				  FROM 
						$tbCuponesUsados as c
				  WHERE
						coupon_code is not NULL 
				  ORDER BY
						virtuemart_order_id DESC
						";
		$query = sprintf( $query, $filtro);
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contarUsados($filtro){
	
		$db = Configuration::getTiendaDB();
		$tbCuponesUsados = $db->nameQuote('#__virtuemart_orders');
		$filtro = mysql_real_escape_string("%".$filtro."%");
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbCuponesUsados as c
				  WHERE
						coupon_code is not NULL 
						";
		
		$query = sprintf( $query, $filtro);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function getCupon($id){
		$db = & JFactory::getDBO();
		$tbCupones = $db->nameQuote('#__zcupones');
		
		$query = "SELECT 
						*
				  FROM 
						$tbCupones  c
				  WHERE
						c.id = %s
						";
						
		$query = sprintf( $query, $id );			
		echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		$result = $result[0];
		return $result;
	}

	function guardar($codigo, $tipoAplicacion, $tipoCupon, $valor, $fechaInicio, $fechaExpiracion, $compraMinima, $cliente ){
	
		//filtrar datos entrada TODO
		date_default_timezone_set('America/Bogota');
		$fechaHoy = date('Y-m-d H:i:s');
		
		//verificar rangos de valor de cupones TODO

		//guardar en tienda
		$db = Configuration::getTiendaDB();
		$tbCupon = $db->nameQuote('#__virtuemart_coupons');
		$tipoAplicacion = ($tipoAplicacion == 'P') ? 'percent' : 'total';
		$tipoCupon = ($tipoCupon == 'R') ? 'gift' : 'permanent';
		
		$query = "INSERT INTO
						$tbCupon(coupon_code, percent_or_total, coupon_type, coupon_value, 
								 coupon_start_date, coupon_expiry_date, coupon_value_valid, published, 
								 created_on)
					    VALUES(
							'%s', '%s', '%s', %s,
							'%s', '%s', %s , 1, '%s'
						)";
		$query = sprintf( $query, $codigo, $tipoAplicacion, $tipoCupon, $valor, $fechaInicio, $fechaExpiracion, $compraMinima, $fechaHoy );
		$db->setQuery($query);
		$result = $db->query();
		$id  = $db->insertid();
				   
		//guardar en admin,enlazar
		$user = JFactory::getUser();
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Cupon', 'Table');
		$row->codigo = $codigo;
		$row->tipo_aplicacion = $tipoAplicacion;
		$row->tipo_cupon = $tipoCupon;
		$row->valor = $valor;
		$row->fecha_inicio = $fechaInicio;
		$row->fecha_expiracion = $fechaExpiracion;
		$row->compra_minima = $compraMinima;
		$row->fecha_creacion = $fechaHoy;
		$row->creador = $user->id;
		$row->tienda_id = $id;
		$row->cliente = $cliente;
		$result = $row->store();
		//Notificar por correo
		$resultCorreo = $this->notificarCuponCliente($id, $cliente);
		
		if($result){
			$msg =  JText::_('M_OK'). JText::_('CUPONES_GUARDAR');
			if( $resultCorreo){
				$msg = $msg . JText::_('CUPONES_GUARDAR_CORREO');
			}
		}
		else{
			$msg =  JText::_('M_ERROR'). JText::_('PROCESO_ERROR');
		}
		
		return $msg;
	}
	
	
	/**
	* Retorna la orden en la que fue usado un cupon o 0 en caso contrario
	*/
	function ordenCupon($codigoCupon){
		$db = Configuration::getTiendaDB();
		$tbPedido = $db->nameQuote('#__virtuemart_orders');
		
		$query = "SELECT 
						virtuemart_order_id
				  FROM 
						$tbPedido as p
				  WHERE
						p.coupon_code = '%s' 
						";
		
		$query = sprintf( $query, $codigoCupon );
		$db->setQuery($query);
	    $result = $db->loadResult();
		if($result > 0){
			return $result;
		}
		else{
			return 0;
		}	
	}
	
	function actualizarOrdenCupon($id, $orden){
		JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
		$row =& JTable::getInstance('Cupon', 'Table');
		$row->id = $id;
		$row->order_id = $orden;
		$row->store();
	}
	
	
	function notificarCuponCliente($cupon, $cliente){
		
		$cliente = $this->getInfoCliente($cliente);
		print_r($cliente);
		$cupon   = $this->getInfoCupon($cupon);
		print_r($cupon);

		$cliente->email = "andresquintero@zeroplatform.com";
		$compraMinima =   "$ " . number_format( $cupon->coupon_value_valid, 0 ,'', ',');
		//$compraMinima =  $cupon->coupon_value_valid;
		if( $cupon->percent_or_total == 'percent' ){
			$valor =   $cupon->coupon_value . " % ";
		}
		else{
			$valor =  "$" . number_format($cupon->coupon_value, 0 ,'', ',') ;
		}
	
		//Envia correo a cliente con datos del cupon
		require_once( JPATH_COMPONENT . DS . 'libraries' . DS . 'phpMailer' . DS . 'class.phpmailer.php' );
		$body = file_get_contents( JPATH_COMPONENT . DS . 'mailtemplate' . DS .  'enviocuponcliente.html');
		$body =  preg_replace("/TAG_NOMBRE/", $cliente->name, $body);
		$body =  preg_replace("/TAG_CODIGO_CUPON/", $cupon->coupon_code, $body);
		$body =  preg_replace("/TAG_FECHA_INICIO/", $cupon->coupon_start_date, $body);
		$body =  preg_replace("/TAG_FECHA_VENCIMIENTO/", $cupon->coupon_expiry_date, $body);
		$body =  preg_replace("/TAG_COMPRA_MINIMA/", $compraMinima , $body);
		$body =  preg_replace("/TAG_LINEA_SERVICIO_CLIENTE/", Configuration::getValue('LINEA_SERVICIO_CLIENTE'), $body);
		$body =  preg_replace("/TAG_URL_TIENDA/", Configuration::getValue('URL_TIENDA'), $body);
		$body =  preg_replace("/TAG_VALOR_CUPON/", $valor , $body);
		$body =  preg_replace("/TAG_CORREO_RUTA_LOGO/", Configuration::getValue('CORREO_RUTA_LOGO'), $body);
		$body =  preg_replace("/TAG_NOM_TIENDA/", Configuration::getValue('NOMBRE_TIENDA'), $body);
		
		 	
		
		
		$mail	= new PHPMailer();
		$mail->SetFrom( Configuration::getValue('CORREO_ORIGEN'), utf8_decode(Configuration::getValue('CORREO_NOMBRE')) );
		$mail->Subject    = utf8_decode(Configuration::getValue('CUPON_ASUNTO') );
		$mail->AltBody    = "Para ver este mensaje, por favor use un visor HTML!"; 
		$mail->MsgHTML($body);
		//$mail->AddAttachment("../encripta/$fileName", "$fileName"); 
		
		$mail->AddAddress($cliente->email, $cliente->name );
		if(!$mail->Send()) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	* Retorna informacion del cliente
	*/
	function getInfoCliente($cliente){
		$db = Configuration::getTiendaDB();
		$tbUsers = $db->nameQuote('#__users');
		
		$query = "SELECT 
						*
				  FROM 
						$tbUsers as u
				  WHERE
						u.id  = %s 
						";
		
		$query = sprintf( $query, $cliente );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result[0];	
	}
	
	/**
	* Retorna informacion del cupon
	*/
	function getInfoCupon($cupon){
		$db = Configuration::getTiendaDB();
		$tbCupones = $db->nameQuote('#__virtuemart_coupons');
		
		$query = "SELECT 
						*
				  FROM 
						$tbCupones as c
				  WHERE
						c.virtuemart_coupon_id  = %s 
						";
		
		$query = sprintf( $query, $cupon);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result[0];	
	}
	
	
	
}











