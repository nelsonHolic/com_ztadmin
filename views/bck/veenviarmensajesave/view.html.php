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
class ViewVeEnviarMensajeSave extends JView
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
		JRequest::checkToken() or die( 'Token Invalido' );
		
		$data =  JRequest::get('post');
		//print_r( $data );
		Session::setVar("data", $data);
		
		$mensaje 	= $data['message'];
		$model 		= $this->getModel('Mensaje');	
		$mensaje 	= $model->removerCaracteresEspeciales($mensaje);
		$tam 		= $model->tamMaxMensajes($mensaje);
		$mensajes 	= $model->contarMensajes($mensaje, $tam);
		
		if( isset($data['envio_todos']) ){
			$destinos = $model->contarTodosContactos();
		}
		else{
			$grupos = explode( "," ,$data['grupos'] );
			
			$contactosGrupos = 0;
			foreach($grupos as $grupo){
				$contactosGrupos = $contactosGrupos + $model->contarContactosGrupo($grupo) ; 
			}
			
			//Obtiene contactos
			$contactos = trim( $data['contactos'] );
			$contactosData = explode(',', $contactos);
			$contactosData = ( strlen($contactos > 3 ) ) ? count($contactosData) : 0 ;
			
			//Obtiene telefonos
			$telefonos = trim( $data['telefonos'] );
			$telefonosData = explode(';', $telefonos);
			$telefonosData = ( strlen($telefonos > 3 ) ) ? count($telefonosData) : 0 ;
			
		
			$destinos = $contactosData +  $telefonosData + $contactosGrupos;
		}
		
		//echo "tam = " . $tam . " mensajes = " . $mensajes. " destinos = " . $destinos . " grupos =" . $contactosGrupos;
		
		//exit;
		//Util::processResult($result);
		
		$this->assignRef('mensajes', $mensajes);
		$this->assignRef('destinos', $destinos);
		
		parent::display($tpl);
	}

	
}









