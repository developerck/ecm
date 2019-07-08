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
$procol = $cntrlobj->form ['data']['procol'];
$scmcol = $cntrlobj->form ['data']['scmcol'];
$scmtypearr = $cntrlobj->form ['data']['scmtypearr'];
$dbcol = $cntrlobj->form ['data']['dbcol'];
$dbtypearr = $cntrlobj->form ['data']['dbtypearr'];

// the label for the "first name" element
$form->add ( 'label', 'label_name', 'name', 'Project Name:' );

// add the "first name" element
$obj = $form->add ( 'text', 'name', '', array (
		"placeholder" => "Project Name",

		"value" => $procol ['name']
) );

// set rules
$obj->set_rule ( array (

		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array (
				'error',
				'Project Name is required!'
		),
		'length' => array (
				0,
				100,
				'error',
				'Maximum 100 character!'
		),

		'alphanumeric' => array (
				'_ ', // allow alphabet plus dash
				'error', // variable to add the error message to
				'Only alphanumeric characters,space and _ is allowed!'  // error message if value doesn't validate
				)
) );
$form->add ( 'note', 'note_name', 'projectname', 'This should be unique name. only alphanumeric, space and _ is allowed.' );

$form->add ( 'label', 'label_description', 'description', 'Description:' );
$obj = $form->add ( 'textarea', 'description', '', array (
		"value" => $procol ['description']
) );
$obj->set_rule(array('length' => array(
		0,
		600,
		'error',
'Maximum 600 Character!',true)));

// "remember me"
$obj = $form->add ( 'checkbox', 'isactive', '1' );
if ($procol ['id'] ) {
	$checked = $procol ['isactive'] ? 'checked' : false;
	if ($checked) {
		$obj->set_attributes ( array (
				"checked" => $checked
		) );
	}
} else {
	$obj->set_attributes ( array (
			"checked" => 'checked'
	) );
}
$form->add ( 'label', 'label_isactive_yes', 'isactive_yes', 'Is Active', array (
		'style' => 'font-weight:normal'
) );

//SCM Detail

$form->add('label', 'label_scmtype', 'scmtype', 'SCM Type ');
$obj = $form->add('select', 'scmtype', $scmcol['scmtype']);


$obj->add_options($scmtypearr,true);




// the label for the "first name" element
$form->add ( 'label', 'label_secmervername', 'secmervername', 'Server Name:' );

// add the "first name" element
$obj = $form->add ( 'text', 'secmervername', '', array (
		"placeholder" => "SCM Server Name",

		"value" => $scmcol ['secmervername']
) );

// set rules
$obj->set_rule ( array (


		'length' => array (
				0,
				100,
				'error',
				'Maximum 100 character!'
		),


) );



$form->add ( 'label', 'label_secmerverurl', 'secmerverurl', 'Server URL:' );

// add the "first name" element
$obj = $form->add ( 'text', 'secmerverurl', '', array (
		"placeholder" => "SCM Server URL",

		"value" => $scmcol ['secmerverurl']
) );

// set rules
$obj->set_rule ( array (


		'length' => array (
				0,
				255,
				'error',
				'Maximum 255 character!'
		),


) );

$form->add ( 'label', 'label_scmusername', 'scmusername', 'SCM UserName:' );

// add the "first name" element
$obj = $form->add ( 'text', 'scmusername', '', array (
		"placeholder" => "SCM UserName ",

		"value" => $scmcol ['scmusername']
) );


$form->add ( 'label', 'label_scmpassword', 'scmpassword', 'SCM UserName:' );

// add the "first name" element
$obj = $form->add ( 'text', 'scmpassword', '', array (
		"placeholder" => "SCM Password ",

		"value" => $scmcol ['scmpassword']
) );


// set rules
$obj->set_rule ( array (


		'length' => array (
				0,
				255,
				'error',
				'Maximum 255 character!'
		),

) );


$form->add ( 'label', 'label_scmotherdetail', 'scmotherdetail', 'Comment:' );
$obj = $form->add ( 'textarea', 'scmotherdetail', '', array (
		"value" => $scmcol ['scmotherdetail']
) );
$obj->set_rule(array('length' => array(
		0,
		600,
		'error',
		'Maximum 600 Character!',true)));


//DB Detail

$form->add('label', 'label_dbtype', 'dbtype', 'DB Type ');
$obj = $form->add('select', 'dbtype', $dbcol['dbtype']);

$obj->add_options($dbtypearr,true);


// the label for the "first name" element
$form->add ( 'label', 'label_dbservername', 'dbservername', 'Server Name:' );

// add the "first name" element
$obj = $form->add ( 'text', 'dbservername', '', array (
		"placeholder" => "DB Server Name",

		"value" => $dbcol ['dbservername']
) );

// set rules
$obj->set_rule ( array (


		'length' => array (
				0,
				100,
				'error',
				'Maximum 100 character!'
		),


) );



$form->add ( 'label', 'label_dbserverurl', 'dbserverurl', 'Server URL:' );

// add the "first name" element
$obj = $form->add ( 'text', 'dbserverurl', '', array (
		"placeholder" => "DB  Host With Port",

		"value" => $dbcol ['dbserverurl']
) );

// set rules
$obj->set_rule ( array (


		'length' => array (
				0,
				255,
				'error',
				'Maximum 255 character!'
		),


) );

$form->add ( 'label', 'label_dbusername', 'dbusername', 'DB UserName:' );

// add the "first name" element
$obj = $form->add ( 'text', 'dbusername', '', array (
		"placeholder" => "DB UserName ",

		"value" => $dbcol ['dbusername']
) );


$form->add ( 'label', 'label_dbpassword', 'dbpassword', 'DB UserName:' );

// add the "first name" element
$obj = $form->add ( 'text', 'dbpassword', '', array (
		"placeholder" => "DB Password",

		"value" => $dbcol ['dbpassword']
) );


// set rules
$obj->set_rule ( array (


		'length' => array (
				0,
				255,
				'error',
				'Maximum 255 character!'
		),

) );


$form->add ( 'label', 'label_dbotherdetail', 'dbotherdetail', 'Comment:' );
$obj = $form->add ( 'textarea', 'dbotherdetail', '', array (
		"value" => $dbcol ['dbotherdetail']
) );
$obj->set_rule(array('length' => array(
		0,
		600,
		'error',
		'Maximum 600 Character!',true)));

// case of edit
if (isset ( $procol ['id'] )) {
	$form->add ( 'hidden', 'id', $procol ['id'] );
}

// "submit"
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);
// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/addproject_zform.php' );

?>

