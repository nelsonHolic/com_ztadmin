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
PageHelper::initPage( $titleMsg . "Tarea", ""); 

$id          = (isset($data->id) ? $data->id : "");
$descripcion = (isset($data->descripcion) ? $data->descripcion : "");
$observaciones = (isset($data->observaciones) ? $data->observaciones : "");
$fecha_creacion = (isset($data->fecha_creacion) ? $data->fecha_creacion : date("Y-m-d"));
$fecha_entrega = (isset($data->fecha_entrega) ? $data->fecha_entrega : "");
$usuario_asignado = (isset($data->usuario_asignado) ? $data->usuario_asignado : "");

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Formulario de Tareas"); ?>
			
				
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
					

                        <div class="clearfix">
										<form name='formBusqueda'>
											Filtro
											<input  name='filtro' type='text' size='10' value='<?php echo $this->filtro;?>'/>
											
											<div class="btn-group">
												<button id="sample_editable_1_new" class="btn green" >
												Aplicar&nbsp;<i class="icon-plus"></i>
												</button>
											</div>
											<input type='hidden' name='option' value='com_ztadmin' />
											<input type='hidden' name='task' value='usTareaHistorial' />
										</form>
										
										<div class="btn-group pull-right">
											<button class="btn dropdown-toggle" data-toggle="dropdown">Opciones <i class="icon-angle-down"></i>
											</button>
											<ul class="dropdown-menu">
												<!--<li><a href="#" onClick="jQuery('#table').printElement();">Imprimir</a></li>
												<li><a onclick="document.getElementById('formTable').submit(); return false;"> Borrar</a></li>
												<li><a data-toggle='modal'  role='button' href='#modalEliminar'> Borrar Todo </a></li>
												-->
											</ul>
										</div>
										
										<div aria-hidden="true" aria-labelledby="myModalLabel3" role="dialog" 
												 tabindex="-1" class="modal hide fade" id="modalEliminar" style="display: none;">
														<div class="modal-header">
															<button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
															<h3 id="myModalLabel3">Confirmar eliminaci&oacute;n</h3>
														</div>
														<div class="modal-body">
															<p>Desea eliminar todos los Tareas, recuerde que estos cambios no pueden ser recuperados.</p>
														</div>
														<div class="modal-footer">
															<button aria-hidden="true" data-dismiss="modal" class="btn">Cancelar</button>
															<a class='btn blue' href='index.php?option=com_ztadmin&task=veContactoDeleteAll'>Borrar</a>
														</div>
										</div>
									</div>
									
								<form id='formTable' >
									<table class="table table-striped table-bordered table-hover" id="table" style="margin-bottom:5px">
										<thead>
											<tr>
												<th class="hidden-480"><input type="checkbox" class="" style="opacity: 0;" onclick="toggle(this, 'id[]')"></th>
												<th class="hidden-480">Observaci&oacute;n</th>
												<th class="hidden-480">Fecha</th>
												<th class="hidden-480">Usuario</th>
												
											</tr>
										</thead>
										<tbody>
											<?php
												if(isset($this->data)){
													foreach($this->data as $item){
											?>
												<tr class="odd gradeX">
													<td class="hidden-480"><input type="checkbox" name='id[]' value="<?php echo $item->id; ?>" class="mail-checkbox" style="opacity: 0;"/></td>
													<td class="hidden-480"><?php echo $item->observacion;?></td>
													<td class="hidden-480"><?php echo $item->fecha;?></td>
													<td class="hidden-480"><?php echo $item->usuario;?></td>
												</tr>
												<div aria-hidden="true" aria-labelledby="myModalLabel3" role="dialog" 
													 tabindex="-1" class="modal hide fade" id="myModal<?php echo $item->id;?>" style="display: none;">
															<div class="modal-header">
																<button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
																<h3 id="myModalLabel3">Confirmar eliminaci&oacute;n</h3>
															</div>
															<div class="modal-body">
																<p>Desea eliminar el elemento seleccionado, recuerde que estos cambios no pueden ser recuperados.</p>
															</div>
															<div class="modal-footer">
																<button aria-hidden="true" data-dismiss="modal" class="btn">Cancelar</button>
																<?php echo $linkEliminar ;?>
															</div>
												</div>
											<?php
													}
												}			
											?>
										</tbody>
									</table>
									<input type='hidden' name='option' value='com_ztadmin' />
									<input type='hidden' id='task' name='task' value='usTareaHistorial' />
								</form>
								

						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group ">
									<label class="control-label">Observacion<span class="required">*</span></label>
									<div class="controls">
									   <input name="observacion" type="text" value='<?php echo $descripcion;?>' class="m-wrap span12" >
									</div>
									
								</div>
							</div>
						</div>

						

					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit" onclick='validate'>Agregar Observacion</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='tarea' value='<?php echo $id;?>' />
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='usTareaHistorialSave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("128");
	Menu::setActive("129");

	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Tareas","index.php?option=com_ztadmin&task=usTareaList");
	PageHelper::addFinalBreadcrumb($titleMsg);
	 
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
	
	//Validate form data
	Validation::initValidation("form");
	Validation::required("nombre", true);
	Validation::required("observacion", false);
	//Validation::number("movil");
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



