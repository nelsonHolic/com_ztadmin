<?php
/**
 * Joomla! 1.5 component 
 *
 * @version $Id: 2009-11-03 10:03:14 svn $
 * @author Une - ETP 
 * @package Joomla
 * @subpackage 
 * @license GNU/GPL
 *
 * 
 *
 * This component file was created using the Joomla Component Creator by Not Web Design
 * http://www.notwebdesign.com/joomla_component_creator/
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Include library dependencies
jimport('joomla.filter.input');

/**
* Table class
*
* @package          Joomla
* @subpackage		
*/
class TableUser extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	var $razon_social;
	var $nit;
	var $usuario;
	var $telefono;
	var $correo;
	var $clave;
	var $fecha_registro;
	var $fecha_revision;
	var $estado;
	
	
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__zusers', 'id', $db);
	}

	/**
	 * Overloaded check method to ensure data integrity
	 *
	 * @access public
	 * @return boolean True on success
	 */
	function check() {
		return true;
	}

}
?>