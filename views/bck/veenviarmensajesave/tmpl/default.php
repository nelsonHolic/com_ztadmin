<?php
/**
 * @version		$Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage("Enviar mensaje", ""); 


echo sprintf( JText::_('VE_GUARDAR_MENSAJE_PREGUNTA') , $this->mensajes, $this->destinos, $this->mensajes * $this->destinos );

?>

<form class="inbox-compose form-horizontal" id="fileupload"  method="POST" enctype="multipart/form-data">

	<div class="row-fluid">  
		<div class="span12 " style='text-align:right'>
		    <a class="btn red" type="button" href='index.php?option=com_ztadmin&task=veEnviarMensajeForm'>Cancelar</a>
			<button class="btn green" type="submit">Confirmar envío</button>
		</div>
	</div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type='hidden' name='option' value='com_ztadmin' />
	<input type='hidden' name='task' value='veenviarmensajesave2' />
	
</form>


<?php


BasicPageHelper::endPage(); 

//Selecciona el menu
if($user->tipo == 'V'){
	Menu::setActive("55");
	Menu::setActive("78");
}
else if($user->tipo == 'U'){
	Menu::setActive("85");
	Menu::setActive("86");
}

//Use the breadcrumb
PageHelper::addInitialBreadcrumb("Dashboard","index.php");
PageHelper::addBreadcrumb("Mensajes","index.php?option=com_ztadmin&task=veEnviarMensajeForm");
PageHelper::addFinalBreadcrumb("Enviar");
?>

