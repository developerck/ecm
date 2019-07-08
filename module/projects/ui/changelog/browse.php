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

		$projectarr = $cntrlobj->form ['data'] ['projectarr'];
		$rvarr = $cntrlobj->form ['data'] ['rvarr'];

		$form->add ( 'label', 'label_project_id', 'project_id', 'For Project ' );
		$obj = $form->add ( 'select', 'project_id', $searcharr ['project_id'], array (
				"onchange" => "getReleaseVersion(this);"
		) );

		$projectarr = array (
				'' => '- Select Project -'
		) + $projectarr;

		$obj->add_options ( $projectarr, true );

		$form->add ( 'label', 'label_releaseversion_id', 'releaseversion_id', 'For Release Version ' );
		$obj = $form->add ( 'select', 'releaseversion_id[]', $searcharr ['releaseversion_id'], array (
				"multiple" => "multiple"
		) );

		// $rvarr = array(''=>'- Select Release Version -')+ $rvarr;

		$obj->add_options ( $rvarr, true );

		$form->add ( 'label', 'label_issueid', 'issueid', 'Issue ID' );

		$obj = $form->add ( 'text', 'issueid', '', array (
				"placeholder" => "Issue ID",

				"value" => $searcharr ['issueid']
		) );

		$form->add ( 'label', 'label_labelname', 'labelname', 'Label' );

		$obj = $form->add ( 'text', 'labelname', '', array (
		        "placeholder" => "Label",

		        "value" => $searcharr ['labelname']
		) );
		/*
		 * $obj = $form->add ( 'checkbox', 'islocked', '1' ); if ($searcharr ['islocked'] ) { $checked = $searcharr ['islocked'] ? 'checked' : false; if ($checked) { $obj->set_attributes ( array ( "checked" => $checked ) ); } } else { $obj->set_attributes ( array ( "checked" => '' ) ); }
		 */
		$form->assign ( 'cntrlobj', $cntrlobj );
		$form->add ( 'submit', 'btnsubmit', 'Search' );
		$form->add ( 'submit', 'btnreset', 'Cancel' );

		// generate output using a custom template
		$form->render ( dirname ( __file__ ) . '/changelog_filter_zform.php' );

		?>


		<?php

		$format_data = $cntrlobj->table_list_data ['data'];

		$pagingStr = isset ( $cntrlobj->table_list_data ['paging'] ) ? $cntrlobj->table_list_data ['paging'] : '';
		$sortarr = $cntrlobj->table_list_data ['sortarr'];

		?>
<div class="panel panel-info">
	<div class="panel-heading">Changelogs</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>
									<?php
									if ($sortarr ['sort'] == 'id') {

										echo '<a href="' . \devlib\AppController::generateGetLink ( array (
												"sort" => $sortarr ['sort'],
												"dir" => $sortarr ['adir']
										), $cntrlobj->baseurl . "browse" ) . '" alt="Sorting"><i class="fa fa-sort-' . $sortarr ['dir'] . '"></i></a>';
									} else {
										echo '#';
									}
									?>
									</th>
									<?php foreach($format_data[ 'header'] as $header_name){ echo '<th>'.$header_name. '</th>'; } ?>
										<th>Action</th>
					</tr>
				</thead>
				<tbody>
								<?php

foreach ( $format_data ['data'] as $record ) {
									if (array_key_exists ( 'NORECORD', $record )) {
										echo '<tr ><td class="norecord" colspan="9">' . $record ['NORECORD'] . '</td></tr>';
									} else {
										$buttonarr = array ();
										$buttonarr [] =  '<a href="' . \devlib\AppController::generateGetLink ( array (
												"edit" => $record ['id']
										), $cntrlobj->baseurl . "edit" ) . '" alt="Edit"><span class="glyphicon glyphicon-pencil"></span></a>' ;
										$buttonarr [] = '<a class="ajaxmodel" style="cursor:pointer" data-target="modal" data-contentid="modal_content"  data-url= "' . $CNF->wwwroot . 'projects/changelog/getChangelogInfoById" data-param=\'{"id":'.$record ['id'] .'}\' onclick="ajaxModal(this);"><span class="glyphicon glyphicon-info-sign"></span></a>';
										$buttonarr [] = (! $record ['islocked'] ? '<a href="' . \devlib\AppController::generateGetLink ( array (
												"delete" => $record ['id']
										), $cntrlobj->baseurl . "delete" ) . '" alt="Delete" onclick="return function(){return confirm(\'Do you want to delete this!\')}(event);"><span class="glyphicon glyphicon-trash"></span></a>' : '');
										$buttonarr [] = (! $record ['islocked'] ? '<a href="' . \devlib\AppController::generateGetLink ( array (
												"lock" => $record ['id']
										), $cntrlobj->baseurl . "lock" ) . '" alt="Lock " onclick="return function(){return confirm(\'You would not be able to edit after locking!\')}(event);"><span class="fa fa-lock"></span></a>' : '');

										echo '<tr>';
										echo '<td>' . $record ['serial'] . '</td>';
										echo '<td>' . $record ['projectname'] . '</td>';
										echo '<td>' . $record ['rvname'] . '</td>';
										echo '<td>' . $record ['issueid'] . '</td>';
										echo '<td>' . $record ['labelname'] . '</td>';
										echo '<td class="txtcenter">' . ($record ['islocked'] ? '<span class="label label-danger">Locked</span>' : '<span class="label label-success">Open</span>') . '</td>';
										//echo '<td class="txtcenter">' . pDate ( $record ['lockedtime'] ) . '</td>';
										echo '<td class="txtcenter">' . pDate ( $record ['creationtime'] ) . '</td>';

										echo '<td  class="txtcenter">' . implode ( "  ", $buttonarr ) . '</td>';
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
        <h4 class="modal-title" id="myModalLabel">Changelog Detail</h4>
      </div>
      <div class="modal-body">
	  <div id="modal_content"></div>
      </div>

    </div>
  </div>
</div>
<link
	href="<?php echo$CNF->wwwroot.$CNF->uidir;?>/js/plugins/multiselect/css/bootstrap-multiselect.css"
	rel="stylesheet">
<script
	src="<?php echo$CNF->wwwroot.$CNF->uidir;?>/js/plugins/multiselect/js/bootstrap-multiselect.js"></script>

<script>
function getReleaseVersion(obj){
	//TODO: multiple selection
	if($(obj).val() !=''){
		var url = '<?php echo $CNF->wwwroot?>projects/changelog/getreleaseversion';
		var opt ={
				"url" :url,
				"query":{
						"project_id":$(obj).val()
						}
		}		;
			_devlibAjax.doAjax('showReleaseVersion',opt);
		}else{
			var response ={};
			showReleaseVersion(response);
		}

}

function showReleaseVersion(response){
	if(!$.isEmptyObject(response)){
	 $.each(response, function(i, value) {
         $('#releaseversion_id').append($('<option>').text(value).attr('value', i));
     });
	}else{
		$('#releaseversion_id option[value!=""]').remove();
	}
	$('#releaseversion_id').multiselect('rebuild');
}


$(document).ready(function() {

	$('#releaseversion_id').multiselect({
    	includeSelectAllOption: true,
    	includeSelectAllDivider: true,
    	enableCaseInsensitiveFiltering:true,
    	selectAllText: 'Select All',
    	enableFiltering: true,
    	checkboxName: 'multiselect_rv[]',
    	nonSelectedText: '- Select Release Version -',
        filterBehavior: 'text',
        filterPlaceholder: 'Search',
    });
});


</script>