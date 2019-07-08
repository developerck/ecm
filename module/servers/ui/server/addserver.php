<?php
/**
 *
 *
 * @server ecm
 * @author developerck <os.developerck@gmail.com>
 * @copyright @devckworks
 * @version <1.1.1>
 * @since 2014
 */
?>

<?php
$form = $cntrlobj->form ['form'];
$srvcol = $cntrlobj->form ['data']['srvcol'];
$ftpcol = $cntrlobj->form ['data']['ftpcol'];
$ftptypearr = $cntrlobj->form ['data']['ftptypearr'];
$dbcol = $cntrlobj->form ['data']['dbcol'];
$dbtypearr = $cntrlobj->form ['data']['dbtypearr'];
$proarr = $cntrlobj->form ['data']['proarr'];

$form->add('label', 'label_project_id', 'project_id', 'For Project ');
$obj = $form->add('select', 'project_id', $srvcol['project_id']);

$proarr = array(''=>'- Select Project -')+ $proarr;


$obj->add_options($proarr,true);

$obj->set_rule(array(
		'required' => array('error', 'Project Name is required!')
));
// the label for the "first name" element
$form->add ( 'label', 'label_name', 'name', 'server Name:' );

// add the "first name" element
$obj = $form->add ( 'text', 'name', '', array (
		"placeholder" => "server Name",

		"value" => $srvcol ['name']
) );

// set rules
$obj->set_rule ( array (

		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array (
				'error',
				'server Name is required!'
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
$form->add ( 'note', 'note_name', 'servername', 'This should be unique name. only alphabet,space and _ is allowed.' );

$form->add ( 'label', 'label_description', 'description', 'Description:' );
$obj = $form->add ( 'textarea', 'description', '', array (
		"value" => $srvcol ['description']
) );
$obj->set_rule(array('length' => array(
		0,
		600,
		'error',
'Maximum 600 Character!',true)));

// "remember me"
$obj = $form->add ( 'checkbox', 'isactive', '1' );
if ($srvcol ['id'] ) {
	$checked = $srvcol ['isactive'] ? 'checked' : false;
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

//ftp Detail




$form->add('label', 'label_ftptype', 'ftptype', ' Type ');
$obj = $form->add('select', 'ftptype', $ftpcol['ftptype']);


$obj->add_options($ftptypearr,true);


// the label for the "first name" element
$form->add ( 'label', 'label_ftpservername', 'ftpservername', 'Server Name:' );

// add the "first name" element
$obj = $form->add ( 'text', 'ftpservername', '', array (
		"placeholder" => "ftp Server Name",

		"value" => $ftpcol ['ftpservername']
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



$form->add ( 'label', 'label_ftpserverurl', 'ftpserverurl', 'Server URL:' );

// add the "first name" element
$obj = $form->add ( 'text', 'ftpserverurl', '', array (
		"placeholder" => "ftp Server URL",

		"value" => $ftpcol ['ftpserverurl']
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

$form->add ( 'label', 'label_ftpusername', 'ftpusername', 'ftp UserName:' );

// add the "first name" element
$obj = $form->add ( 'text', 'ftpusername', '', array (
		"placeholder" => "ftp UserName ",

		"value" => $ftpcol ['ftpusername']
) );


$form->add ( 'label', 'label_ftppassword', 'ftppassword', 'ftp UserName:' );

// add the "first name" element
$obj = $form->add ( 'text', 'ftppassword', '', array (
		"placeholder" => "ftp Password ",

		"value" => $ftpcol ['ftppassword']
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


$form->add ( 'label', 'label_ftpotherdetail', 'ftpotherdetail', 'Comment:' );
$obj = $form->add ( 'textarea', 'ftpotherdetail', '', array (
		"value" => $ftpcol ['ftpotherdetail']
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
if (isset ( $srvcol ['id'] )) {
	$form->add ( 'hidden', 'id', $srvcol ['id'] );
}

// "submit"
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);
// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/addserver_zform.php' );

?>

