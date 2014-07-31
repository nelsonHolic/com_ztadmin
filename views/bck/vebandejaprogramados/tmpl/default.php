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
PageHelper::initPage("Mensajes programados", ""); 
$excelUrl = "index.php?option=com_ztadmin&task=adUsuariosExcel";
//echo "grupo".$this->grupoActual;
//print_r($this->data);

?>

	<div class="container-fluid">
				<div class="row-fluid inbox">
					
					<div class="span12">
						<div class="inbox-header">
							<h1 class="pull-left"></h1>
							<form name='form' class="form-search pull-right">
								<div class="input-append">
									<input class="m-wrap" type="text" name="filtro"  value="<?php echo $this->filtro; ?>" placeholder="Buscar mensajes">
									<button class="btn green" type="button">Buscar</button>
								</div>
								
								<input type='hidden' name='option' value='com_ztadmin' />
								<input type='hidden' name='task' value='veBandejaEntrada' />
							</form>
						</div>
						
						<div class="inbox-loading">Cargando...</div>
						
						<form id='formTable' action="index.php" method="post">
							<div class="inbox-content">
								<table class="table table-selected table-hover ">
									<thead>
										<tr>
											<th colspan="3">
												<div class="" style='display: inline-block;' id="uniform-undefined">
													<span>
														<input type="checkbox" class="" style="opacity: 0;" onclick="toggle(this, 'mensajes[]')">
													</span>
												</div>
												<div class="btn-group">
													<a data-toggle="dropdown" href="#" class="btn mini blue">
													Opciones
													<i class="icon-angle-down "></i>
													</a>
													<ul class="dropdown-menu pull-left">
														<li>
															<a onclick="document.getElementById('formTable').submit(); return false;">
															<i class="icon-trash"></i> Borrar</a>
														</li>						
														<li>
															<a data-toggle='modal'  role='button' href='#modalEliminar'>
															<i class="icon-trash"></i> Borrar todo </a>
														</li>						
													</ul>
												</div>
												
												<div aria-hidden="true" aria-labelledby="myModalLabel3" role="dialog" 
												 tabindex="-1" class="modal hide fade" id="modalEliminar" style="display: none;">
														<div class="modal-header">
															<button aria-hidden="true" data-dismiss="modal" class="close" type="button"></button>
															<h3 id="myModalLabel3">Confirmar eliminaci&oacute;n</h3>
														</div>
														<div class="modal-body">
															<p>Desea eliminar todos los elementos de la bandeja de programados, recuerde que estos cambios no pueden ser recuperados.</p>
														</div>
														<div class="modal-footer">
															<button aria-hidden="true" data-dismiss="modal" class="btn">Cancelar</button>
															<a class='btn blue' href='index.php?option=com_ztadmin&task=veBandejaProgramadosBorrarTodos'>Borrar</a>
														</div>
												</div>
											</th>
											<th colspan="3" class="text-right">
												<!--<ul class="unstyled inline inbox-nav">
													<li><span>1-30 de 100</span></li>
													<li><i class="icon-angle-left  pagination-left"></i></li>
													<li><i class="icon-angle-right pagination-right"></i></li>
												</ul>-->
											</th>
										</tr>
									</thead>
									<tbody>
										<?php
											if(isset($this->data)){
												foreach($this->data as $item){
										?>
										<tr>
											<td class="inbox-small-cells">
												<div class="" id="uniform-undefined"><span>
													<input type="checkbox" name='mensajes[]' value="<?php echo $item->id; ?>" class="mail-checkbox" style="opacity: 0;"/></span>
												</div>
											</td>
											<td class="inbox-small-cells"><i class="icon-star"></i></td>
											<td class="view-message  hidden-phone">
												<a href="index.php?option=com_ztadmin&task=veMensajeDetalleOutbox&id=<?php echo $item->id;?>" >
													<?php echo $item->receiver;?>
												</a>
											</td>
											<td class="view-message  hidden-phone">
												<a href="index.php?option=com_ztadmin&task=veMensajeDetalleOutbox&id=<?php echo $item->id;?>" >
													<?php echo $item->msgdata;?>  
												</a>
											</td>
											<td class="view-message ">
												<a href="index.php?option=com_ztadmin&task=veMensajeDetalleOutbox&id=<?php echo $item->id;?>" >
													<?php echo $item->sendondate;?>
												</a>
											</td>
											<!--<td class="view-message  inbox-small-cells"><i class="icon-paper-clip"></i></td>-->
										</tr>
										<?php
												}
											}
										?>
									
										
									
										<!--<tr class="read">
											<td class="inbox-small-cells">
												<div class="checker" id="uniform-undefined"><span><input type="checkbox" class="mail-checkbox" style="opacity: 0;"></span></div>
											</td>
											<td class="inbox-small-cells"><i class="icon-star"></i></td>
											<td class="view-message hidden-phone">Concierto Santiago Cruz</td>
											<td class="view-message">21 Usuarios || 0 Grupos = 21</td>
											<td class="view-message inbox-small-cells"></td>
											<td class="view-message text-right">Marzo 15</td>
										</tr>
										-->
										
										
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
							</div>
						<?php echo JHTML::_( 'form.token' ); ?>
							<input type='hidden' name='option' value='com_ztadmin' />
							<input type='hidden' name='task' value='veMensajeEliminarOutbox' />
						</form>
					</div>
				</div>
	</div>
		
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	if($user->tipo == 'V'){
		Menu::setActive("55");
		Menu::setActive("56");
		Menu::setActive("82");
	}
	else if($user->tipo == 'U'){
		Menu::setActive("85");
		Menu::setActive("88");
		Menu::setActive("93");
	}
	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Bandejas","index.php");
	PageHelper::addFinalBreadcrumb("Programados");
	
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


