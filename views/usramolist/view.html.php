<?php
/**
 * @version		$Id: view.html.php 21023 2011-03-28 10:55:01Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Form login
 *
 * @package		
 * @subpackage	
 * @since		
 */
class ViewUsRamoList extends JView
{
	protected $form;
	protected $params;
	protected $state;
	protected $user;

	/**
	 * Method to display the view.
	 *
	 * @param	string	The template file to include
	 * @since	1.5
	 */
	public function display($tpl = null)
	{
		
		jimport('joomla.html.pagination');
		
		$inicio	  	= Session::getVar('limitstart', 0 );
		$registros 	= Configuration::getValue("CANTIDAD_REGISTROS");
		
		$filtro    = Session::getVar("filtro");
		
		$model = $this->getModel('Ramo');

		$data 		= $model->listarRamos($filtro, $inicio, $registros);
		$cantidad 	= $model->contarRamos($filtro);
		
		$pageNav = new JPagination( $cantidad , $inicio, $registros );
				
		$this->assignRef('filtro' , $filtro);
		$this->assignRef('data'   , $data);
		$this->assignRef('pageNav', $pageNav);
		$this->assignRef('inicio' , $inicio );
		
		parent::display($tpl);
	}

	
}
