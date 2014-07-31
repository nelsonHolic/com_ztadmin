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
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage("Asignar contacto grupo", ""); 


?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Contactos"); ?>
			
				
				<form class="form-horizontal" method="post" >
					<fieldset>
						
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Grupo</label>
									<div class="controls">
										 <select name='grupo'>
											<option value="">Seleccione un grupo</option>
											<?php foreach($this->grupo as $item){?>
												<option value="<?php echo $item->id ?>" <?php echo ($item->id == $this->grupoActual ? "selected" : ""); ?>>
													<?php echo $item->descripcion;?>
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
									<label class="control-label">Contacto</label>
									<div class="controls">
										 <input type="text" class="m-wrap span12" name="contactos" id="contactos" />
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
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='veGrupoContactoSave' />
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
		Menu::setActive("62");
	}
	else if($user->tipo == 'U'){
		Menu::setActive("95");
		Menu::setActive("97");
		Menu::setActive("98");
	}
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("grupos","index.php?option=com_ztadmin&task=veGrupoList");
	PageHelper::addFinalBreadcrumb("Agregar Contacto");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>

<script src="templates/chronos/plugins/jquerytokeninput/js/jquery.tokeninput.js" type="text/javascript" ></script> 

<script type='text/javascript'>
jQuery(document).ready(function(){  
	
	$("#contactos").tokenInput("http://sms.smstechnosoft.com/components/com_ztadmin/autocomplete/contactos.php?user=<?php echo $user->id; ?>", 
							{
								theme: "facebook",
								hintText: "Eliga los contactos",
								noResultsText: "No existen contactos con estos datos ...",
								searchingText: "Buscando ....",
								minChars: 0,
								tokenLimit: 100,
								showing_all_results: true,
								preventDuplicates: true
							}
	);
	
});
</script>



