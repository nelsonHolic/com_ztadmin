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
PageHelper::initPage("Listado de usuarios", ""); 
$excelUrl = "index.php?option=com_ztadmin&task=adUsuariosExcel";
//echo "grupo".$this->grupoActual;

?>
			<form name='form'>
				<div class="row-fluid">
					<div class="span12">
						<?php PortletHelper::show("Listado de usuarios"); ?>
						
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
											<th class="hidden-480">Nombre de usuario</th>
											<th class="hidden-480">Mensajes</th>
											<th class="hidden-480">Correo</th>
											<th class="hidden-480">Tipo</th>
											<th class="hidden-480">Estado</th>
											<th class="hidden-480">Editar</th>
											<th class="hidden-480">Accion</th>
											
										</tr>
									</thead>
									<tbody>
										<?php
											if(isset($this->data)){
												foreach($this->data as $item){
													$rutaEditar = "index.php?option=com_ztadmin&task=adUsuarioForm&id=" . $item->id;
													$linkEditar = "<a class='btn blue' href='{$rutaEditar}'>Editar</a>";
													$rutaEliminar = "index.php?option=com_ztadmin&task=adUsuarioDelete&id=" . $item->id;
													$linkEliminar = "<a class='btn blue' href='{$rutaEliminar}'>Eliminar</a>";
													$confirmarEliminar = "<a data-toggle='modal' class='btn blue' role='button' href='#myModal{$item->id}'>Eliminar</a>";
													$rutaBloquear = "index.php?option=com_ztadmin&task=adUsuarioBlock&id=" . $item->id."&b=1";
													$linkBloquear = "<a class='btn blue' href='{$rutaBloquear}'>Bloquear</a>";
													$rutaDesBloquear = "index.php?option=com_ztadmin&task=adUsuarioBlock&id=" .$item->id."&b=0";
													$linkDesBloquear = "<a class='btn blue' href='{$rutaDesBloquear}'>Desbloquear</a>";
										?>
											<tr class="odd gradeX">
												<td class="hidden-480"><?php echo $item->name;?></td>
												<td class="hidden-480"><?php echo $item->username;?></td>
												<td class="hidden-480"><?php echo $item->mensajes;?></td>
												<td class="hidden-480"><?php echo $item->email;?></td>
												<td class="hidden-480"><?php echo ( $item->tipo == "V" ?  "Vendedor" : "Usuario" );?></td>
												<td class="hidden-480"><?php echo (($item->block > 0) ?       "Bloqueado" : "Activo");?></td>
												<td class="hidden-480"><?php echo $linkEditar;?></td>
												<td class="hidden-480"><?php echo (($item->block > 0) ?        $linkDesBloquear : $linkBloquear);?></td>
											</tr>
											<div aria-hidden="true" aria-labelledby="myModalLabel3" role="dialog" 
												 tabindex="-1" class="modal hide fade" id="myModal<?php echo $item->id;?>" style="display: none;">
														<div class="modal-header">
															<button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
															<h3 id="myModalLabel3">Confirmar eliminacion&oacute;n</h3>
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
				<input type='hidden' name='option' value='com_ztadmin' />
				<input type='hidden' name='task' value='adUsuariosListar' />
			</form>

		
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
		Menu::setActive("53");
		Menu::setActive("1");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Usuarios","index.php");
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


