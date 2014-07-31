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
PageHelper::initPage("Distribuci&oacute;n de mensajes", ""); 
$excelUrl = "index.php?option=com_ztadmin&task=adUsuariosExcel";
//echo "grupo".$this->grupoActual;

?>
			<form name='form'>
				<div class="row-fluid">
					<div class="span12">
						<?php PortletHelper::show("Distribuci&oacute;n de mensajes"); ?>
						
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
											<th class="hidden-480">Usuario</th>
											<th class="hidden-480">Nombre</th>
											<th class="hidden-480">Correo</th>
											<th class="hidden-480">Mensajes</th>
											
										</tr>
									</thead>
									<tbody>
										<?php
											if(isset($this->data)){
												foreach($this->data as $item){
												
													//$rutaContacto = "index.php?option=com_ztadmin&task=veGrupoContactoList&id=" . $item->id;
													//$linkContacto = "<a title='ver Contactos' class='btn blue' href='{$rutaContacto}'>".$cant."</a>";
										?>
													
											<tr class="odd gradeX">
												<td class="hidden-480"><?php echo $item->username;?></td>
												<td class="hidden-480"><?php echo $item->name;?></td>
												<td class="hidden-480"><?php echo $item->email;?></td>
												<td class="hidden-480"><?php echo $item->mensajes;?></td>
												<!--<td class="hidden-480"><?php //echo $linkEditar;?></td>-->
												
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
				<input type='hidden' name='task' value='vemensajedistribucion' />
			</form>

		
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("55");
	Menu::setActive("57");
	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("grupos","index.php?option=com_ztadmin&task=veGrupoList");
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


