<?php
/**
 *
 *
 * @project ecm
 * @author developerck <os.developerck@gmail.com>
 * @copyright @devckworks
 * @version <1.1.1>
 * @since 2014
 */
  ?>

<?php
$step = $cntrlobj->form['step'];

?>
<style>
.deploystep{
	text-align: center;
	color: #333;
}
.active{
	color:#F0AD4E;
	font-weight:bold;
	font-size:16px;
}
.glyphicon-chevron-right {
	color:#2a6496;
}
</style>
<div class="well well-sm deploystep">
<?php 
if($step > 1){
	
	echo '<a href="'.\devlib\AppController::generateGetLink(array("step"=>1),$cntrlobj->baseurl."deploy").'" alt="Edit"><span >Select Project </span></a>';
	
}else{
?>
<span <?php echo $step==1?'class="active"':''?>>Select Project </span>
<?php 
}
?>

<?php echo $cntrlobj->getHelpText('help_deploymentstep1',$cntrlobj->module);?>

<span class="glyphicon glyphicon-chevron-right "></span>

<?php 
if($step > 2){
	
	echo '<a href="'.\devlib\AppController::generateGetLink(array("step"=>2),$cntrlobj->baseurl."deploy").'" alt="Edit"><span >Select Issues </span></a>';
	
}else{
?>
<span <?php echo $step==2?'class="active"':''?>>Select Issues </span>
<?php 
}
?>

<?php echo $cntrlobj->getHelpText('help_deploymentstep2',$cntrlobj->module);?>
<span class="glyphicon glyphicon-chevron-right "></span>
<span <?php echo $step==3?'class="active"':''?>> Follow Deployment Steps</span>
<?php echo $cntrlobj->getHelpText('help_deploymentstep3',$cntrlobj->module);?>
</div>