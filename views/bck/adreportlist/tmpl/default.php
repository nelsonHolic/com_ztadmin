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
PageHelper::initPage("Lista de Reportes", ""); 

?>

<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Lista de Reportes"); ?>


				<div style='text-align:left;font-size:14px;margin-left:50px'>
					<b>Lista de Reportes</b>
				</div>

				
				
				<table class="table table-striped table-bordered table-hover" id="table" style="margin-bottom:5px">
					<thead>
						<tr>
							<th class="hidden-480">Id</th>
							<th class="hidden-480">Nombre</th>
							<th class="hidden-480">Descripcion</th>
							<th class="hidden-480">Ejecutada</th>
							<th class="hidden-480">Ver Detalle</th>
							
						</tr>
					</thead>
					<tbody>
						<?php
							if(isset($this->reports)){
								foreach($this->reports as $item){
									$rutaDetalle = "index.php?option=com_ztadmin&task=adReportExecuteForm&id={$item->id}'";
									$linkDetalle = "<a class='btn blue' href='{$rutaDetalle}'>Ejecutar</a>";
						?>
							<tr class="odd gradeX">
								<td><?php echo $item->id; ?></td>
								<td class="hidden-480"><?php echo $item->nombre; ?></td>
								<td class="hidden-480"><?php echo $item->descripcion;?></td>
								<td class="hidden-480"><?php echo $item->ejecutada;?></td>
								<td><?php echo $linkDetalle; ?></td>
							</tr>
						<?php
								}
							}			
						?>
					</tbody>
				</table>
								
				

		<?php PortletHelper::end(); ?>
	</div>
</div>


<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("67");
	Menu::setActive("72");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	//PageHelper::addBreadcrumb("Listado de reportes","index.php?option=com_ztadmin&task=reportList");
	PageHelper::addFinalBreadcrumb("Listado de reportes","index.php?option=com_ztadmin&task=veReportList");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




