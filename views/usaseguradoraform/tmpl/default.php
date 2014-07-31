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

$titleMsg = (isset($data->id) ? "Editar " : "Crear  ");

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage( $titleMsg . " Aseguradora", ""); 

$id          = (isset($data->id) ? $data->id : "");
$descripcion = (isset($data->descripcion) ? $data->descripcion : "");

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Formulario de Aseguradoras"); ?>
			
				
				<form id="form" class="form-horizontal" method="post" >
					<fieldset>
						
						<div class="alert alert-success hide">
                              <button class="close" data-dismiss="alert"></button>
                             Validaci&oacute;n de datos correcta!
                        </div>
						<div class="alert alert-error hide">
							<button class="close" data-dismiss="alert"></button>
                             Por favor ingrese los campos obligatorios.
                        </div>
					
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group ">
									<label class="control-label">Descripci&oacute;n<span class="required">*</span></label>
									<div class="controls">
									   <input name="descripcion" type="text" value='<?php echo $descripcion;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>

						

					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit" onclick='validate'>Guardar</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='id' value='<?php echo $id;?>' />
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='usAseguradoraSave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("115");
	Menu::setActive("111");

	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Aseguradoras","index.php?option=com_ztadmin&task=usAseguradoraList");
	PageHelper::addFinalBreadcrumb($titleMsg);
	 
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
	
	//Validate form data
	Validation::initValidation("form");
	Validation::required("nombre", true);
	//Validation::email("correo");
	Validation::number("movil");
	Validation::endValidation();
?>

<script src="templates/chronos/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>

<script type='text/javascript'>
jQuery(document).ready(function(){  

	if (jQuery().datepicker) {
        jQuery('.date-picker').datepicker({ dateFormat: 'yy-mm-dd' });
    }
	

});
</script>



