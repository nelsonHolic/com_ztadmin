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
BasicPageHelper::iniPage( $user->username, $menu);
PageHelper::initPage("Ejecutar Reporte ", ""); 
?>


	<div class="alert">
		El reporte se gener&oacute; correctamente, se abrir&aacute; como un archivo de excel. 
		Si no puede visualizarlo correctamente debe habilitar las ventanas emergentes para este sitio
	</div>
							

<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("67");
	Menu::setActive("72");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Listado de reportes","index.php?option=com_ztadmin&task=adReportList");
	PageHelper::addFinalBreadcrumb("Ejecutar reporte","#");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>






