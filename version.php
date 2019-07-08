<?php

/**
 * This is file is used to mantain the version of this open source ECM.
 *
 * @filename	version.php
 * @project 	ecm
 * @author 		developerck <os.developerck@gmail.com>
 * @copyright 	@developerck 2014
 * @version 	<1.1.1>
 *
 */
?>
<html>
<head>


<style type="text/css">
table.gridtable {
	min-width:550px;
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
	margin:20px;
}
table.gridtable tr.odd td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>
</head>
<body>
<?php
// fetching version from version.xml
$releasearr = array();
$releasearr['cv']='';
$releasearr['cvr']='';
$releasearr['rv']='';
$releasearr['rvr']='';
$releasearr['rmsg']='';
$releasearr['rtimestamp']='';
$releasearr['rby']='';

$versionfile ='version.xml';
if (file_exists($versionfile)) {
	$parsexml = simplexml_load_file($versionfile);
	if ($parsexml) {
		$releasearr['cv'] = (int)$parsexml->product->current_version;
		$releasearr['cvr'] = $parsexml->product->current_version_readable;
		$releasearr['rv']= (int)$parsexml->product->required_version;
		$releasearr['rvr'] = $parsexml->product->required_version_readable;
		$releasearr['rmsg'] = $parsexml->product->release_msg;
		$releasearr['rtimestamp'] = (int)$parsexml->product->release_timestamp;
		$releasearr['rby'] = $parsexml->product->release_by;

	} else {
		die('Version File is not in proper xml format!');
	}
} else {
	die('Version File Does not exist!');
}


//TODO: print version and specific detail about ECM
echo "<b>";
echo "==============ECM [Changelog & Deployment Management System]============";

?>

<!-- Table goes in the document BODY -->
<table class="gridtable">

<tr class="odd">
	<td>Release Version</td><td><?php echo $releasearr['cvr'];?></td>
</tr>
<tr>
	<td>Release On</td><td><?php echo ($releasearr['rtimestamp']?date("d/m/Y h:i",$releasearr['rtimestamp']):"");?></td>
</tr>
<tr class="odd">
	<td>Release By</td><td><?php echo $releasearr['rby'];?></td>
</tr>
<tr>
	<td>Release Comment</td><td><?php echo $releasearr['rmsg'];?></td>
</tr>
</table>
<?php
echo "<br/>===================================================================";
echo "</b>";
echo "<hr/>";
echo "This Software is developed to maintain Changelog and Deployment, <br/> those are created when we fixes bug or do enhancement.";
echo '<br/>';

echo "<hr/>";

echo "developed by devckworks@2014";
?>
</body>
</html>