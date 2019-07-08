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
$data = $cntrlobj->form ['data'];


$serverarr = $cntrlobj->form ['data']['serverarr'];


//form start

$form->add('label', 'label_server_id', 'server_id', 'For Server ');
$obj = $form->add('select', 'server_id', $data['server_id'],array("onchange"=>"getHistory(this);"));

$srvarr = array(''=>'- Select server -')+ $serverarr;


$obj->add_options($srvarr,true);


$form->assign('cntrlobj', $cntrlobj);

// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/deploymenthistory_zform.php' );


?>
<?php
if($data['server_id']){
		$format_data = $cntrlobj->table_list_data ['data'];
		$pagingStr = isset ( $cntrlobj->table_list_data ['paging'] ) ? $cntrlobj->table_list_data ['paging'] : '';
		
		?>
<div class="panel panel-info">
	<div class="panel-heading">Deployment History</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th>
							#
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
										$buttonarr [] = '<a class="ajaxmodel" style="cursor:pointer" data-target="modal" data-contentid="modal_content"  data-url= "' . $CNF->wwwroot . 'servers/deployment/deployedPreview" data-param=\'{"id":'.$record ['id'] .'}\' onclick="ajaxModal(this);"><span class="glyphicon glyphicon-info-sign"></span></a>';
										$changelogs= array();
										
										foreach($record ['changelogs_detail']  as $changelogrecord){
												
											$changelogs[] = '<span class="label label-default"><a class="ajaxmodel" style="cursor:pointer" data-target="modal" data-contentid="modal_content"  data-url= "' . $CNF->wwwroot . 'projects/changelog/getChangelogInfoById" data-param=\'{"id":'.$changelogrecord['id'] .'}\' onclick="ajaxModal(this);">' . $changelogrecord['projectname']." > ".$changelogrecord['rvname'] ." > ".$changelogrecord['issueid']. '</a></span>&nbsp;';
											
										}
										
										echo '<tr>';
										echo '<td>' . $record ['serial'] . '</td>';
										echo '<td>' . $record ['servername'] . '</td>';
										echo '<td>' . $record ['projectname'] . '</td>';
										echo '<td>' . implode(" ", $changelogs) . '</td>';
										echo '<td>' . $record ['deployedby'] . '</td>';
										echo '<td>' . pDate($record ['deploymenttime']) . '</td>';
										
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"> Detail</h4>
      </div>
      <div class="modal-body">
	  <div id="modal_content"></div>
      </div>

    </div>
  </div>
</div>
<?php } ?>

<script>
function getHistory(obj){
var url='';
	if($(obj).val() !=''){
	url =   '<?php echo $cntrlobj->baseurl."deploymenthistory"?>?id='+$(obj).val() ;
	}else{
		url =   '<?php echo $cntrlobj->baseurl."deploymenthistory"?>';
	}
	window.location.assign( url);	
}
	</script>
