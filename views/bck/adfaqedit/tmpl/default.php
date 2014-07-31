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
PageHelper::initPage("Nueva Pregunta", ""); 

$data = $this->data;

$id = (isset($data->id) ? $data->id : "");
$pregunta = (isset($data->pregunta) ? $data->pregunta : "");
$titulo = (isset($data->titulo) ? $data->titulo : "");
$contenido = (isset($data->contenido) ? $data->contenido : "");

Editor::includeEditor2(array('contenido'));
?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Crear / Editar Pregunta "); ?>
			
				
				<form class="form-horizontal" method="POST" >
					<fieldset>
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Pregunta</label>
									<div class="controls">
									   <input name="pregunta"  type="text" value='<?php echo $pregunta;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
					
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Titulo</label>
									<div class="controls">
									   <input name="titulo" type="text" value='<?php echo $titulo;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
												
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Contenido</label>
									<div class="controls">
										<textarea rows="50" cols="150" class="ckeditor" style="height:150px" id="contenido" name="contenido">
											<?php echo $contenido; ?>
										</textarea> 
									</div>
								</div>
							</div>
						</div>
						
					</fieldset>
					
					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit">Guardar</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='id' value='<?php echo $id;?>' />
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='adfaqsave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
	
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("29");
	Menu::setActive("30");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Preguntas","index.php?option=com_ztadmin&task=tiquetelist");
	PageHelper::addFinalBreadcrumb("Crear");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




