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
//form start
$form = $cntrlobj->form ['form'];
$data = $cntrlobj->form ['data'];
$proname =$data ['projectname'];
$srvname = $data ['servername'];


$form->add ( 'label', 'label_project_id', 'project_id', 'For Project ' );

$prolabel = '<span class="label label-warning">'.$proname.'</span>';
$obj = $form->add ( 'label', 'project_id', 'project_id',$prolabel);

$form->add ( 'label', 'label_server_id', 'server_id', 'For Server ' );

$srvlabel = '<span class="label label-warning">'.$srvname.'</span>';
$obj = $form->add ( 'label', 'server_id', 'server_id',$srvlabel);


// "submit"
if (! empty ( $cntrlobj->form ['data']['selectedchangelog'] )
&& !empty($cntrlobj->form['data']['steps'])
) {
$form->add ( 'submit', 'btnsubmit', 'Deployment Done' );
}else{
	$form->add ( 'label', 'btnsubmit','btnsubmit', '<span class="label label-danger">No Changelog OR no Steps For Deployment!</span>' );
}

$form->add ( 'label', 'label_comment', 'comment', 'Comment:' );
$obj = $form->add ( 'textarea', 'comment', '', array (
		"value" => ''
) );
$form->assign('CNF', $CNF);
$form->assign('cntrlobj', $cntrlobj);
$changelogpatchlink = '<a target="_blank" class="download" style="cursor:pointer"  href="' . \devlib\AppController::generateGetLink ( '', $cntrlobj->baseurl . "changelogPatch" ) . '" alt="Export" title="Export"    ><i class="glyphicon glyphicon-export fa-fw"></i></a>';

$form->assign('changelogpatchlink', $changelogpatchlink);

// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/step3_zform.php' );



?>


<script type="text/javascript">
$(document).ready(function(){
	//getSteps('#server_id');
	$('#form').submit(function(event){

		var flag = true;
		$('#form .requiredinput').each(function(){
			if(!$(this).is(':checked')){
					flag= false;
					$(this).focus();
					$(this).css("border",'1px solid red');
					return;
				}else{
					$(this).css("border",'1px solid #ccc');
				}
			});
			if(!flag){
					alert('You should follow all step that are required!');
					return false;
				}
			if(!confirm("You can not edit this step! ")){
				return false;
				}

		});



});




</script>
<style>
#customizedsteps{
	text-align:center;
	width:100%;
	float:left;
}
#addstepbutton{
	float:right;
}

ul.steps{}
ul.steps>li{border:1px solid #2a6496;text-align:left; margin:0;padding:5px; cursor: 'move';min-height:60px;}
ul.steps label{}
ul.steps span.steplabel{float:right;}
ul.steps li div.steprow{float:left;width:90%}


</style>