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
PageHelper::initPage("Importar contactos de Excel", ""); 
?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Importar contactos"); ?>
			
				
				<form class="form-horizontal" method="POST" enctype="multipart/form-data">
					<fieldset>					
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<a href='images/archivos/plantilla.xlsx'>Descargar plantilla del archivo</a>
								
								</div>
							</div>
						</div>
						
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Archivo</label>
									<div class="controls">
										<input 	type="file" id="archivo" name='archivo' class="m-wrap span6 popovers" size='50'
												placeholder="Archivo" 
												data-trigger="hover" 
												data-content="Seleccione el archivo a cargar" 
												data-original-title="Ayuda" 
									   />
									</div>
								</div>
							</div>
						</div>
						
						
					</fieldset>
					
					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit">Guardar</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='veContactoImportar' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
	
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	if($user->tipo == 'V'){
		Menu::setActive("58");
		Menu::setActive("68");
		Menu::setActive("71");
	}
	else if($user->tipo == 'U'){
		Menu::setActive("95");
		Menu::setActive("96");
		Menu::setActive("103");
	}

	
	//Use the breadcrumb
	
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Grupos","index.php");
	PageHelper::addFinalBreadcrumb("Importar contactos","");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




