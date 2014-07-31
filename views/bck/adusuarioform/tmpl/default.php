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


$data = $this->data;

$bol= (isset($data->id) ? "Editar" : "Crear");

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( $user->username, $menu);
PageHelper::initPage($bol." usuario", ""); 


$id			= (isset($data->id) ? $data->id : "");
$nombre 	= (isset($data->name) ? $data->name : "");
$username 	= (isset($data->username) ? $data->username : "");
$email 		= (isset($data->email) ? $data->email : "");
$password 	= (isset($data->password) ? $data->password : "");
$tipo 		= (isset($data->tipo) ? $data->tipo : "");
$telefono	= (isset($data->telefono) ? $data->telefono : "");

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show($bol." Usuario #{$this->id}"); ?>
			
				
				<form class="form-horizontal" method="POST" >
					<fieldset>					
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Nombre</label>
									<div class="controls">
									   <input name="name" type="text" value='<?php echo $nombre;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Usuario</label>
									<div class="controls">
									  <?php if(isset($data->username)){
												echo $username;
											}
											else{
												echo "<input name='username' type='text' value='' class='m-wrap span12' >";
											}
										?>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Correo</label>
									<div class="controls">
									   <input name="email" type="text" value='<?php echo $email;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Tel&eacute;fono</label>
									<div class="controls">
									   <input name="telefono" type="text" value='<?php echo $telefono;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Clave</label>
									<div class="controls">
									   <input name="clave1" type="password" value='' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>		
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Repetir clave</label>
									<div class="controls">
									   <input name="clave2" type="password" value='' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Tipo</label>
									<div class="controls">
										 <select name='tipo'>
											<option value="">Seleccione un tipo de usuario</option>
											<?php foreach($this->tipo as $item){?>
												<option value="<?php echo $item->codigo ?>" <?php echo ($item->codigo == $this->tipoActual ? "selected" : ($item->codigo == $tipo ? "Selected" : "")); ?>>
													<?php echo $item->descripcion;?>
												</option>
											<?php } ?>
									</select>
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
					<input type='hidden' name='task' value='adUsuarioSave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
	
<?php 
	BasicPageHelper::endPage(); 
	//(($data->tipo == "V") ? 'Vendedor' : 'Usuario') 
	//Selecciona el menu
	if(isset($data->id)){
		Menu::setActive("53");
		Menu::setActive("1");
	}else{	
		Menu::setActive("53");
		Menu::setActive("7");
	}
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Usuario","index.php");
	PageHelper::addFinalBreadcrumb($bol);
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>




