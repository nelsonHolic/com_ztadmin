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

?>


<p class="gkInfo1">
		Ingrese sus datos personales para acceder a su cuenta IpCentrex
</p>


<div>


	
<section id="gkMainbody" style="font-size: 100%;">



	<section class="login">
	
	
	
		<form method="post" action="index.php">
			<fieldset>
				<div class="login-fields">
					<label class="" for="username" id="username-lbl">Usuario</label>					
					<input type="text" size="25" class="validate-username" value="" id="username" name="username">				
				</div>
				
				<div class="login-fields">
					<label class="" for="password" id="password-lbl">Contrase&ntilde;a</label>					
					<input type="password" size="25" class="validate-password" value="" id="password" name="password">				
				</div>
						
				<div style='margin-left:100px'>
					<button class="button" type="submit">Ingresar</button>
				</div>
				<input type='hidden' name='option' value='com_zipcentrex' />
				<input type='hidden' name='task' value='userLogin' />
				<?php echo JHTML::_( 'form.token' ); ?>
				
			</fieldset>
		</form>
	</section>
</section>
					
</div>











