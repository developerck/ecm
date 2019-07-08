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

<h3>Checking System Requirements :</h3>
<div class="info">Before proceeding with the full installation, we will
	carry out some tests on your server configuration to ensure that you
	are able to install and run our software. Please ensure you read
	through the results thoroughly and do not proceed until all the
	required tests are passed.</div>
<h3>Our Main Reuirement is :-</h3>
<ul>
<li>Apache Server</li>
<li>PHP with required extension</li>
<li>Mysql</li>
</ul>

	<h2>Required PHP settings</h2>

<div class="grid">
	<div class="first odd">
		<label> PHP Version </label>
		<div class="value"><?php echo phpversion();?>
			 <?php echo  phpversion_compare()?'<span class="pass">Ok!</span>':'<span class="fail">Failed!</span>'?>
		</div>
		<div class="clear"></div>
	</div>


</div>
<h2>Required PHP modules</h2>
	<div class="grid">
	<div class="first odd">
		<label> MySQL </label>
		<div class="value">
		<?php echo  isExtLoaded('mysql')?'<span class="pass">Available!</span>':'<span class="fail">Not Available!</span>'?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="even">
		<label> ZIP </label>
		<div class="value">
		<?php echo  isExtLoaded('zlib')?'<span class="pass">Available!</span>':'<span class="fail">Not Available!</span>'?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="even">
		<label> Curl </label>
		<div class="value">
		<?php echo  isExtLoaded('curl')?'<span class="pass">Available!</span>':'<span class="fail">Not Available!</span>'?>
		</div>
		<div class="clear"></div>
	</div>

</div>

<h2>Folders and files</h2>
<div class="info">Folder and files permission</div>
<div class="grid widegrid">
	<div class="first odd">
		<label> uploaddata </label>
		<div class="value">
		<?php echo  isWritable($SETUP_VAR['uploaddata_dir'])?'<span class="pass">Writable!</span>':'<span class="fail">Not Writable!</span>'?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="even">
		<label> config </label>
		<div class="value">
		<?php echo  is_writable($SETUP_VAR['config_dir'])?'<span class="pass">Writable!</span>':'<span class="fail">Not Writable!</span>'?>
		</div>
		<div class="clear"></div>
	</div>

</div>

<h2> 		 Module Rewrite In Apache </h2>
<div class="info">ECM needs that .htaccess should work in Apache. you can browse through internet for more.</div>

<div class="grid widegrid">
	<div class="first odd">
		<label> .htaccess</label>

		<div class="value">
		Write now , you should confirm it your self if .htaccess works. you can check on internet for this.

		</div>
		<div class="clear"></div>
	</div>

</div>

<div class="clear"></div>

