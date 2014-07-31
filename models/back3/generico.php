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
		
class ModelGenerico extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	function cambiarClave(){
	
		$claveAct = JRequest::getVar('claveAct');
		$clave1 = JRequest::getVar('clave1');
		$clave2 = JRequest::getVar('clave2');
		
		if($clave1 == $clave2){
			$user = JFactory::getUser();
			JTable::addIncludePath(JPATH_COMPONENT .DS. 'tables');	
			$row =& JTable::getInstance('User', 'Table');
			$row->id = $user->id;
			$row->password = md5($clave1);
			if($row->store()){
				return JText::_('M_OK') . sprintf( JText::_('AD_GUARDAR_USUARIO_OK'));
			}
			else{
				return JText::_('M_ERROR'). JText::_('AD_GUARDAR_USUARIO_ERROR');
			}
		}
	}
	
}







