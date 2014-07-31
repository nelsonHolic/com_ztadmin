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
class ViewAdUsuariosAsignarMensajes extends JView
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
		$id    = JRequest::getVar("id");
		$usuariosActual   = Session::getVar("usuarios");
		$mensajesActual   = Session::getVar("mensajes");
		
		$model = $this->getModel('Administrador');
		
		
		$usuarios	= $model->getUsuarios();
		$mensajes	= $model->getPaquetesMensajes();
		
		$this->assignRef('id', $id );
		$this->assignRef('usuarios', $usuarios);
		$this->assignRef('usuariosActual', $usuariosActual);
		$this->assignRef('mensajes', $mensajes);
		$this->assignRef('mensajesActual', $mensajesActual);
		
		parent::display($tpl);
	}

	
}
