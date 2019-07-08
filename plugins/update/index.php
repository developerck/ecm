<?php

/**
 * update installer
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
?>
<?php

ini_set('display_errors','');
global $UPDATE_VAR;
$UPDATE_VAR = array();
$root = dirname ( dirname ( dirname ( __file__ ) ) ) . DIRECTORY_SEPARATOR ;
$UPDATE_VAR['lib_base_dir'] = $root.'lib' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'base.php';
$UPDATE_VAR['uploaddata_dir']  =  $root. "uploaddata" . DIRECTORY_SEPARATOR;
$UPDATE_VAR['uploaddata_updates_versions_dir']  =  $root. "uploaddata" . DIRECTORY_SEPARATOR.'updates'. DIRECTORY_SEPARATOR.'versions'. DIRECTORY_SEPARATOR;
$UPDATE_VAR['config_dir'] = $root. 'config' . DIRECTORY_SEPARATOR;
$UPDATE_VAR['config_config.ini_file'] = $UPDATE_VAR['config_dir']. 'config.ini';
$UPDATE_VAR['plugins_update_dir'] 	= $root.'plugins'.DIRECTORY_SEPARATOR.'update'.DIRECTORY_SEPARATOR;


$f3 = require_once ($UPDATE_VAR['lib_base_dir']);
$f3->config ( $UPDATE_VAR['config_config.ini_file']);
$f3->reroute ( $f3->get ( 'wwwroot' ) );
die();
require_once('requirement.php');
?>
<style>
html,body {
	background: #ccc;
	color: #000000;
	font: 12px arial, sans-serif;
}

a {
	color: #004FD2;
	outline: none;
	text-decoration: none;
}

a:hover {
	text-decoration: underline;
}

div#container {
	background: #f3f3f3;
	border: 1px solid #a7a7a7;
	margin: 15px auto 0 auto;
	width: 900px;
}

div#page {
	background: #ffffff;
	margin: 10px;
	padding: 15px 20px 10px 20px;
}

div#header {
	
	border-bottom: 1px solid #7db0e3;
	height: 48px;
	padding: 0 10px 10px 50px;
}

div#header h1 {
	color: #2a6496;
	font-size: 16px;
	padding-top: 6px;
}

div#header p {
	color: #222222;
	font-size: 11px;
}

div#sidebar {
	float: left;
	padding: 20px 0 0 0;
	width: 200px;
}

div#sidebar div.progress {
	padding-right: 20px;;
}

div#sidebar div.progress ul {
	border-right: 1px solid #7db0e3;
}

div#sidebar div.progress ul li {
	line-height: 24px;
	padding-left: 20px;
}

div#sidebar div.progress ul li.current {
	background: #84b6e0 no-repeat 0 4px;
}

div#content {
	background: #ffffff;
	float: left;
	padding: 20px 0 10px 0;
	width: 100%;
}

div#content div.progress {
	font-weight: bold;
	padding-bottom: 10px;
}

div#content div.error {
	color: #D70000;
	line-height: 16px;
	margin-bottom: 10px;
	padding-left: 20px;
}

div#content div.info {
	line-height: 18px;
	padding-bottom: 10px;
}

div#content div.sections {
	padding-bottom: 10px;
}

div#content div.sections h2 {
	font-weight: bold;
	padding-bottom: 10px;
}

div#footer {
	color: #2060a0;
	margin: 0 auto;
	padding: 10px 0;
	text-align: center;
	width: 800px;
}

div#footer a {
	color: #2060a0;
}

div.grid {
	border-bottom: 1px solid #eeeeee;
	clear: both;
	margin-bottom: 10px;
}

div.grid div.even {
	border-top: 1px solid #eeeeee;
}

div.grid div.odd {
	background: #fffff4;
	border-top: 1px solid #eeeeee;
}

div.grid div.first {

}

div.grid label {
	display: block;
	float: left;
	line-height: 24px;
	width: 160px;
}

div.widegrid label {
	width: 160px;
}

div.grid div.value {
	line-height: 24px;
	margin-left: 160px;
}

div.widegrid div.value {
	margin-left: 160px;
}

div.grid div.value span.pass {
	color: #008000;
}

div.grid div.value span.fail {
	color: #C80000;
}

div.row {
	clear: both;
	padding-bottom: 6px;
}

div.row label {
	display: block;
	float: left;
	line-height: 24px;
	width: 160px;
}

div.row label.error {
	color: #c80000;
}

div.row div.field {
	margin-left: 160px;
}

div.row div.field ul.items {

}

div.row div.field ul.items li {

}

div.row div.field ul.items li label {
	float: none;
	line-height: 18px;
	width: auto;
}


div.clear {
	clear: both;
}
</style>
<?php


?>
<div id="container">
	<div id="page">
		<div id="header">
			<h1>ECM</h1>
			<h3>Update Process</h3>
		</div>
		
		<div id="content">


			<form action="" method="post" name="wizard" enctype="multipart/form-data">
			
					<fieldset><legend>Upload File </legend>
				
					<?php if ( isset($error )): ?>
					<div class="error"><?php echo $error; ?></div>
					<?php endif; ?>
			<p>Upload File : <input type="file" name="importfile"/><span style="color:red"> * Max Upload Size: <?php echo getMaxUploadSize();?> MB</span></p>
          
				
					  <p><input type="submit" name="submit" Value= "Upload And Check"/></p>
            </fieldset>
				



			</form>

		</div>
		<div class="clear"></div>
	</div>
</div>
<div id="footer">copyright : devlib@2014</div>
