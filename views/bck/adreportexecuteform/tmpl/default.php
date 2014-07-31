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


<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Ejecutar reporte"); ?>

		<div class="row-fluid">
			<div class="span12 ">
				<div class="control-group">
					<label class="control-label"><b>T&iacute;tulo</b></label>
					<div class="controls">
					  <?php echo $this->report->nombre; ?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12 ">
				<div class="control-group">
					<label class="control-label"><b>Documentaci&oacute;n</b></label>
					<div class="controls">
					  <?php echo $this->report->documentacion; ?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span12 ">
				<div class="control-group">
					<b>Parametros</b>					
				</div>
			</div>
		</div>


		<form action="index.php" method="post">
			
			<?php

				if(is_array($this->report->parameters)){
					foreach($this->report->parameters as $parameter){
						if($parameter != "USER_ID"){
						?>
								<div class="row-fluid">  
									<div class="span12 ">
										<div class="control-group">
											<label class="control-label"><?php echo $parameter;?></label>
											<div class="controls">
												<input name='<?php echo $parameter;?>' type="text" value='' class="m-wrap span12" >
											</div>
										</div>
									</div>
								</div>
						
						<?php
						}
					}
				}
			?>
			<div class="form-actions">
				<button class="btn blue" type="submit"><i class="icon-ok"></i>Ejecutar</button>
			</div>
											
			<input type='hidden' name='reportCwId' value='<?php echo $this->report->id; ?>' />
			<input type='hidden' name='option' value='com_ztadmin' />
			<input type='hidden' name='task' value='adReportExecute' />
		</form>


		<?php PortletHelper::end(); ?>
	</div>
</div>


<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("67");
	Menu::setActive("72");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Listado de reportes","index.php?option=com_ztadmin&task=reportList");
	PageHelper::addFinalBreadcrumb("Ejecutar reporte","#");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>






