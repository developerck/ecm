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
$data = $cntrlobj->form ['data'];
$usercol = $data ['usercol'];
$rolecol = $data ['rolecol'];
$rolearr = $data ['rolearr'];

// the label for the "first name" element
$form->add ( 'label', 'label_firstname', 'firstname', 'First name:' );

// add the "first name" element
$obj = $form->add ( 'text', 'firstname', '', array (
		"placeholder" => "First Name",
		"onkeyup" => 'autoFillDisplay(this)',
		"value" => $usercol ['firstname']
) );

// set rules
$obj->set_rule ( array (

		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array (
				'error',
				'First name is required!'
		),
		'length' => array (
				0,
				50,
				'error',
				'Maximum 50 character!'
		)
)
 );

// "last name"
$form->add ( 'label', 'label_lastname', 'lastname', 'Last name:' );
$obj = $form->add ( 'text', 'lastname', '', array (
		"placeholder" => "Last Name",
		"onkeyup" => 'autoFillDisplay(this)',
		"value" => $usercol ['lastname']
) );
$obj->set_rule ( array (
		'length' => array (
				0,
				50,
				'error',
				'Maximum 50 character!'
		)
) );

// "email"
$form->add ( 'label', 'label_emailid', 'emailid', 'Email address:' );
$obj = $form->add ( 'text', 'emailid', '', array (
		"placeholder" => "Email",
		"value" => $usercol ['emailid']
) );
$obj->set_rule ( array (
		'required' => array (
				'error',
				'Email is required!'
		),
		'email' => array (
				'error',
				'Email address seems to be invalid!'
		),
		'length' => array (
				0,
				100,
				'error',
				'Maximum 100 character!'
		)
) );

// "password"
$form->add ( 'label', 'label_password', 'password', 'Password:' );
$obj = $form->add ( 'password', 'password', '', array (
		"placeholder" => "password"
) );
if ($usercol ['id'] > 0) {
	$obj->set_rule ( array (

			'length' => array (
					6,
					10,
					'error',
					'The password must have between 6 and 10 characters'
			)
	) );
} else {
	$obj->set_rule ( array (
			'required' => array (
					'error',
					'Password is required!'
			),
			'length' => array (
					6,
					10,
					'error',
					'The password must have between 6 and 10 characters'
			)
	) );
}
$form->add ( 'note', 'note_password', 'password', 'Password must  have between 6 and 10 characters.', array (
		'style' => 'width: 180px'
) );

$form->add ( 'label', 'label_displayname', 'displayname', 'Display Name:' );
$obj = $form->add ( 'text', 'displayname', '', array (
		'maxlength' => '30',
		"value" => $usercol ['displayname']
) );
$obj->set_rule ( array (
		'length' => array (
				0,
				30,
				'error',
				'Maximum 30 Character!'
		),
		'alphanumeric' => array (
				'. ', // allow alphabet plus dash
				'error', // variable to add the error message to
				'Only alphanumeric characters, ,and space is allowed!'  // error message if value doesn't validate
				)
) );
$form->add ( 'note', 'note_displayname', 'displayname', 'By Default It will be FirstName + LastName.', array (
		'style' => 'width: 180px'
) );

// selecting role

$form->add ( 'label', 'label_role', 'role', 'Role ' );
$obj = $form->add ( 'select', 'role_id', $rolecol ['role_id'] );

$rolearr = array (
		'' => '- Select Role -'
) + $rolearr;

$obj->add_options ( $rolearr, true );

$obj->set_rule ( array (
		'required' => array (
				'error',
				'Role is required!'
		)
) );

$form->add ( 'label', 'label_signature', 'signature', 'Signature:' );
$obj = $form->add ( 'textarea', 'signature', '', array (
		"value" => $usercol ['signature']
) );
$obj->set_rule ( array (
		'length' => array (
				0,
				600,
				'error',
				'Maximum 600 Character!',
				true
		)
) );
$form->add ( 'note', 'note_signature', 'signature', 'This will be used in exported files.', array (
		'style' => 'width: 180px'
) );

$obj = $form->add ( 'checkbox', 'isactive', '1' );
if ($usercol ['id']) {
	$checked = $usercol ['isactive'] ? 'checked' : false;
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

if (isset ( $usercol ['id'] )) {
	$form->add ( 'hidden', 'id', $usercol ['id'] );
}

// "submit"
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign ( 'cntrlobj', $cntrlobj );
// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/add_zform.php' );

?>

