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
		
class ModelCron extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	
	function cronNotSent(){
		$db = JFactory::getDBO();
		$usuarios = $this->getAllUsuarios();
		print_r($usuarios);
		foreach($usuarios as $usuario){
			$mensajes = $this->getMensajesNotSent($usuario->id);
			echo "usuario = " . $usuario->id . " mensajes = " . $mensajes . "<br/>";
			//Transaccion
			ZDBHelper::initTransaction($db);
			if( !$this->actualizarMensajesUsuarioNotSent( $usuario->id )){
				ZDBHelper::rollBack($db);
			}
			if( !$this->actualizarCreditosUsuario($usuario->id, $mensajes) ){
				ZDBHelper::rollBack($db);
			}
			
			ZDBHelper::commit($db);
		}
	}
	
	
	function getAllUsuarios(){
		$db = JFactory::getDBO();
		$tbUsers = $db->nameQuote('#__users');

		$query = "SELECT 
						*
				  FROM 
						$tbUsers
						
						";
						
		$query = sprintf( $query);
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function getMensajesNotSent($id){
		$db = JFactory::getDBO();
		$tbOutbox = $db->nameQuote('outbox');

		$query = "SELECT 
						count(*)
				  FROM 
						$tbOutbox
				  WHERE
						procesado = 0 AND
						user = %s AND
						status = 'notsent'
						";
						
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	function actualizarMensajesUsuarioNotSent($id){
		$db = JFactory::getDBO();
		$tbOutbox = $db->nameQuote('outbox');

		$query = "UPDATE 
						$tbOutbox
				  SET 
					procesado = 1
				  WHERE
						procesado = 0 AND
						user = %s AND
						status = 'notsent'
						";
						
		$query = sprintf( $query, $id);
		$db->setQuery($query);
	    $result = $db->query();
		return $result;
	}
	
	function actualizarCreditosUsuario($id, $mensajes){
		$db = JFactory::getDBO();
		$tbUsers = $db->nameQuote('#__users');

		$query = "UPDATE 
						$tbUsers
				  SET 
						mensajes = mensajes + %s
				  WHERE
						id = %s
						";
						
		$query = sprintf( $query, $mensajes, $id);
		$db->setQuery($query);
	    $result = $db->query();
		return $result;
	}
	
	
	

	
	
}










