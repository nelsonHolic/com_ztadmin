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
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage($bol." usuario", ""); 


$id			= (isset($data->id) ? $data->id : "");
$nombre 	= (isset($data->name) ? $data->name : "");
$username 	= (isset($data->username) ? $data->username : "");
$email 		= (isset($data->email) ? $data->email : "");
$password 	= (isset($data->password) ? $data->password : "");
$tipo 		= (isset($data->tipo) ? $data->tipo : "");
$telefono	= (isset($data->telefono) ? $data->telefono : "");
$ilimitado	= (isset($data->ilimitado) ? $data->ilimitado : "");
$seguro		= (isset($data->seguro) ? $data->seguro : "");
?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show($bol." Usuario #{$this->id}"); ?>
			
					
				<form id="form" class="form-horizontal" method="POST" action='index.php'>
					<fieldset>		
						<div class="alert alert-success hide">
							  <button class="close" data-dismiss="alert"></button>
							 Validaci&oacute;n de datos correcta!
						</div>
						<div class="alert alert-error hide">
							<button class="close" data-dismiss="alert"></button>
							 Por favor ingrese los campos obligatorios.
						</div>
				
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Nombre<span class="required">*</span></label>
									<div class="controls">
									   <input name="name" type="text" value='<?php echo $nombre;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Usuario<span class="required">*</span></label>
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
									<label class="control-label">Correo<span class="required">*</span></label>
									<div class="controls">
									   <input name="email" type="text" value='<?php echo $email;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Tel&eacute;fono<span class="required">*</span></label>
									<div class="controls">
									   <input name="telefono" type="text" value='<?php echo $telefono;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Clave<span class="required">*</span></label>
									<div class="controls">
									   <input name="clave1" type="password" value='' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>		
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Repetir clave<span class="required">*</span></label>
									<div class="controls">
									   <input name="clave2" type="password" value='' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Tipo<span class="required">*</span></label>
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
						
					<?php 
						if( $user->username == "admin" ){
					?>
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Cr&eacute;ditos ilimitados?<span class="required">*</span></label>
									<div class="controls">
										
										<label class="radio">
											<input type="radio" name="ilimitado" value="1" <?php echo ($ilimitado == 1) ? "checked" : "" ;?> />
                                            Si
                                        </label>  
										
										<label class="radio">
											<input type="radio" name="ilimitado" value="0" <?php echo ($ilimitado == 0) ? "checked" : "" ;?>/>
                                            No
                                        </label>  
                                          
                                       </div>
									</div>
								</div>
						</div>
					<?php 
						}
					?>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Seguro?<span class="required">*</span></label>
									<div class="controls">
										
										<label class="radio">
											<input type="radio" name="seguro" value="1" <?php echo ($seguro == 1) ? "checked" : "" ;?> />
                                            Si
                                        </label>  
										
										<label class="radio">
											<input type="radio" name="seguro" value="0" <?php echo ($seguro == 0) ? "checked" : "" ;?>/>
                                            No
                                        </label>  
                                          
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
					<input type='hidden' name='task' value='veUsuarioSave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
	
<?php 
	BasicPageHelper::endPage(); 

	//Selecciona el menu	
	Menu::setActive("72");
	Menu::setActive("73");
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Usuario","index.php");
	PageHelper::addFinalBreadcrumb($bol);
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
	
	//Validate form data
	Validation::initValidation("form");
	Validation::required("name", true);//Primer campo lleva true
	Validation::required("username");
	Validation::email("email");
	Validation::number("telefono");
	//Validation::required("clave1");
	//Validation::required("clave2");
	Validation::required("tipo");
	Validation::required("ilimitado");
	Validation::endValidation();
?>




