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

$titleMsg = (isset($data->id) ? "Editar " : "Crear  ");

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( GuiHelper::msgVendedor(), $menu);
PageHelper::initPage( $titleMsg . "Poliza", ""); 

$id         	  = (isset($data->id) ? $data->id : "");
$cliente 		  = (isset($data->cliente) ? $data->cliente : "");
$aseguradora	  = (isset($data->aseguradora) ? $data->aseguradora : "");
$ramo	          = (isset($data->ramo) ? $data->ramo : "");
$vendedor	      = (isset($data->vendedor) ? $data->vendedor : "");	
$tomador	      = (isset($data->tomador) ? $data->tomador : "");	
$asegurado	      = (isset($data->asegurado) ? $data->asegurado : "");	
$beneficiario	  = (isset($data->beneficiario) ? $data->beneficiario : "");	
$numero	          = (isset($data->numero) ? $data->numero : "");	
$tipo             = (isset($data->tipo) ? $data->tipo : "");	
$tipo_poliza      = (isset($data->tipo_poliza) ? $data->tipo_poliza : "");	
$lugar_expedicion = (isset($data->lugar_expedicion) ? $data->lugar_expedicion : "");	
$fecha_expedicion = (isset($data->fecha_expedicion) ? $data->fecha_expedicion : "");	
$fecha_inicio     = (isset($data->fecha_inicio) ? $data->fecha_inicio : "");	
$fecha_fin        = (isset($data->fecha_fin) ? $data->fecha_fin : "");	
$prima            = (isset($data->prima) ? $data->prima : "");	
$gastos_exp       = (isset($data->gastos_exp) ? $data->gastos_exp : "");	
$iva              = (isset($data->iva) ? $data->iva : "");	
$total            = (isset($data->total) ? $data->total : "");	
$fecha_limite_pago= (isset($data->fecha_limite_pago) ? $data->fecha_limite_pago : "");	
$accesorios       = (isset($data->accesorios) ? $data->accesorios : "");	
$observaciones    = (isset($data->observaciones) ? $data->observaciones : "");	


?>
	<div class="row-fluid">
		<div class="span12">
			<?php PortletHelper::show("Formulario de Pólizas"); ?>
			
				
				<form id="form" class="form-horizontal" method="post" >
				
				    <!-- Datos actuales -->
				
					<fieldset>
						 <legend>Datos del cliente</legend>
						<div class="alert alert-success hide">
                              <button class="close" data-dismiss="alert"></button>
                             Validaci&oacute;n de datos correcta!
                        </div>
						<div class="alert alert-error hide">
							<button class="close" data-dismiss="alert"></button>
                             Por favor ingrese los campos obligatorios.
                        </div>
					
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Cliente<span class="required">*</span></label>
									<div class="controls">
									   <select name='cliente'>
										<option value="">Seleccione un cliente</option>
										<?php foreach($this->clientes as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $cliente ? "selected" : ""); ?>>
												<?php echo $item->primer_nombre   . " " . $item->segundo_nombre . " " .
												           $item->primer_apellido . " " . $item->segundo_apellido ;?>
											</option>
										<?php } ?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Aseguradora<span class="required">*</span></label>
									<div class="controls">
									   <select name='aseguradora'>
										<option value="">Seleccione una aseguradora</option>
										<?php foreach($this->aseguradoras as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $aseguradora ? "selected" : ""); ?>>
												<?php echo $item->descripcion;?>
											</option>
										<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Ramo<span class="required">*</span></label>
									<div class="controls">
									   <select name='ramo'>
										<option value="">Seleccione un ramo</option>
										<?php foreach($this->ramos as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $ramo ? "selected" : ""); ?>>
												<?php echo $item->descripcion ;?>
											</option>
										<?php } ?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Vendedor<span class="required">*</span></label>
									<div class="controls">
									   <select name='vendedor'>
										<option value="">Seleccione un vendedor</option>
										<?php foreach($this->vendedores as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $vendedor ? "selected" : ""); ?>>
												<?php echo $item->nombre . " " . $item->apellido ;?>
											</option>
										<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>

						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Tomador<span class="required">*</span></label>
									<div class="controls">
									  <select name='tomador'>
										<option value="">Seleccione un tomador</option>
										<?php foreach($this->clientes as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $tomador ? "selected" : ""); ?>>
												<?php echo $item->primer_nombre   . " " . $item->segundo_nombre . " " .
												           $item->primer_apellido . " " . $item->segundo_apellido ;?>
											</option>
										<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Asegurado<span class="required">*</span></label>
									<div class="controls">
									   <select name='asegurado'>
										<option value="">Seleccione un asegurado</option>
										<?php foreach($this->clientes as $item){?>
											<option value="<?php echo $item->id ?>" <?php echo ($item->id == $asegurado ? "selected" : ""); ?>>
												<?php echo $item->primer_nombre   . " " . $item->segundo_nombre . " " .
												           $item->primer_apellido . " " . $item->segundo_apellido ;?>
											</option>
										<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Beneficiario<span class="required">*</span></label>
									<div class="controls">
										<select name='beneficiario'>
											<option value="">Seleccione un beneficiario</option>
											<?php foreach($this->clientes as $item){?>
												<option value="<?php echo $item->id ?>" <?php echo ($item->id == $beneficiario ? "selected" : ""); ?>>
													<?php echo $item->primer_nombre   . " " . $item->segundo_nombre . " " .
															   $item->primer_apellido . " " . $item->segundo_apellido ;?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">N&uacute;mero<span class="required">*</span></label>
									<div class="controls">
									   <input name="numero" type="text" value='<?php echo $numero;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
			
						
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Tipo<span class="required">*</span></label>
									<div class="controls">
									   <select name='tipo'>
											<option value="">Seleccione un tipo</option>
												<option value="C" <?php echo ($tipo == 'I' ? "selected" : ""); ?>>Individual</option>
												<option value="C" <?php echo ($tipo == 'C' ? "selected" : ""); ?>>Colectivo</option>
										</select>
									</div>
								</div>
							</div>
							<div class="span6">
								<div class="control-group ">
									<label class="control-label">Tipo P&oacute;liza<span class="required">*</span></label>
									<div class="controls">
										<select name='tipo_poliza'>
											<option value="">Seleccione un tipo de poliza</option>
												<option value="A" <?php echo ($tipo_poliza == 'A' ? "selected" : ""); ?>>Auto</option>
												<option value="H" <?php echo ($tipo_poliza == 'H' ? "selected" : ""); ?>>Hogar</option>
												<option value="V" <?php echo ($tipo_poliza == 'V' ? "selected" : ""); ?>>Vida</option>
										</select>
									</div>
								</div>
							</div>
						</div>
						
							
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Lugar Expedici&oacute;n<span class="required">*</span></label>
									<div class="controls">
									   <input name="lugar_expedicion" type="text" value='<?php echo $lugar_expedicion;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Fecha Expedici&oacute;n<span class="required">*</span></label>
									<div class="controls">
											<input name='fecha_expedicion' placeholder="Fecha" type="text" value="<?php echo $fecha_expedicion;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
									</div>
								</div>
							</div>
						</div>
						
						
						
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Fecha Inicio<span class="required">*</span></label>
									<div class="controls">
											<input name='fecha_inicio' placeholder="Fecha" type="text" value="<?php echo $fecha_inicio;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
										</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Fecha Fin<span class="required">*</span></label>
									<div class="controls">
											<input name='fecha_fin' placeholder="Fecha" type="text" value="<?php echo $fecha_fin;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
									</div>
								</div>
							</div>
						</div>
						
						<div class="row-fluid">
							
						</div>	
					</fieldset>	
					
					<fieldset>
					 <legend>Valores de la poliza</legend>
					 
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Prima<span class="required">*</span></label>
									<div class="controls">
									   <input name="prima" type="text" value='<?php echo $prima;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Gastos Exp. P&oacute;liza<span class="required">*</span></label>
									<div class="controls">
									   <input name="gastos_exp" type="text" value='<?php echo $gastos_exp;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Iva<span class="required">*</span></label>
									<div class="controls">
									   <input name="iva" type="text" value='<?php echo $iva;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Total<span class="required">*</span></label>
									<div class="controls">
									   <input name="total" type="text" value='<?php echo $total;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
						
											
						<div class="row-fluid">
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Fecha Límite de Pago<span class="required">*</span></label>
									<div class="controls">
											<input name='fecha_limite_pago' placeholder="Fecha" type="text" value="<?php echo $fecha_limite_pago;?>"  size="16" 
											class="m-wrap date-picker" style='border:1px solid green' />
									</div>
								</div>
							</div>
							
							<div class="span6 ">
								<div class="control-group ">
									<label class="control-label">Accesorios<span class="required">*</span></label>
									<div class="controls">
									   <input name="accesorios" type="text" value='<?php echo $accesorios;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
					
						<div class="row-fluid">
							<div class="span12 ">
								<div class="control-group ">
									<label class="control-label">Observaciones<span class="required">*</span></label>
									<div class="controls">
									   <input name="observaciones" type="text" value='<?php echo $observaciones;?>' class="m-wrap span12" >
									</div>
								</div>
							</div>
						</div>
						
					<div class="row-fluid">  
						<div class="span12 " style='text-align:right'>
							<button class="btn green" type="submit" onclick='validate'>Guardar</button>
						</div>
					</div>
					
					</fieldset>
					
					<?php echo JHTML::_( 'form.token' ); ?>
					<input type='hidden' name='id' value='<?php echo $id;?>' />
					<input type='hidden' name='option' value='com_ztadmin' />
					<input type='hidden' name='task' value='usPolizaSave' />
				</form>
								
							
					<!--END TABS-->
			<?php PortletHelper::end(); ?>
		</div>
	</div>
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	Menu::setActive("85");
	Menu::setActive("122");

	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Poliza","index.php?option=com_ztadmin&task=usPolizaList");
	PageHelper::addFinalBreadcrumb($titleMsg);
	 
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
	
	//Validate form data
	Validation::initValidation("form");
	Validation::required("nombre", true);
	//Validation::email("correo");
	Validation::number("prima");
	Validation::number("gastos_exp");
	Validation::number("iva");
	Validation::number("total");
	//Validation::Fecha("fecha_expedicion");

	Validation::endValidation();
?>

<script src="templates/chronos/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>

<script type='text/javascript'>
jQuery(document).ready(function(){  

	if (jQuery().datepicker) {
        jQuery('.date-picker').datepicker({ dateFormat: 'yy-mm-dd' });
    }
	

});
</script>



