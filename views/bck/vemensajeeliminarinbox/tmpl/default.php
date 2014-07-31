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
BasicPageHelper::iniPage(GuiHelper::msgVendedor(), $menu);
PageHelper::initPage("Guardar", ""); 


if( $this->error == false){
	//GuiHelper::redirect($this->msg, $this->error, "index.php?option=com_ztadmin" );
	GuiHelper::mensajeOk($this->msg);
}
else{
	GUIHelper::mensajeNOk($this->msg);
}

BasicPageHelper::endPage(); 

//Selecciona el menu
Menu::setActive("55");
Menu::setActive("56");
Menu::setActive("79");

//Use the breadcrumb
PageHelper::addInitialBreadcrumb("Dashboard","index.php");
PageHelper::addBreadcrumb("Mensajes","index.php?option=com_ztadmin&task=vebandejaenviados");
PageHelper::addFinalBreadcrumb("Eliminar");
?>

