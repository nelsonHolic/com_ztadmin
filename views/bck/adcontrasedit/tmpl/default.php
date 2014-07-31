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


$data = $this->data;

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( $user->username, $menu);
$nombre= (isset($data->id) ? "Editar" : "Crear");
PageHelper::initPage("Cambiar Contraseña", ""); 

$id = (isset($data->id) ? $data->id : "");
$user = (isset($data->user) ? $data->user : "");
$pass = (isset($data->pass) ? $data->pass : "");

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Cambiar Contraseña"); ?>
			
				
				<form class="form-horizontal" method="POST" >
					<fieldset>
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Contraseña actual</label>
									<div class="controls">
									   <input name="pass"  type="text" value='<?php ;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
					
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Nueva Contraseña</label>
									<div class="controls">
									   <input name="passworld" type="text" value='<?php ?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
												
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Repita se contraseña</label>
									<div class="controls">
									   <input name="passworld" type="text" value='<?php ?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
					
					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit">Cambiar</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='id' value='<?php echo $id;?>' />
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='adBaseDeDatosCrear' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
	
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("3");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Contraseña","index.php");
	PageHelper::addFinalBreadcrumb("Cambiar");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




