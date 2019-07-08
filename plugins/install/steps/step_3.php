<?php

/**
 * setup installer
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
?>
<div style="<?php if($configstr){ echo "display:none";}?>">
<div class="info"><strong>Please make sure everything is correct before you continue
	on to the next step.</strong></div>

<div class="row">
	<label for="field_virtual_path"> Website URL </label>

	<div class="field">
		<input type="text" id="wwwroot" name="wwwroot"

			value="<?php echo isset($_POST['wwwroot'])?$_POST['wwwroot']:currentSitePath();?>" class="text xlarge" placeholder="http://example.com/ecm/" />
	<div class="info"> * You can later change it from config/config.ini</div>
	</div>

</div>
<div class="clear"></div>
<hr>
<div class="info">Specify your database settings here. Please note that
	the database for our software must be created prior to this step. If
	you have not created one yet, do so now.</div>

<div class="row">
	<label for="field_db_hostname"> Database hostname </label>
	<div class="field">
		<input type="text" id="field_db_hostname" name="db_hostname"
			value="<?php echo isset($_POST['db_hostname'])?$_POST['db_hostname']:'';?>" class="text"  placeholder="hostname" />
	</div>
</div>
<div class="row">
	<label for="field_db_hostname"> Database Port </label>
	<div class="field">
		<input type="text" id="field_db_port" name="db_port"
			value="<?php echo isset($_POST['db_port'])?$_POST['db_port']:'3306';?>" class="text"  placeholder="portno"  />
	</div>
</div>
<div class="clear"></div>
<div class="row">
	<label for="field_db_username"> Database username </label>
	<div class="field">
		<input type="text" id="field_db_username" name="db_username" value="<?php echo isset($_POST['db_username'])?$_POST['db_username']:'';?>"
			class="text"  placeholder="database username"/>
	</div>
</div>
<div class="clear"></div>
<div class="row">
	<label for="field_db_password"> Database password </label>
	<div class="field">
		<input type="text" id="field_db_password" name="db_password" value="<?php echo isset($_POST['db_password'])?$_POST['db_password']:'';?>"
			class="text"  placeholder="database password"/>
	</div>
</div>
<div class="clear"></div>
<div class="row">
	<label for="field_db_name"> Database name </label>
	<div class="field">
		<input type="text" id="field_db_name" name="db_name" value="<?php echo isset($_POST['db_name'])?$_POST['db_name']:'';?>"
			class="text"  placeholder="database name"/>
	</div>
</div>
</div>
<div class="clear"></div>
