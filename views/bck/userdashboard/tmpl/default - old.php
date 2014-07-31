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
$menu = Menu::getMenu('A');
?>

<!-- BEGIN HEADER -->
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="navbar-inner">
			<div class="container-fluid">
				
				<?php echo GuiHeaderHelper::logo(); ?>
				<?php echo GuiHeaderHelper::menuToggler(); ?>
			
				<!-- BEGIN TOP NAVIGATION MENU -->					
				<?php echo GuiHeaderHelper::topNavegationMenu(); ?>
					
					<!-- BEGIN NOTIFICATION DROPDOWN -->	
					<?php echo GuiNotificationBarHelper::show(); ?>
					<!-- END NOTIFICATION DROPDOWN -->
					
					<!-- BEGIN INBOX DROPDOWN -->
					<?php echo GuiInboxBarHelper::show(); ?>
					<!-- END INBOX DROPDOWN -->
					
					
					<!-- BEGIN TODO DROPDOWN -->
					<?php echo GuiTaskBarHelper::show(); ?>
					<!-- END TODO DROPDOWN -->
					
					<!-- Display user dropdown -->
					<?php 
						echo GuiUserDropdownHelper::show("Andres Quintero"); 
					?>
					
	
				<?php echo GuiHeaderHelper::endTopNavegationMenu(); ?>
			</div>
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	
	
	
	<!-- BEGIN CONTAINER -->
	<div class="page-container row-fluid">
		<!-- BEGIN SIDEBAR -->
		<?php  GuiLeftHelper::initLeftPanel(); ?>
			<?php  GuiLeftHelper::showSearchForm(); ?>	
			<?php echo $menu; ?>	
		<?php  GuiLeftHelper::endLeftPanel(); ?>
		
		<!-- END SIDEBAR -->
		
		
		<!-- BEGIN PAGE -->
		<?php PageHelper::initPage("Centro de mando", "Estadisticas de la plataforma"); 
		
					PageHelper::initRow();
						GraphicButtonHelper::show("tablet", "15", "Nuevos pedidos", "index.php", "Ver m&aacute;s", 3, "red"); 
						GraphicButtonHelper::show("user", "10", "Mensajes", "index.php", "Ver todos", 3, "blue"); 
						GraphicButtonHelper::show("tablet", "15", "Nuevos pedidos", "index.php", "Ver m&aacute;s", 3, "green"); 
						GraphicButtonHelper::show("tablet", "15", "Nuevos pedidos", "index.php", "Ver m&aacute;s", 3, "yellow"); 
					PageHelper::endRow();
					
					PageHelper::separator();
					
					PageHelper::initRow();
						GraphicButtonHelper::show("user", "10", "Mensajes", "index.php", "Ver todos",6); 
						GraphicButtonHelper::show("tablet", "15", "Nuevos pedidos", "index.php", "Ver m&aacute;s",6); 
					PageHelper::endRow();	
					
					PageHelper::initRow();
						PageHelper::span(6);
							PortletHelper::show("Promedio de notas", "Refrescar", "red");
								
							PortletHelper::end();
						PageHelper::endSpan();
						
						PageHelper::span(6);
							PortletHelper::show("Promedio de notas", "Refrescar");
								
							PortletHelper::end();
						PageHelper::endSpan();
					PageHelper::endRow();
		?>
		
		
		
		<div class="row-fluid">
						<div class="span6">
							<!-- BEGIN PORTLET-->
							<div class="portlet solid bordered light-grey">
								<div class="portlet-title">
									<h4><i class="icon-bar-chart"></i>Site Visits</h4>
									<div class="tools">
										<div class="btn-group pull-right" data-toggle="buttons-radio">
											<a href="javascript:;" class="btn mini">Users</a>
											<a href="javascript:;" class="btn mini active">Feedbacks</a>
										</div>
									</div>
								</div>
								<div class="portlet-body">
									<div id="site_statistics_loading">
										<img src="templates/chronos/img/loading.gif" alt="loading" />
									</div>
									<div id="site_statistics_content" class="hide">
										<div id="site_statistics" class="chart"></div>
									</div>
								</div>
							</div>
							<!-- END PORTLET-->
						</div>
						<div class="span6">
							<!-- BEGIN PORTLET-->
							<div class="portlet solid light-grey bordered">
								<div class="portlet-title">
									<h4><i class="icon-bullhorn"></i>Activities</h4>
									<div class="tools">
										<div class="btn-group pull-right" data-toggle="buttons-radio">
											<a href="javascript:;" class="btn blue mini active">Users</a>
											<a href="javascript:;" class="btn blue mini">Orders</a>
										</div>
									</div>
								</div>
								<div class="portlet-body">
									<div id="site_activities_loading">
										<img src="assets/img/loading.gif" alt="loading" />
									</div>
									<div id="site_activities_content" class="hide">
										<div id="site_activities" style="height:100px;"></div>
									</div>
								</div>
							</div>
							<!-- END PORTLET-->
							<!-- BEGIN PORTLET-->
							<div class="portlet solid bordered light-grey">
								<div class="portlet-title">
									<h4><i class="icon-signal"></i>Carga del servidor</h4>
									<div class="tools">
										<div class="btn-group pull-right" data-toggle="buttons-radio">
											<a href="javascript:;" class="btn red mini active">
											<span class="hidden-phone">Database</span>
											<!--<span class="visible-phone">DB</span>-->
											</a>
											<!--<a href="javascript:;" class="btn red mini">Web</a>-->
										</div>
									</div>
								</div>
								<div class="portlet-body">
									<div id="load_statistics_loading">
										<img src="assets/img/loading.gif" alt="loading" />
									</div>
									<div id="load_statistics_content" class="hide">
										<div id="load_statistics" style="height:108px;"></div>
									</div>
								</div>
							</div>
							<!-- END PORTLET-->
						</div>
						
						
		</div>
				
		
			
			
		<?php PageHelper::endPage(); ?>
					
	<?php PageHelper::footerPage(); ?>

	
	<!--Load Javascript Needed-->
	<?php JsHelper::addCoreJs(); ?>
	<?php JsHelper::addGuiHandler(); ?>
	<?php JsHelper::addJQueryFlot(); ?>
	

	<script src="templates/chronos/scripts/index.js" type="text/javascript"></script>

	
	<script type='text/javascript'>
		jQuery(document).ready(function() {		
			App.init(); // initlayout and core plugins
			//initCharts();
			//showLineGraphic();
			//Index.init();
			//Index.initJQVMAP(); // init index page's custom scripts
			//Index.initCalendar(); // init index page's custom scripts
			//Index.initCharts(); // init index page's custom scripts
			//Index.initChat();
			//Index.initMiniCharts();
			//Index.initDashboardDaterange();
			//Index.initIntro();
		});
	</script>

	
	
<?php echo GuiNotificationBarHelper::addItem("Nuevo usuario", "12:50 am","success"); ?>

<?php echo GuiInboxBarHelper::addItem("","",""); ?>
<?php echo GuiInboxBarHelper::addItem("","",""); ?>
<?php echo GuiInboxBarHelper::seeAll(""); ?>

<?php echo GuiTaskBarHelper::addItem("hi","Progreso proyecto 1","40","success"); ?>


<?php
	//Menu de usuario
	GuiUserDropdownHelper::addItem("","user","Perfil");
	GuiUserDropdownHelper::addItem("","calendar","Calendario");
	GuiUserDropdownHelper::addItem("","envelope","Mensajes");
	GuiUserDropdownHelper::addItem("","tasks","Tareas");
	GuiUserDropdownHelper::addSeparator();
	GuiUserDropdownHelper::addItem("","key","Salir");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Centro de mando","index.php");
	PageHelper::addBreadcrumb("Proyectos","index.php");
	PageHelper::addBreadcrumb("Proyecto Polla Mundialista","index.php");
	PageHelper::addFinalBreadcrumb("Tareas Pendientes");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>


<?php 
	
	$serie1[] = array(5,10,"X=5  Y=10", "Informacion Adicional");
	$serie1[] = array(12,4,"X=12 - Y=4", "");
	$serie1[] = array(15,10,"X=15 - Y=10", "");
	$serie1[] = array(30,8,"X=30 - Y=8", "");
	$serie1[] = array(32,40,"X=30 - Y=8", "");
	
	$serie2[] = array(2,10,"X=5 - Y=10", "jhernandez");
	$serie2[] = array(12,12,"X=12 - Y=4", "");
	$serie2[] = array(30,10,"X=15 - Y=10", "");
	$serie2[] = array(30,15,"X=30 - Y=8", "");
	$serie2[] = array(42,40,"X=30 - Y=8", "");
	
	$serie3[] = array(1,10);
	$serie3[] = array(20,30);
	$serie3[] = array(40,50);
	$serie3[] = array(60,10);
	$serie3[] = array(90,39);
	
	
	Graphic2DHelper::showGraphicLines("site_statistics", "site_statistics_loading", "site_statistics_content",  $serie1, $serie2, "Informacion", 120  );
	Graphic2DHelper::showGraphicLines2("load_statistics", "load_statistics_loading", "load_statistics_content",  "Datos estadisticos", $serie3);
	
	

?>




