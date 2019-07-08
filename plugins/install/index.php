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
<?php
ini_set('display_errors','');
global $SETUP_VAR;
$SETUP_VAR = array();
$root = dirname ( dirname ( dirname ( __file__ ) ) ) . DIRECTORY_SEPARATOR ;
$SETUP_VAR['lib_base_dir'] = $root.'lib' . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR . 'base.php';
$SETUP_VAR['uploaddata_dir']  =  $root. "uploaddata" . DIRECTORY_SEPARATOR;
$SETUP_VAR['config_dir'] = $root. 'config' . DIRECTORY_SEPARATOR;
$SETUP_VAR['config_config.ini_file'] = $SETUP_VAR['config_dir']. 'config.ini';
$SETUP_VAR['plugins_setup_basescript_dir'] 	= $root.'plugins'.DIRECTORY_SEPARATOR.'install'.DIRECTORY_SEPARATOR. 'basescript' . DIRECTORY_SEPARATOR;
//TODO: maintian all steps dynamically and all required settings in xml, now static
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
	width: 640px;
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
pre.scripts{
background: #ccc;
border: 1px solid #333;
	padding:10px;

}


div.clear {
	clear: both;
}
</style>
<?php
require_once ('requirement.php');
// step1
if (isset ( $_POST ['step1'] )) {
	if (isset ( $_POST ['agree'] )) {

		$step = 2;
	} else {
		$error = 'You should agree first!';
	}
}
// step2
if (isset ( $_POST ['step2'] )) {
	if (checkRequirement ()) {

		$step = 3;
	} else {
		$step = 2;
		$error = 'System Requirement does not match!';
	}
}

// step3 ..DB
if (isset ( $_POST ['step3'] )) {
	$error = checkDB ( $_POST );
	$configstr = false;
	if(is_array($error)){
// handlig if file could'nt write
		$configstr= true;
		$error = $error['msg'];
	}

	if ($error=='') {

		$step = 4;
	} else {
		$step = 3;

	}
}

if (isset ( $_POST ['step4'] )) {
	$error = loadMysqlScript();
	if ($error=='') {

		$step = 5;
	} else {
		$step = 4;

	}
}

if (isset ( $_POST ['step5'] )) {
	$error = createAdmin($_POST);
	if ($error=='') {

		$step = 5;
	} else {
		$step = 5;

	}
}
?>

<?php

$step = isset ( $step ) && $step > 0 ? $step : 1;
if($step == 1){
//check if this already setup

if (file_exists ( $SETUP_VAR['config_config.ini_file'] )) {
	$f3 = require_once ($SETUP_VAR['lib_base_dir']);
	$f3->config ( $SETUP_VAR['config_config.ini_file']);
	$f3->reroute ( $f3->get ( 'wwwroot' ) );
}

}
?>
<div id="container">
	<div id="page">
		<div id="header">
			<h1>ECM</h1>
			<p>Changelog & Deployment Checklist Solution</p>
		</div>
		<div id="sidebar">
			<div class="progress">
				<ul>
					<li class="<?php echo $step ==1?"current":''?>">Step 1</li>
					<li class="<?php echo $step ==2?"current":''?>">Step 2</li>
					<li class="<?php echo $step ==3?"current":''?>">Step 3</li>
					<li class="<?php echo $step ==4?"current":''?>">Step 4</li>
					<li class="<?php echo $step ==5?"current":''?>">Step 5</li>

				</ul>
			</div>
		</div>
		<div id="content">


			<form action="" method="post" name="wizard">
				<div class="sections">
					<h2>Step <?php echo $step;?> </h2>
					<hr />
					<?php if ( isset($error )): ?>
					<div class="error"><?php echo $error; ?></div>
					<?php endif; ?>
				<?php require_once dirname(__file__).'/steps/step_'.$step .'.php'?>


				<hr />
					<p>
						<input type="submit" name="step<?php echo $step;?>" value="Next" />
					</p>

				</div>



			</form>

		</div>
		<div class="clear"></div>
	</div>
</div>
<div id="footer">copyright : devckworks@2014</div>
