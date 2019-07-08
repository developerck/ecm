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

$projectarr = $cntrlobj->form ['data']['projectarr'];

$form->add('label', 'label_project_id', 'project_id', 'For Project ');
$obj = $form->add('select', 'project_id', $searcharr['project_id'], array("onchange"=>"getReleaseVersion(this);"));

$projectarr = array(''=>'- Select Project -')+ $projectarr;


$obj->add_options($projectarr,true);







$form->add ( 'label', 'label_rvname', 'rvname', 'Release Version' );


$obj = $form->add ( 'text', 'rvname', '', array (
        "placeholder" => "Release Version ",

        "value" => $searcharr ['rvname']
) );

/*
 $obj = $form->add ( 'checkbox', 'islocked', '1' );
if ($searcharr ['islocked'] ) {
	$checked = $searcharr ['islocked'] ? 'checked' : false;
	if ($checked) {
		$obj->set_attributes ( array (
				"checked" => $checked
		) );
	}
} else {
	$obj->set_attributes ( array (
			"checked" => ''
	) );
}

 */
$form->assign('cntrlobj', $cntrlobj);
$form->add ( 'submit', 'btnsubmit', 'Search' );
$form->add ( 'submit', 'btnreset', 'Cancel' );

// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/rv_filter_zform.php' );

?>

		<?php

         $format_data=$cntrlobj ->table_list_data['data'];

         $pagingStr= isset($cntrlobj ->table_list_data['paging'])?$cntrlobj ->table_list_data['paging']:'';

          ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Release Version<?php echo $cntrlobj->getHelpText('help_rvlist',$cntrlobj->module);?>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>
										#
									</th>
									<th>Project Name</th>
									<th>Release</th>
									<th>Is Locked</th>
									<th>Locked On</th>
									<th>Created On</th>
										<th>
											Action
										</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($format_data[ 'data'] as $record){
								    if(array_key_exists( 'NORECORD',$record)){ echo '<tr ><td class="norecord" colspan="7">'.$record[ 'NORECORD']. '</td></tr>';
                                    }else{
                                        $buttonarr = array();
                                        $buttonarr[] = '<a href="'.\devlib\AppController::generateGetLink(array("edit"=>$record['id']),$cntrlobj->baseurl."edit").'" alt="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                                        // TODO: Make this page
                                        //$buttonarr[] = '<a class="ajaxmodel" style="cursor:pointer" data-target="modal" data-contentid="modal_content"   data-url= "'.$CNF->wwwroot.'projects/releaseversion/getRVInfo" data-param=\'{"id":'.$record ['id'] .'}\' onclick="ajaxModal(this);"><span class="glyphicon glyphicon-info-sign"></span></a>';
                                        $buttonarr[] = (!$record['islocked']?'<a href="'.\devlib\AppController::generateGetLink(array("delete"=>$record['id']),$cntrlobj->baseurl."delete").'" alt="Delete" onclick="return function(){return confirm(\'Do you want to delete this!\')}(event);"><span class="glyphicon glyphicon-trash"></span></a>':'');
                                        echo '<tr>';
                                        echo '<td>'.$record['serial']. '</td>';
                                        echo '<td>'.$record['project_name']. '</td>';
                                        echo '<td>'.$record['rvname']." ".$record['rcname']. '</td>';
                                        echo '<td class="txtcenter">'.($record['islocked']?'<span class="label label-default">Locked</span>':'<span class="label label-success">Not Locked</span>'). '</td>';
                                        echo '<td class="txtcenter">'.pDate($record['lockedtime']). '</td>';
                                        echo '<td class="txtcenter">'.pDate($record['creationtime']). '</td>';
                                        echo '<td  class="txtcenter">'.
                                        implode("  ",$buttonarr)
                                        .'</td>';
                                        echo '</tr>';
                                         }
                                } ?>
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
        <h4 class="modal-title" id="myModalLabel">Project Detail</h4>
      </div>
      <div class="modal-body">
	  <div id="modal_content"></div>
      </div>

    </div>
  </div>
</div>