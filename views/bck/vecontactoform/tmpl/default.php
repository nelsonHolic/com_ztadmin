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

$bol= (isset($data->id) ? "Editar" : "Crear Nuevo");

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage($bol." contacto", ""); 

$id = (isset($data->id) ? $data->id : "");
$nombre = (isset($data->nombre) ? $data->nombre : "");
$movil = (isset($data->movil) ? $data->movil : "");
$correo = (isset($data->correo) ? $data->correo : "");

$cedula = (isset($data->cedula) ? $data->cedula : "");
$apellido = (isset($data->apellido) ? $data->apellido : "");
$fechaNacimiento = (isset($data->fecha_nacimiento) ? $data->fecha_nacimiento : "");
$tiene_vehiculo = (isset($data->tiene_vehiculo) ? $data->tiene_vehiculo : "");
$tipo = (isset($data->tipo) ? $data->tipo : "");
$placa = (isset($data->placa) ? $data->placa : "");
$fechaSoat = (isset($data->fecha_soat) ? $data->fecha_soat : "");

//Seguro de vida
$vida = (isset($data->vida) ? $data->vida : "");
$fechaInicio = (isset($data->fecha_inicio) ? $data->fecha_inicio : "");
$fechaPago = (isset($data->fecha_pago) ? $data->fecha_pago : "");
$aseguradora = (isset($data->aseguradora) ? $data->aseguradora : "");

//Seguro autos
$autos = (isset($data->autos) ? $data->autos : "");
$fechaInicioAutos = (isset($data->fecha_inicio_autos) ? $data->fecha_inicio_autos : "");
$fechaPagoAutos = (isset($data->fecha_pago_autos) ? $data->fecha_pago_autos : "");
$aseguradoraAutos = (isset($data->aseguradora_autos) ? $data->aseguradora_autos : "");

//Seguro hogar
$hogar = (isset($data->hogar) ? $data->hogar : "");
$fechaInicioHogar = (isset($data->fecha_inicio_hogar) ? $data->fecha_inicio_hogar : "");
$fechaPagoHogar = (isset($data->fecha_pago_hogar) ? $data->fecha_pago_hogar : "");
$aseguradoraHogar = (isset($data->aseguradora_hogar) ? $data->aseguradora_hogar : "");

//Seguro salud
$salud = (isset($data->salud) ? $data->salud : "");
$fechaInicioSalud = (isset($data->fecha_inicio_salud) ? $data->fecha_inicio_salud : "");
$fechaPagoSalud = (isset($data->fecha_pago_salud) ? $data->fecha_pago_salud : "");
$aseguradoraSalud = (isset($data->aseguradora_salud) ? $data->aseguradora_salud : "");

$recordar = (isset($data->recordar) ? $data->recordar : "");

?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Información del contacto #{$this->id}"); ?>
			
				
				<form id="form" class="form-horizontal" method="post" >
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
								<div class="control-group ">
									<label class="control-label">Nombre<span class="required">*</span></label>
									<div class="controls">
									   <input name="nombre" type="text" value='<?php echo $nombre;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Movil<span class="required">*</span></label>
									<div class="controls">
									   <input name="movil"  type="text" value='<?php echo $movil;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						
												
						<div class="row-fluid">  
							<div class="span12 ">
								<div class="control-group">
									<label class="control-label">Correo</label>
									<div class="controls">
										<input name="correo" type="text" value='<?php echo $correo;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						<?php 
							if($user->seguro == 1){
						?>
						
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Apellido</label>
										<div class="controls">
											<input name="apellido" type="text" value='<?php echo $apellido;?>' class="m-wrap span12" >
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">C&eacute;dula</label>
										<div class="controls">
											<input name="cedula" type="text" value='<?php echo $cedula;?>' class="m-wrap span12" >
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Nacimiento</label>
										<div class="controls">
											<input name='fecha_nacimiento' placeholder="Fecha" type="text" value="<?php echo $fechaNacimiento;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Tiene vehículo?<span class="required">*</span></label>
										<div class="controls">
											
											<label class="radio">
												<input type="radio" name="tiene_vehiculo" value="1" <?php echo ($tiene_vehiculo == 1) ? "checked" : "" ;?> />
												Si
											</label>  
											
											<label class="radio">
												<input type="radio" name="tiene_vehiculo" value="0" <?php echo ($tiene_vehiculo == 0) ? "checked" : "" ;?>/>
												No
											</label>  
											  
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Tipo<span class="required">*</span></label>
										<div class="controls">
											
											<label class="radio">
												<input type="radio" name="tipo" value="M" <?php echo ($tipo == 'M') ? "checked" : "" ;?> />
												Moto
											</label>  
											
											<label class="radio">
												<input type="radio" name="tipo" value="C" <?php echo ($tipo == 'C') ? "checked" : "" ;?>/>
												Carro
											</label>  
											
											<label class="radio">
												<input type="radio" name="tipo" value="A" <?php echo ($tipo == 'A') ? "checked" : "" ;?>/>
												Moto y Carro
											</label>  
											  
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Placa</label>
										<div class="controls">
											<input name="placa" type="text" value='<?php echo $placa;?>' class="m-wrap span12" >
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha SOAT</label>
										<div class="controls">
											<input name='fecha_soat' placeholder="Fecha" type="text" value="<?php echo $fechaSoat;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
							
							<!-- VIDA-->
							<div class="row-fluid">
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Poliza Vida<span class="required">*</span></label>
										<div class="controls">
											
											<label class="radio">
												<input type="radio" name="vida" value="1" <?php echo ($vida == 1) ? "checked" : "" ;?> />
												Si
											</label>  
											
											<label class="radio">
												<input type="radio" name="vida" value="0" <?php echo ($vida == 0) ? "checked" : "" ;?>/>
												No
											</label>  
											  
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Inicio</label>
										<div class="controls">
											<input name='fecha_inicio' placeholder="Fecha" type="text" value="<?php echo $fechaInicio;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Pago</label>
										<div class="controls">
											<input name='fecha_pago' placeholder="Fecha" type="text" value="<?php echo $fechaPago;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
								
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Aseguradora</label>
										<div class="controls">
											<input name="aseguradora" type="text" value='<?php echo $aseguradora;?>' class="m-wrap span12" >
										</div>
									</div>
								</div>
							</div>
							
							<!-- AUTOS-->
							<div class="row-fluid">
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Poliza Auto<span class="required">*</span></label>
										<div class="controls">
											
											<label class="radio">
												<input type="radio" name="autos" value="1" <?php echo ($autos == 1) ? "checked" : "" ;?> />
												Si
											</label>  
											
											<label class="radio">
												<input type="radio" name="autos" value="0" <?php echo ($autos == 0) ? "checked" : "" ;?>/>
												No
											</label>  
											  
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Inicio</label>
										<div class="controls">
											<input name='fecha_inicio_autos' placeholder="Fecha" type="text" value="<?php echo $fechaInicioAutos;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Pago</label>
										<div class="controls">
											<input name='fecha_pago_autos' placeholder="Fecha" type="text" value="<?php echo $fechaPagoAutos;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
								
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Aseguradora</label>
										<div class="controls">
											<input name="aseguradora_autos" type="text" value='<?php echo $aseguradoraAutos;?>' class="m-wrap span12" >
										</div>
									</div>
								</div>
							</div>
							
							<!-- Hogar-->
							<div class="row-fluid">
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Poliza Hogar<span class="required">*</span></label>
										<div class="controls">
											
											<label class="radio">
												<input type="radio" name="hogar" value="1" <?php echo ($hogar == 1) ? "checked" : "" ;?> />
												Si
											</label>  
											
											<label class="radio">
												<input type="radio" name="hogar" value="0" <?php echo ($hogar == 0) ? "checked" : "" ;?>/>
												No
											</label>  
											  
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Inicio</label>
										<div class="controls">
											<input name='fecha_inicio_hogar' placeholder="Fecha" type="text" value="<?php echo $fechaInicioHogar;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Pago</label>
										<div class="controls">
											<input name='fecha_pago_hogar' placeholder="Fecha" type="text" value="<?php echo $fechaPagoHogar;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
								
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Aseguradora</label>
										<div class="controls">
											<input name="aseguradora_hogar" type="text" value='<?php echo $aseguradoraHogar;?>' class="m-wrap span12" >
										</div>
									</div>
								</div>
							</div>
							
							<!-- Salud-->
							<div class="row-fluid">
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Poliza Salud<span class="required">*</span></label>
										<div class="controls">
											
											<label class="radio">
												<input type="radio" name="salud" value="1" <?php echo ($salud == 1) ? "checked" : "" ;?> />
												Si
											</label>  
											
											<label class="radio">
												<input type="radio" name="salud" value="0" <?php echo ($salud == 0) ? "checked" : "" ;?>/>
												No
											</label>  
											  
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Inicio</label>
										<div class="controls">
											<input name='fecha_inicio_salud' placeholder="Fecha" type="text" value="<?php echo $fechaInicioSalud;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="inbox-control-group" >
										<label class="control-label">Fecha Pago</label>
										<div class="controls">
											<input name='fecha_pago_salud' placeholder="Fecha" type="text" value="<?php echo $fechaPagoSalud;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
									</div>
								</div>
							</div>
								
							<div class="row-fluid">  
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Aseguradora</label>
										<div class="controls">
											<input name="aseguradora_salud" type="text" value='<?php echo $aseguradoraSalud;?>' class="m-wrap span12" >
										</div>
									</div>
								</div>
							</div>
							
							<div class="row-fluid">
								<div class="span12 ">
									<div class="control-group">
										<label class="control-label">Desea que le recordemos de las fechas?<span class="required">*</span></label>
										<div class="controls">
											
											<label class="radio">
												<input type="radio" name="recordar" value="S" <?php echo ($recordar == 'S') ? "checked" : "" ;?> />
												Si
											</label>  
											
											<label class="radio">
												<input type="radio" name="recordar" value="N" <?php echo ($recordar == 'N') ? "checked" : "" ;?>/>
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
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit" onclick='validate'>Guardar</button>
						</div>
					</div>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='id' value='<?php echo $id;?>' />
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='veContactoSave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	if($user->tipo == 'V'){
		Menu::setActive("58");
		Menu::setActive("68");
		if(isset($data->id)){	
			Menu::setActive("70");
		}
		else{	
			Menu::setActive("69");
		}
	}
	else if($user->tipo == 'U'){
		Menu::setActive("95");
		Menu::setActive("96");
		Menu::setActive("102");
	}

	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Contactos","index.php?option=com_ztadmin&task=veContactoList");
	PageHelper::addFinalBreadcrumb($bol);
	 
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
	
	//Validate form data
	Validation::initValidation("form");
	Validation::required("nombre", true);
	//Validation::email("correo");
	Validation::number("movil");
	Validation::endValidation();
?>

<script src="templates/chronos/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>

<script type='text/javascript'>
jQuery(document).ready(function(){  

	/*jQuery( "#fecha" ).hide();
	jQuery( "#envio_posterior" ).change(function() {
		if( jQuery( "#envio_posterior" ).is(':checked')  ){
			jQuery( "#fecha" ).show();
		}
		else{
			jQuery( "#fecha" ).hide();
		}
		
	});
	
	jQuery( "#envio_todos" ).change(function() {
		if( jQuery( "#envio_todos" ).is(':checked')  ){
			jQuery( "#gruposDiv" ).hide();
			jQuery( "#contactosDiv" ).hide();
			jQuery( "#telefonosDiv" ).hide();
			
		}
		else{
			jQuery( "#gruposDiv" ).show();
			jQuery( "#contactosDiv" ).show();
			jQuery( "#telefonosDiv" ).show();
		}
		
	});*/

	if (jQuery().datepicker) {
        jQuery('.date-picker').datepicker({ dateFormat: 'yy-mm-dd' });
    }
	

});
</script>



