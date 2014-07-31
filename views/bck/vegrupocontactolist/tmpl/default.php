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
PageHelper::initPage("Listado de contactos", ""); 
$excelUrl = "index.php?option=com_ztadmin&task=adUsuariosExcel";
$id = $this->id;

?>
			<form name='form'>
				<div class="row-fluid">
					<div class="span12">
						<?php PortletHelper::show("Listado de contactos"); ?>
						
								<div class="clearfix">
									Filtro
									<input  name='filtro' type='text' size='10' value='<?php echo $this->filtro;?>'/>
									
									<div class="btn-group">
										<button id="sample_editable_1_new" class="btn green" >
										Aplicar&nbsp;<i class="icon-plus"></i>
										</button>
									</div>
									
									<div class="btn-group pull-right">
										<button class="btn dropdown-toggle" data-toggle="dropdown">Opciones <i class="icon-angle-down"></i>
										</button>
										<ul class="dropdown-menu">
											<li><a href="#" onClick="jQuery('#table').printElement();">Imprimir</a></li>
											<li><a href="#" onClick="window.open('<?php echo $excelUrl;?>')">Exportar a Excel</a></li>
										</ul>
									</div>
								</div>
								<table class="table table-striped table-bordered table-hover" id="table" style="margin-bottom:5px">
									<thead>
										<tr>
											<th class="hidden-480">Nombre</th>
											<th class="hidden-480">Correo</th>
											<th class="hidden-480">Movil</th>
											<th class="hidden-480">Editar</th>
											<th class="hidden-480">Eliminar</th>
											
										</tr>
									</thead>
									<tbody>
										<?php
											if(isset($this->data)){
												foreach($this->data as $item){
													$rutaEditar = "index.php?option=com_ztadmin&task=veContactoForm&id=".$item->contacto;
													$linkEditar = "<a class='btn blue' href='{$rutaEditar}'>Editar</a>";
													$rutaRetirar = "index.php?option=com_ztadmin&task=veGrupoContactoDelete&id=" . $item->id;
													$linkRetirar = "<a class='btn blue' href='{$rutaRetirar}'>Retirar</a>";
													$confirmarRetirar = "<a data-toggle='modal' class='btn blue' role='button' href='#myModal{$item->id}'>Retirar</a>";
										?>
											<tr class="odd gradeX">
												<td class="hidden-480"><?php echo $item->nombre;?></td>
												<td class="hidden-480"><?php echo $item->correo;?></td>
												<td class="hidden-480"><?php echo $item->movil?></td>
												<td class="hidden-480"><?php echo $linkEditar;?></td>
												<td class="hidden-480"><?php echo $confirmarRetirar;?></td>
											</tr>
											<div aria-hidden="true" aria-labelledby="myModalLabel3" role="dialog" 
												 tabindex="-1" class="modal hide fade" id="myModal<?php echo $item->id;?>" style="display: none;">
														<div class="modal-header">
															<button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
															<h3 id="myModalLabel3">Confirmar Retiraci&oacute;n</h3>
														</div>
														<div class="modal-body">
															<p>Desea retirar del grupo al contacto seleccionado.</p>
														</div>
														<div class="modal-footer">
															<button aria-hidden="true" data-dismiss="modal" class="btn">Cancelar</button>
															<?php echo $linkRetirar ;?>
														</div>
											</div>
										<?php
												}
											}			
										?>
									</tbody>
								</table>
								
								
								
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
				<input type='hidden' name='id' value='<?php echo $id;?>' />
				<input type='hidden' name='option' value='com_ztadmin' />
				<input type='hidden' name='task' value='veGrupoContactoList' />
			</form>

		
<?php 
	BasicPageHelper::endPage(); 
	
	
	//Selecciona el menu
	if($user->tipo == 'V'){
		Menu::setActive("58");
		Menu::setActive("59");
		Menu::setActive("61");
	}
	else if($user->tipo == 'U'){
		Menu::setActive("95");
		Menu::setActive("97");
		Menu::setActive("99");
	}
	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Contactos","index.php");
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


