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
class ViewUsTareaHistorial extends JView
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
		$id    = JRequest::getVar("id");
		$model = $this->getModel('Tarea');
		$usuarios = $model->listarUsuarios();
		$inicio	  	= Session::getVar('limitstart', 0 );
		$registros 	= Configuration::getValue("CANTIDAD_REGISTROS");
		
		$filtro    = Session::getVar("filtro");
		if( $id > 0 ){
			$data      = $model->listarHistorialTarea($filtro, $inicio, $registros, $id);
			$cantidad 	= $model->contarHistorialTarea($filtro, $id);
		}
		

		$this->assignRef('id', $id );
		$this->assignRef('data',$data );
		$this->assignRef('usuarios',$usuarios );
		$this->assignRef('filtro' , $filtro);

		parent::display($tpl);
	}

	
}
