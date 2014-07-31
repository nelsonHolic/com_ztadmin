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
PageHelper::initPage("Guardar grupo", ""); 


if( $this->error == false){
	//GuiHelper::redirect($this->msg, $this->error, "index.php?option=com_ztadmin" );
	GuiHelper::mensajeOk($this->msg);
}
else{
	GUIHelper::mensajeNOk($this->msg);
}

BasicPageHelper::endPage(); 

//Selecciona el menu
if($user->tipo == 'V'){
	Menu::setActive("58");
	Menu::setActive("59");
}
else if($user->tipo == 'U'){
	Menu::setActive("95");
	Menu::setActive("97");
	Menu::setActive("98");
}



//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Grupos","index.php?option=com_ztadmin&task=veGrupoList");
	PageHelper::addFinalBreadcrumb("Guardar");
?>

