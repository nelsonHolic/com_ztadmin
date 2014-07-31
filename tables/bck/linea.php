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
class TableLinea extends JTable {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	var $linea;
	var $ext;
	var $direccion;
	var $operadora;
	var $group_id;
	
	var $cat_local;
	var $cat_ddn;
	var $cat_ddi;
	var $cat_celular;
	var $cat_901;
	var $cat_113;
	
	var $ser_ma;
	var $ser_il;
	var $ser_ct;
	var $ser_clgt;
	var $ser_ss;
	var $ser_gt;
	var $ser_te;
	var $ser_te_min;
	var $ser_nm;
	
	var $voz;
	var $voz_correo;
	var $estado;
	
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__zlineas', 'id', $db);
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