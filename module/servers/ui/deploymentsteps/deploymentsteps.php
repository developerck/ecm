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
	if($stepdata = $stepgen->generateStep($step,true)){
		$allstep[] = $stepdata;
	}
	
}

// now we have steps then want t print

$count=1;
$index = 0;
echo '<ul class="steps list-group">';
foreach($allstep as $record){
	$editlink = '<a href="javascript:void(0);"  alt="Edit Step"  data-target="addstep_modal" data-contentid="addstep_modal_content" onclick="editStep(this,'.$data[$index]['stepid'].');"><span class="glyphicon glyphicon-pencil"></span></a>';
	$deletelink = '<a href="javascript:void(0);"  alt="Delete Step"  data-target="addstep_modal" data-contentid="addstep_modal_content" onclick="deleteStep(this,'.$data[$index]['id'].');"><span class="glyphicon glyphicon-trash"></span></a>';
	$hiddenseq = '<input type="hidden" name="step_table_id['.$data[$index]['id'].']" id="step_table_id_'.$data[$index]['id'].'" value="'.$data[$index]['stepsequence'].'" readonly class="hiddenstepsequence"/>';
	echo '<li class="list-group-item " id="'.$data[$index]['id'].'">'.$hiddenseq.' &nbsp;'.$record.' &nbsp; '.$editlink.'&nbsp;'.$deletelink.'<span class="label label-default steplabel" ><i class="fa fa-arrows fa-ws"></i>  # Step - '.$count.' </span> &nbsp; </li>';
	$count++;
	$index++;
}

echo'</ul>';