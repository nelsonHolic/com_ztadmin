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

$bol= (isset($data->id) ? "Editar " : "Crear Nuevo ");

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage($bol."grupo", ""); 

$id = (isset($data->id) ? $data->id : "");
$descripcion = (isset($data->descripcion) ? $data->descripcion : "");

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Editar Grupo  #{$this->id}"); ?>
			
				
				<form class="form-horizontal" method="post" >
					<fieldset>
						
					
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
									   <input name="descripcion" type="text" value='<?php echo $descripcion;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
												
					
					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit">Guardar</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='id' value='<?php echo $id;?>' />
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='veGrupoSave' />
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
	Menu::setActive("59");
	if(isset($data->id)){	
		Menu::setActive("61");
	}
	else{	
		Menu::setActive("60");
	}
}
else if($user->tipo == 'U'){
	Menu::setActive("95");
	Menu::setActive("97");
	Menu::setActive("101");
}

	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("grupos","index.php?option=com_ztadmin&task=veGrupoList");
	PageHelper::addFinalBreadcrumb($bol);
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




