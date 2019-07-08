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
$form = $cntrlobj->form ['form'];
$projectid = $cntrlobj->form ['data']['selprojectid'];
$selserverid = $cntrlobj->form ['data']['selserverid'];
$projectarr = $cntrlobj->form ['data']['projectarr'];
$serverarr = $cntrlobj->form ['data']['serverarr'];

$form->add('label', 'label_project_id', 'project_id', 'For Project ');
$obj = $form->add('select', 'project_id', $projectid, array("onchange"=>"getProjectServer(this);"));

$projectarr = array(''=>'- Select Project -')+ $projectarr;


$obj->add_options($projectarr,true);

$obj->set_rule(array(
		'required' => array('error', 'Project Name is required!')
));

$form->add('label', 'label_server_id', 'server_id', 'For Server ');
$obj = $form->add('select', 'server_id', $selserverid);

$serverarr = array(''=>'- Select server -')+ $serverarr;


$obj->add_options($serverarr,true);

$obj->set_rule(array(
		'required' => array('error', 'Server Name is required!')
));


// "submit"
$form->add ( 'submit', 'btnsubmit', 'Next' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);
// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/step1_zform.php' );

?>

<script type="text/javascript">
function getProjectServer(obj){
	if($(obj).val() !=''){
		var url = '<?php echo $CNF->wwwroot?>servers/deployment/getProjectServer';
		var opt ={
				"url" :url,
				"query":{
						"project_id":$(obj).val()
						}
		}		;
			_devlibAjax.doAjax('showServer',opt);
		}else{
			var response ={};
			showServer(response);
		}
}
function showServer(response){
	if(!$.isEmptyObject(response)){
		if(typeof response !=='undefined'){
			
			
			 $.each(response, function(i, value) {
		         $('#server_id').append($('<option>').text(value).attr('value', i));
		     });
			
		}
		
	}else{
		$('#server_id option[value!=""]').remove();
		
	}
}
</script>

