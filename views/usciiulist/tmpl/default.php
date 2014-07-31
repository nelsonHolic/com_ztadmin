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
PageHelper::initPage("Listado de Ciius", ""); 
//$excelUrl = "index.php?option=com_ztadmin&task=adUsuariosExcel";

?>
			
				<div class="row-fluid">
					<div class="span12">
						<?php PortletHelper::show("Listado de Ciius"); ?>		
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
											<input type='hidden' name='task' value='usCiiuList' />
										</form>
										
										<div class="btn-group pull-right">
											<button class="btn dropdown-toggle" data-toggle="dropdown">Opciones <i class="icon-angle-down"></i>
											</button>
											<ul class="dropdown-menu">
												<!--<li><a href="#" onClick="jQuery('#table').printElement();">Imprimir</a></li>
												<li><a onclick="document.getElementById('formTable').submit(); return false;"> Borrar</a></li>
												<li><a data-toggle='modal'  role='button' href='#modalEliminar'> Borrar Todo </a></li>-->
												
											</ul>
										</div>
										
										<div aria-hidden="true" aria-labelledby="myModalLabel3" role="dialog" 
												 tabindex="-1" class="modal hide fade" id="modalEliminar" style="display: none;">
														<div class="modal-header">
															<button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
															<h3 id="myModalLabel3">Confirmar eliminaci&oacute;n</h3>
														</div>
														<div class="modal-body">
															<p>Desea eliminar todos los ciius, recuerde que estos cambios no pueden ser recuperados.</p>
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
												<th class="hidden-480">Codigo</th>
												<th class="hidden-480">Descripci&oacute;n</th>
												<th class="hidden-480">Editar</th>
												<th class="hidden-480">Eliminar</th>
												
											</tr>
										</thead>
										<tbody>
											<?php
												if(isset($this->data)){
													foreach($this->data as $item){
														$rutaEditar = "index.php?option=com_ztadmin&task=usCiiuForm&id=".$item->id;
														$linkEditar = "<a class='btn blue' href='{$rutaEditar}'>Editar</a>";
														$rutaEliminar = "index.php?option=com_ztadmin&task=usCiiuDelete&id=" . $item->id;
														$linkEliminar = "<a class='btn blue' href='{$rutaEliminar}'>Eliminar</a>";
														$confirmarEliminar = "<a data-toggle='modal' class='btn blue' role='button' href='#myModal{$item->id}'>Eliminar</a>";
											?>
												<tr class="odd gradeX">
													<td class="hidden-480"><input type="checkbox" name='id[]' value="<?php echo $item->id; ?>" class="mail-checkbox" style="opacity: 0;"/></td>
													<td class="hidden-480"><?php echo $item->codigo;?></td>
													<td class="hidden-480"><?php echo $item->descripcion;?></td>
													<td class="hidden-480"><?php echo $linkEditar;?></td>
													<td class="hidden-480"><?php echo $confirmarEliminar;?></td>
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
									<input type='hidden' id='task' name='task' value='usCiiuList' />
								</form>
								
								
								
								<div class="row-fluid">
									<div class="span4">
										<div class="dataTables_info" id="sample_1_info"><?php echo $this->pageNav->getPagesCounter();?></div>
									</div>
								
									<div class="span8">
										<?php echo $this->pageNav->getPagesLinks();?>
									</div>
								</div>
							<?php PortletHelper::end(); ?>
						<!-- END EXAMPLE TABLE PORTLET-->
					</div>
				</div>
			</form>

		
<?php 
	BasicPageHelper::endPage(); 
	
	Menu::setActive("117");
	Menu::setActive("124");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Ciius","index.php?option=com_ztadmin&task=usCiiuList");
	PageHelper::addFinalBreadcrumb("Listar");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>

<script src="templates/chronos/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>
<script type='text/javascript'>
jQuery(document).ready(function(){  
    if (jQuery().datepicker) {
        $('.date-picker').datepicker();
    }
});
</script>


