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
class ViewVeBandejaEliminadosBorrarTodos extends JView
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
		//JRequest::checkToken() or die( 'Token Invalido' );
		
		echo "borrando bandeja enviados todoss";
		$model 	= $this->getModel('Mensaje');
		
		if($model->eliminarMensajesOutboxFisico()){
			$result = JText::_('M_OK') . sprintf( JText::_('VE_BORRAR_TODOS_MENSAJE_OUTBOX_OK') ,'');
		}
		else{
			$result = JText::_('M_ERROR') . sprintf( JText::_('VE_BORRAR_TODOS_MENSAJE_OUTBOX_ERROR') ,'');
		}
		
		Util::processResult($result);
		parent::display($tpl);
	}

	
}









