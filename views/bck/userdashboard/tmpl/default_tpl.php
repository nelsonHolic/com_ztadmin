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
		<?php PageHelper::initPage("Centro de mando", "Estadisticas de la plataforma"); ?>
		
			Page content here!!
			
			
			
		<?php PageHelper::endPage(); ?>
					
	<?php PageHelper::footerPage(); ?>

	
	<!--Load Javascript Needed-->
	<?php JsHelper::addCoreJs(); ?>
	<?php JsHelper::addGuiHandler(); ?>

	<!-- <script src="templates/chronos/scripts/index.js" type="text/javascript"></script>	-->
	
	<script>
		jQuery(document).ready(function() {		
			App.init(); // initlayout and core plugins
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




