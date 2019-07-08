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
$data = $cntrlobj->form ['data'] ['tablecol'];
$projectarr = $cntrlobj->form ['data'] ['projectarr'];
$rvarr = $cntrlobj->form ['data'] ['rvarr'];
$labelarr = $cntrlobj->form ['data'] ['labelarr'];


$proproperty = array();

$form->add ( 'label', 'label_project_id', 'project_id', 'For Project ' );
if($data['id']){
// show label in case of edit
$prolabel = '<span class="label label-warning">'.$projectarr[$data['project_id']].'</span>';
$obj = $form->add ( 'label', 'project_id', 'project_id',$prolabel);


}else{
$obj = $form->add ( 'select', 'project_id', $data ['project_id'],  array (
		"onchange" => "getReleaseVersion(this);"
));
$projectarr = array (
        '' => '- Select Project -'
) + $projectarr;

$obj->add_options ( $projectarr, true );

$obj->set_rule ( array (
        'required' => array (
                'error',
                'Project Name is required!'
        )
) );
}


$form->add ( 'label', 'label_releaseversion_id', 'releaseversion_id', 'For Release Version ' );
$obj = $form->add ( 'select', 'releaseversion_id', $data ['releaseversion_id']);

$rvarr = array (
		'' => '- Select Release Version -'
) + $rvarr;

$obj->add_options ( $rvarr, true );

$obj->set_rule ( array (
		'required' => array (
				'error',
				'Release Name is required!'
		)
) );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
// the label for the "first name" element
$form->add ( 'label', 'label_issueid', 'issueid', 'Issue ID' );

// add the "first name" element
$obj = $form->add ( 'text', 'issueid', '', array (
		"placeholder" => "Issue ID",

		"value" => $data ['issueid']
) );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
// set rules
$obj->set_rule ( array (

		// error messages will be sent to a variable called "error", usable in custom templates

		'required' => array (
				'error',
				'Issue ID is required!'
		),

		'length' => array (
				0,
				100,
				'error',
				'Maximum 100 character!'
		)
)
 );
$form->add ( 'note', 'note_issueid', 'issueid', 'This should be unique id for a release.', array (
		"style" => "width:180px;"
) );


$form->add ( 'label', 'label_labelname', 'labelname', 'Label ' );
$obj = $form->add ( 'select', 'labelname_sel', $data ['labelname'], array("onchange"=>"updateLabelName(this);") );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
$labelarr = array (
        '' => '- Select Label-',

) + $labelarr;

$obj->add_options ( $labelarr, true );
$obj = $form->add ( 'text', 'labelname', $data ['labelname'] );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}

$form->add ( 'label', 'label_filelog', 'filelog', 'File Logs' );
$obj = $form->add ( 'textarea', 'filelog', '', array (
		"value" => $data ['filelog']
) );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
$form->add ( 'note', 'note_filelog', 'filelog', 'Every file should start with a /.', array (
		"style" => "width:180px;"
) );

$form->add ( 'label', 'label_scriptlog', 'scriptlog', 'Database Script' );
$obj = $form->add ( 'textarea', 'scriptlog', '', array (
		"value" => $data ['scriptlog']
) );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
$form->add ( 'label', 'label_settings', 'settings', 'Settings' );
$obj = $form->add ( 'textarea', 'settings', '', array (
		"value" => $data ['settings']
) );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
$form->add ( 'label', 'label_comment', 'comment', 'Developer Comment' );
$obj = $form->add ( 'textarea', 'comment', '', array (
		"value" => $data ['comment']
) );

$obj = $form->add ( 'checkbox', 'islocked', '1', array (
		"onchange" => "confirmLocked(this);"
) );
if ($data ['islocked']) {
	$checked = $data ['islocked'] ? 'checked' : false;
	if ($checked) {
		$obj->set_attributes ( array (
				"checked" => $checked
		) );
	}
} else {
	$obj->set_attributes ( array ()

	 );
}
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
$form->add ( 'label', 'label_islocked_yes', 'islocked_yes', 'Lock Changelog' );

$form->add ( 'note', 'note_islocked', 'islocked', 'You can not edit after locking. Only Locked issue will be appeared for Deployment.' );

// case of edit

if (isset ( $data ['id'] )) {
	$form->add ( 'hidden', 'id', $data ['id'] );
}
// "submit"
$form->add ( 'hidden', 'allsaved', 1 );
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign ( 'cntrlobj', $cntrlobj );
// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/add_zform.php' );

?>



<script>
$(document).ready(function(){
	// not call when submit is clicked
	// confirm before leave
	
	window.onbeforeunload = function(e) {
	
		 var tar = $( e.target );
		if(!tar.is( "#btnsubmit" ) ){
			var allsaved = 0;
			allsaved = $('#allsaved').val();
			allsaved  = parseInt(allsaved, 10);
	     	if (!allsaved) {
	          return "Save before leaving this page!";
	     	}
		}
	};
	
<?php
// call in case of edit
if($data['id']){
	echo 'displayFilelogsLabels();';
}
?>

    $('input:not(:button),textarea,select').change(function(){

        	if($('#allsaved').val() == 1){
				$('#allsaved').val("0");
        	}
        });

    $('#filelog').change(function(){
        // change not saved
	    	if($('#allsaved').val() == 1){
				$('#allsaved').val("0");
	    	}
	    	// then print filelog
	    	displayFilelogsLabels();

        }
    );
 });
function displayFilelogsLabels(){
	var paths = $('#filelog').val();
	if( paths !=''){
		$('#filelogs_label').show();
		var patharr = paths.replace( /\n/g, " " ).split( " " )
		var str = '';
		$.each(patharr, function (i, data){
			// TODO: validation for input string
			var labelclass ='label-success';
			if ( data.charAt( 0 ) != '/' ) {
				labelclass ='label-danger';

				}
				str += '<span class="label ' +labelclass +'">'+data+'</span> &nbsp;';
			});
			$('#filelogs_label').html(str);
		}
}
function confirmLocked(obj){
	if($(obj).is(":checked")){
		if(!confirm("Are you sure you want to lock this?")){
			$(obj).removeAttr("checked");

		}
	}
}
function updateLabelName(obj){

	if($(obj).val() !=''){
		$('#labelname').val($(obj).val());
	}

}
function getReleaseVersion(obj){
	if($(obj).val() !=''){
		var url = '<?php echo $CNF->wwwroot?>projects/changelog/getRVAndLabel';
		var opt ={
				"url" :url,
				"query":{
						"project_id":$(obj).val()
						}
		}		;
			_devlibAjax.doAjax('showRVAndLabel',opt);
		}else{
			var response ={};
			showRVAndLabel(response);
		}
}
function showRVAndLabel(response){
	if(!$.isEmptyObject(response)){
		if(typeof response.rv !=='undefined'){
			var rv = response.rv ;
			if(!$.isEmptyObject(rv )){
			 $.each(rv, function(i, value) {
		         $('#releaseversion_id').append($('<option>').text(value).attr('value', i));
		     });
			}
		}
		if(typeof response.label !=='undefined'){
			var label = response.label ;
			if(!$.isEmptyObject(label )){
			 $.each(label, function(i, value) {
		         $('#labelname_sel').append($('<option>').text(value).attr('value', i));
		     });
			}
		}
	}else{
		$('#releaseversion_id option[value!=""]').remove();
		$('#labelname_sel option[value!=""]').remove();
	}
}

</script>
<style>
.tooltip-inner {
	max-width: 350px;
	/* If max-width does not work, try using width instead */
	width: 350px;
}
</style>
