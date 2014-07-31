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
$data = $this->mensaje;
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage("Detalle del mensaje :" . $data->id, ""); 

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Informaci&oacute;n del Mensaje #{$data->id}"); ?>
			
				
				<form class="form-horizontal" method="post" >
					<fieldset>
					
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group">
									<label class="control-label"><b>Id</b></label>
									<div class="controls">
									   <span class="text"><?php echo $data->id; ?></span>
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group">
									<label class="control-label"><b>Tipo</b></label>
									<div class="controls">
									   <span class="text"><?php echo $data->msgtype; ?></span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group">
									<label class="control-label"><b>Enviador por</b></label>
									<div class="controls">
									   <span class="text"><?php echo $data->sender; ?></span>
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group">
									<label class="control-label"><b>Enviado a</b></label>
									<div class="controls">
									   <span class="text"><?php echo $data->receiver; ?></span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label"><b>Fecha envio</b></label>
									<div class="controls">
									   <span class="text"><?php echo $data->acceptedfordeliverytime; ?></span>
									</div>
								</div>
							</div>
							
							
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label"><b>Mensaje</b></label>
									<div class="controls">
									   <span class="text"><?php echo $data->msgdata; ?></span>
									</div>
								</div>
							</div>
							
						</div>
						
					
					
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
	
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("58");
	Menu::setActive("68");
	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Mensajes","index.php?option=com_ztadmin");
	PageHelper::addFinalBreadcrumb("Detalle del mensaje");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




