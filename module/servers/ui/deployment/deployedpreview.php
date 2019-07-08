<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * 
 */

$data = $cntrlobj->form ['data']['steps'];
// now making a print to that
$stepgen = new 	 \module\servers\lib\StepGenerator();
$allstep =array();
foreach ($data as $step){
	if($stepdata = $stepgen->generateDeployedStep($step,true)){
		$allstep[] = $stepdata;
	}
	
}

// now we have steps then want t print

$count=1;
$index = 0;
echo '<h4> Steps Followed	 :</h4>';
echo '<ul class="steps list-group">';
foreach($allstep as $record){
	
	
	
	echo '<li class="list-group-item " id="'.$data[$index]['id'].'">'.$record.' &nbsp; </li>';
	
	$index++;
	$count++;
}

echo'</ul>';
?>

<h4> Deployment Comment :</h4>
<pre>

<?php echo nl2br($cntrlobj->form ['data']['comment']);?>
</pre>
