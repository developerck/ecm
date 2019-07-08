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
$data = $cntrlobj->form ['data']['field'];

// the label for the "first name" element
$form->add ( 'label', 'label_svnurl', 'svnurl', 'SVN Code Url:' );

// add the "first name" element
$obj = $form->add ( 'text', 'svnurl', '', array (
		"placeholder" => "SVN URL",

		"value" => $data['svnurl']
) );

// set rules
$obj->set_rule ( array (

		// error messages will be sent to a variable called "error", usable in custom templates
		'required' => array (
				'error',
				'SVN URL is required!'
		),
		) );
$form->add ( 'note', 'note_svnurl', 'svnurl', 'ECM will try to make a patch from given svn url and based on file changelog!' );

$form->add ( 'label', 'label_username', 'username', 'SVN Username:' );
$obj = $form->add ( 'text', 'username', '', array (
		"value" => $data['username']
) );
$form->add ( 'label', 'label_password', 'password', 'SVN Password:' );
$obj = $form->add ( 'text', 'password', '', array (
        "value" => $data['password']
) );


// "submit"
$form->add ( 'hidden', 'changelog', $data['checkout'] );
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);
// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/changelogpatch_zform.php' );

?>
<?php
$patchfileerror = $cntrlobj->form['data']['patchfileerror'];
$patchpath = $cntrlobj->form['data']['patchpath'];


?>
<?php
if($patchpath !=''){
	echo '<h4>Download Patch</h4>';
	echo '<pre><a href="'.$patchpath.'" alt="Downlaod Patch">Download Patch</a></pre>';
}
?>

<?php
if(!empty($patchfileerror)){
echo '<h3 style="color:red">These files are not in patch. Please copy these files Manually.</h3>';
echo '<pre>';
foreach($patchfileerror as $issue){
	foreach($issue as $filepath){
		echo $filepath.PHP_EOL;
	}
}
echo '</pre>';
}

?>
