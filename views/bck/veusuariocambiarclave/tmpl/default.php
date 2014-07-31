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
PageHelper::initPage("Asignar Mensajes Usuario", ""); 

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Cambiar clave de usuario"); ?>
			
				
				<form class="form-horizontal" method="POST" >
					<fieldset>
					
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Usuarios</label>
									<div class="controls">
										 <select name='id'>
											<option value="">Seleccione un Usuario</option>
											<?php foreach($this->usuarios as $item){?>
												<option value="<?php echo $item->id ?>" <?php echo ($item->id == $this->usuariosActual ? "selected" : ""); ?>>
													<?php echo $item->username;?>
											</option>
											<?php } ?>
									</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Clave</label>
									<div class="controls">
									   <input name="clave1" type="password" value='' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>		
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Repetir clave</label>
									<div class="controls">
									   <input name="clave2" type="password" value='' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
					</fieldset>
					
					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit">Asignar</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='veUsuarioCambiarClaveSave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
	
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("72");
	Menu::setActive("76");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Usuario","index.php");
	PageHelper::addFinalBreadcrumb("Asignar mensajes");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




