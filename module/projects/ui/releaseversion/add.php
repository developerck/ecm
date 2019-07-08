
<?php
$form = $cntrlobj->form ['form'];
$data = $cntrlobj->form ['data']['tablecol'];
$projectarr = $cntrlobj->form ['data']['projectarr'];

$form->add('label', 'label_project_id', 'project_id', 'For Project ');
$obj = $form->add('select', 'project_id', $data['project_id']);

$projectarr = array(''=>'- Select Project -')+ $projectarr;


$obj->add_options($projectarr,true);

$obj->set_rule(array(
		'required' => array('error', 'Project Name is required!')
));
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}

// the label for the  element
$form->add ( 'label', 'label_rvname', 'rvname', 'Release Version :' );

// add the  element
$obj = $form->add ( 'text', 'rvname', '', array (
		"placeholder" => "Release Version",

		"value" => $data ['rvname'],
		"style"=>"width:165px"
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
				'Release Version is required!'
		),
		'length' => array (
				0,
				50,
				'error',
				'Maximum 50 character!'
		),


) );
$form->add ( 'note', 'note_rvname', 'rvname', 'Release Version With RC Name should be unique name for a particular project.' );
// the label for the  element
$form->add ( 'label', 'label_rcname', 'rcname', 'RC:' );

// add the  element
$obj = $form->add ( 'text', 'rcname', '', array (
		"placeholder" => "RC",

		"value" => $data ['rcname'],
		"style"=>"width:50px"
) );
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
// set rules
$obj->set_rule ( array (


		'length' => array (
				0,
				50,
				'error',
				'Maximum 50 character!'
		),


) );

$form->add ( 'label', 'label_description', 'description', 'Description:' );
$obj = $form->add ( 'textarea', 'description', '', array (
		"value" => $data ['description']
) );
$obj->set_rule(array('length' => array(
		0,
		600,
		'error',
'Maximum 600 Character!',true)));

$obj = $form->add ( 'checkbox', 'islocked', '1', array("onchange"=>"confirmLocked(this);") );

if ($data ['islocked'] ) {
	$checked = $data ['islocked'] ? 'checked' : false;
	if ($checked) {
		$obj->set_attributes ( array (
				"checked" => $checked
		) );
	}
}
if($data['islocked']){
	$obj->set_attributes ( array (
			"disabled" => "disabled"
	) );
}
$form->add ( 'label', 'label_islocked_yes', 'islocked_yes', 'Lock Changelog');

$form->add ( 'note', 'note_islocked', 'islocked', 'You can not edit release version and changelogs after locking. Only locked issue will be appeared for  Deployment.' );


// case of edit
if (isset ( $data ['id'] )) {
	$form->add ( 'hidden', 'id', $data ['id'] );
}

// "submit"
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);
// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/add_zform.php' );

?>
<script>
function confirmLocked(obj){
	if($(obj).is(":checked")){
		if(!confirm("Are you sure you want to lock this?")){
			$(obj).removeAttr("checked");

		}
	}
}
</script>
