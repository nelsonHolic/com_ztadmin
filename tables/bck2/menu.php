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
class TableMenu extends JTableNested {

	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;
	var $menutype;
	var $title;
	var $alias;
	var $note;
	var $path;
	var $link;
	var $type;
	var $published;
	var $parent_id;
	var $level;
	var $component_id;
	var $ordering;
	var $checked_out;
	var $checked_out_time;
	var $browserNav;
	var $access;
	var $img;
	var $template_style_id;
	var $params;
	var $lft;
	var $rgt;
	var $home;
	var $language;
	var $client_id;
	
	
    /**
	 * Constructor
	 *
	 * @param object Database connector object
	 * @since 1.0
	 */
	function __construct(& $db) {
		parent::__construct('#__menu', 'id', Configuration::getTiendaDB() );
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