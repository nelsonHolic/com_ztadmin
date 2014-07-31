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
PageHelper::initPage("Listado de referidos", ""); 
$excelUrl = "index.php?option=com_ztadmin&task=adReferidosExcel";
//echo "grupo".$this->grupoActual;

?>
			<form name='form'>
				<div class="row-fluid">
					<div class="span12">
						<?php PortletHelper::show("Listado de Referidos"); ?>
						
								<div class="clearfix">
									Filtro
									<input  name='filtro' type='text' size='10' value='<?php echo $this->filtro;?>'/>
									 Grupo
									 <select name='grupo'>
										<option value="">Seleccione un grupo</option>
										<?php foreach($this->grupo as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $this->grupoActual ? "selected" : ""); ?>>
												<?php echo $item->descripcion;?>
										</option>
										<?php } ?>
									</select>
									 Plataforma
									 <select name='plataforma'>
										<option value="">Seleccione una plataforma</option>
										<?php foreach($this->plataforma as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $this->plataformaActual ? "selected" : ""); ?>>
												<?php echo $item->descripcion;?>
										</option>
										<?php } ?>
									</select>
									
									<br/> 
									
									Fecha de inicio <input name='fechaInicio' type="text" value="<?php echo $this->fechaInicio; ?>"  size="16" class="m-wrap m-ctrl-medium date-picker" >
									
									Fecha de final <input  name='fechaFinal' type="text" value="<?php echo $this->fechaFinal; ?>" size="16" class="m-wrap m-ctrl-medium date-picker">
									
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
											<th class="hidden-480">Id</th>
											<th class="hidden-480">Cedula</th>
											<th class="hidden-480">Nombre</th>
											<th class="hidden-480">Grupo</th>
											<th class="hidden-480">Plataforma</th>
											<th class="hidden-480">Fecha creacion</th>
											<th class="hidden-480">Ver Detalle</th>
											
										</tr>
									</thead>
									<tbody>
										<?php
											if(isset($this->data)){
												foreach($this->data as $item){
													$rutaDetalle = "index.php?option=com_ztadmin&task=asReferidoDetalle&id=" . $item->id;
													$linkDetalle = "<a class='btn blue' href='{$rutaDetalle}'>Ver Detalle</a>";
										?>
											<tr class="odd gradeX">
												<td><?php echo $item->id; ?></td>
												<td class="hidden-480"><?php echo $item->cedula; ?></td>
												<td class="hidden-480"><?php echo $item->nombre;?></td>
												<td class="hidden-480"><?php echo $item->descripcion;?></td>
												<td class="hidden-480"><?php echo $item->plataforma; ?></td>
												<td class="center hidden-480"><?php echo $item->fecha_creacion; ?></td>
												<td><?php echo $linkDetalle; ?></td>
											</tr>
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
				<input type='hidden' name='task' value='adlistado' />
			</form>

		
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("3");
	Menu::setActive("4");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	//PageHelper::addBreadcrumb("Proyecto Polla Mundialista","index.php");
	PageHelper::addFinalBreadcrumb("Listado de referidos");
	
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


