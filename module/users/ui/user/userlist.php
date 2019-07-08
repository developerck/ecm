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
$searcharr = $data ['searcharr'];

$rolearr = $data ['rolearr'];
// the label for the "first name" element
$form->add ( 'label', 'label_searchname', 'searchname', 'By Name:' );

// add the "first name" element
$obj = $form->add ( 'text', 'searchname', '', array ("placeholder" => "Search By Name",

"value" => $searcharr ['searchname'] ) );

// set rules

// "email"
$form->add ( 'label', 'label_searchemail', 'searchemail', 'Email address:' );
$obj = $form->add ( 'text', 'searchemail', '', array ("placeholder" => "Search By Email", "value" => $searcharr ['searchemail'] ) );

$form->add ( 'label', 'label_role', 'role', 'Role ' );
$obj = $form->add ( 'select', 'role_id', $searcharr ['role_id'] );
$rolearr = array ('' => '- Select Role -' ) + $rolearr;
$obj->add_options ( $rolearr, true );

/*
 * // "remember me" $form->add('checkbox', 'isactive', '1'); $form->add('label',
 * 'label_isactive_yes', 'isactive_yes', 'Is Active', array('style' =>
 * 'font-weight:normal'));
 */

// "submit"
$form->add ( 'submit', 'btnsubmit', 'Search' );
$form->add ( 'submit', 'btnreset', 'Cancel' );

// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/userlist_filter_zform.php' );

?>



<?php
$format_data = $cntrlobj->user_list_data ['data'];

$pagingStr = isset ( $cntrlobj->user_list_data ['paging'] ) ? $cntrlobj->user_list_data ['paging'] : '';

?>
<div class="panel panel-info">
	<div class="panel-heading">Users  <?php echo $cntrlobj->getHelpText('help_userlist',$cntrlobj->module);?></div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>#</th>
									<?php foreach($format_data[ 'header'] as $header_name){ echo '<th>'.$header_name. '</th>'; } ?>
										<th>Action</th>
					</tr>
				</thead>
				<tbody>
								<?php

								foreach ( $format_data ['data'] as $record ) {
									if (array_key_exists ( 'NORECORD', $record )) {
										echo '<tr ><td class="norecord" colspan="6">' . $record ['NORECORD'] . '</td></tr>';
									} else {
$buttonarr = array();
$buttonarr[] = '<a href="' . \devlib\AppController::generateGetLink ( array ("edit" => $record ['id'] ), $cntrlobj->baseurl . "edit" ) . '" alt="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
$buttonarr[] = '<a class="ajaxmodel" style="cursor:pointer" data-target="modal" data-contentid="modal_content"  data-url= "'.$CNF->wwwroot.'users/user/getUserInfoById" data-param=\'{"id":'.$record ['id'] .'}\' onclick="ajaxModal(this);"><span class="glyphicon glyphicon-info-sign"></span></a>';


										echo '<tr>';
										echo '<td>' . $record ['serial'] . '</td>';
										echo '<td>' . $record ['name'] . '</td>';
										echo '<td>' . $record ['emailid'] . '</td>';
										echo '<td>' . $record ['rolename'] . '</td>';
										echo '<td class="txtcenter">' . ($record ['isactive'] ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">In-active</span>') . '</td>';

										echo '<td class="txtcenter">' . implode(" ", $buttonarr)  . '</td>';
										echo '</tr>';
									}
								}
								?>
							</tbody>
			</table>
		</div>
		<!-- /.table-responsive -->
	</div>
	<!-- /.panel-body -->
	<div class="panel-footer">
					<?php echo $pagingStr;?>
				</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">User Detail</h4>
      </div>
      <div class="modal-body">
	  <div id="modal_content"></div>
      </div>

    </div>
  </div>
</div>