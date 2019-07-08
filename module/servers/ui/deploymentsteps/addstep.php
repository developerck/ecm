<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */

$form = $cntrlobj->form ['form'];
$data = $cntrlobj->form ['data']['tablecol'];
$serverdetail = $cntrlobj->form ['data']['serverdetail'];

$form->assign('serverdetail',$serverdetail);
// the label for the  element
$form->add ( 'label', 'label_steplabel', 'steplabel', 'Step Name:' );

// add the  element
$obj = $form->add ( 'text', 'steplabel', '', array (
		"placeholder" => "Step Label",

		"value" => $data ['steplabel'],
		"style"=>"width:165px"
) );

// set rules
$obj->set_rule ( array (

		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array (
				'error',
				'Step Label is required!'
		),
		'length' => array (
				0,
				255,
				'error',
				'Maximum 50 character!'
		),


) );
$form->add ( 'note', 'note_steplabel', 'steplabel', 'This will be shown before Input Type.' );
$selected = $data['stepinputtype']==''?'none':'text';
$form->add('label', 'label_stepinputtype', 'stepinputtype', 'Step Input Type');
$obj = $form->add('radios', 'stepinputtype',
		array(
				'text'=>'Text Box',
				'none'=>'None'



		),
		$selected
);

$obj->set_rule(array(
		'required' => array('error', 'Step Input Type is required!')
));

$form->add ( 'note', 'note_stepinputtype', 'stepinputtype', 'Select a input type that will be created at deployment .' );

$selected = $data['steprequired']?1:0;
$form->add('label', 'label_steprequired', 'steprequired', 'Step Required');
$obj = $form->add('radios', 'steprequired',
		array(
				'1'=>'Required',
				'0'=>'Optional',


		),
		$selected

);
/*
$obj->set_rule(array(
		'required' => array('error', 'Step Required is required!')
));
*/
$form->add ( 'note', 'note_steprequired', 'steprequired', 'Select Validation ' );
$form->add ( 'label', 'label_stepcomment', 'stepcomment', 'Comment:' );
$obj = $form->add ( 'textarea', 'stepcomment', '', array (
		"value" => $data ['stepcomment']
) );


$form->add ( 'hidden', 'server_id', $serverdetail ['id'] );
// case of edit
if (isset ( $data ['id'] )) {
	$form->add ( 'hidden', 'id', $data ['id'] );
}

// "submit"
$form->add ( 'submit', 'btnaddstepsubmit', 'Submit' );
$form->add ( 'reset', 'btnaddstepreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);
// generate output using a custom template

$form->render ( dirname ( __file__ ) . '/addstep_zform.php' );

?>
<script>

$('#stepform').submit(function(event) {

    /* stop form from submitting normally */
    event.preventDefault();

	var $form = $('#stepform').data('Zebra_Form');


    // validate the form, and if the form validates
    if ($form.validate()) {


    	var url = '<?php echo $CNF->wwwroot?>servers/deploymentsteps/savestep';
		var opt ={
				"url" :url,
				"query": JSON.stringify($('#stepform').serializeArray())
		}		;
			_devlibAjax.doAjax('renderSaveSteps',opt);

    }



});

//response should be either true with blank message or failed with message
function renderSaveSteps(response){
	if(typeof response ===  "object"){
		try{
			var status = false;
			var msg ='';
			if(typeof response.status !== "undefined" ){
				status = response.status;
			}
			if(typeof response.msg !== "undefined" ){
				msg = response.msg;
			}
			if(status){
				$('#addstep_modal').modal("hide");
					window.location.reload();
			}else{

			}
		}catch(err){
			console.log(err);
		}
	}
}
</script>
