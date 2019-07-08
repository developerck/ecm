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
		$selctedarr = $f3->get ( 'SESSION.search.changelog.exportsel' ) ? $f3->get ( 'SESSION.search.changelog.exportsel' ) : array ();
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
<div class="panel panel-warning">
	<div class="panel-heading">Selected Changelog</div>
	<!-- /.panel-heading -->
	<div class="panel-body" id="selected_list">
<?php
// showing selected changelog
if (! empty ( $selctedarr )) {

	foreach ( $selctedarr as $val ) {
		echo '<span class="label label-default">' . $val . '</span>&nbsp;';
	}
} else {
	echo '<span class="label label-warning fa-ws">No Changelog Selected For Export.</span> ';
}

?>
</div>
</div>
<?php

$format_data = $cntrlobj->table_list_data ['data'];

$pagingStr = isset ( $cntrlobj->table_list_data ['paging'] ) ? $cntrlobj->table_list_data ['paging'] : '';
$sortarr = $cntrlobj->table_list_data ['sortarr'];

?>
<div class="panel panel-info">
	<div class="panel-heading" style="height:50px;">
	Changelogs
	<div class="dropdown" style="float:right">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
    Export
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo  \devlib\AppController::generateGetLink ( array (), $cntrlobj->baseurl . "exportCombined" )?>"  alt="Export" title="Export" >Combined File</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo  \devlib\AppController::generateGetLink ( array (), $cntrlobj->baseurl . "exportByLogType" )?>"  alt="Export" title="Export">Combined By Log Type</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo  \devlib\AppController::generateGetLink ( array (), $cntrlobj->baseurl . "exportSeperate" )?>"  alt="Export" title="Export"">Separate Per Issue</a></li>
  </ul>
</div>
	</div>
	<!-- /.panel-heading -->
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th><input type="checkbox" id="parentchk" name="toggleselect"
							value="1" onchange="toggleExportCheckbox(this)" /></th>
						<th>#</th>
									<?php foreach($format_data[ 'header'] as $header_name){ echo '<th>'.$header_name. '</th>'; } ?>
										<th>Action</th>
					</tr>
				</thead>
				<tbody>
								<?php

foreach ( $format_data ['data'] as $record ) {
									if (array_key_exists ( 'NORECORD', $record )) {
										echo '<tr ><td class="norecord" colspan="8">' . $record ['NORECORD'] . '</td></tr>';
									} else {
										$buttonarr = array ();
										$buttonarr [] = '<a class="ajaxmodel" style="cursor:pointer" data-target="modal" data-contentid="modal_content"  data-url= "' . $CNF->wwwroot . 'projects/changelog/getChangelogInfoById" data-param=\'{"id":' . $record ['id'] . '}\' onclick="ajaxModal(this);"><span class="glyphicon glyphicon-info-sign"></span></a>';
										$buttonarr [] = '<a class="download" style="cursor:pointer"  href="' . \devlib\AppController::generateGetLink ( array (
												"logid" => $record ['id']
										), $cntrlobj->baseurl . "exportSingle" ) . '" alt="Export" title="Export"    ><i class="glyphicon glyphicon-export fa-fw"></i></a>';
										echo '<tr>';
										echo '<td><input type="checkbox" name="selectedexport[' . $record ['id'] . ']" value="' . $record ['id'] . '" val-name="' . $record ['projectname'] . " > " . $record ['rvname'] . " > " . $record ['issueid'] . '" class="chkexport" onchange="setExportSingle(this);" /></td>';
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
<div class="modal fade" id="modal" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					<span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
				</button>
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
		var url = '<?php echo $CNF->wwwroot?>projects/changelog/getallreleaseversion';
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


// for session saved checkbox to select on multiple page

function toggleExportCheckbox(obj) {


    if ($(obj).is(":checked")) {
		$('.chkexport').unbind("onchange");
        $('.chkexport').prop("checked", true);


    }else{

        $('.chkexport').prop("checked",false);
        $('.chkexport').bind("onchange",function(){
        	setExportSingle(this);
        });
    }

   setExportMultiple(obj);
}



    /*
     * set Export Fucntion to set and unset id in session.
     *
     */

function setExportMultiple(obj) {

        var flag = false;
        if ($(obj).is(":checked")) {
                flag = true;

        }
      var sendContent = [];

            $('.chkexport').each(function(i,chkobj){
                sendContent.push({"id":$(chkobj).val(),"name":$(chkobj).attr("val-name"),data:flag})
            });
            setValueAjax(sendContent). error(function() {
            	$(obj).prop("checked",false);
            	toggleAssignCheckbox(obj);



    		});



    }


function setExportSingle(obj){

	var allChk = parseInt($('.chkexport').length, 10);
	var checked = parseInt($('.chkexport:checked').length, 10);
	if (checked < allChk) {

	   $('#parentchk').prop("checked",false);
	}
	if (checked == allChk) {
	   $('#parentchk').prop("checked",true);
	}



        var id   = $(obj).val();
        var name   = $(obj).attr('val-name');
		var flag = false;
        if (id !='') {
            if ($(obj).is(":checked")) {
                flag = true;
            }
        }
        var sendContent = [];
        sendContent.push({
		   id: id,
		   name:name,
		   data:flag,
		});
        setValueAjax(sendContent). error(function() {
			$(obj).prop("checked",false);


		});

    }

function setValueAjax(sendContent) {

		var url = '<?php echo $CNF->wwwroot?>projects/changelog/exportchangelogs';
		var opt ={
				"url" :url,
				"query":sendContent,
				"responsedatatype":"json"
		}		;
			return _devlibAjax.doAjax('showSelectedChangelog',opt);

}

function showSelectedChangelog(data){

	var $listobj =$('#selected_list');

	if(!$.isEmptyObject(data)){

		$listobj.html('');
			var sel;
		try{
			sel =$.parseJSON(data);

				}catch(err){
					console.log(err);
					}
			if($.isEmptyObject(sel.selected)){
				$listobj.html('<span class="label label-warning">No Changelog Selected For Export.</span> ');
				}else{
					var count = 1;
					$.each(sel.selected,function(i,name){

//DONE: show selcted ID in Span tag
					//DONE : if more than 1000 show alert
						if(count > 1000){
							alert("You have selected more than 1000!");
							}
						count++;
						if(name !=''){
							$listobj.append('<span class="label label-default">'+name+'</span> &nbsp;');
						}
					});
				}

			}

}
/*
 * exporting
 */

 function exportSingle(obj, id){


			if(id !=''){
				var url = '<?php echo $CNF->wwwroot?>projects/changelog/exportSingle';
				var opt ={
						"url" :url,
						"responsedatatype":"json",
						"query":{
								"issueid":id
								}
				}		;
					_devlibAjax.doAjax('downloadLog',opt);
				}else{
					console.log("Nothing to Download!");
				}

	 }
function downloadLog(response){
	  if (typeof response.path === "undefined") {

	  }else {
      //Create an hidden iframe, with the 'src' attribute set to the created ZIP file.
      var dlif = $('<iframe/>',{'src':response.path}).hide();
      //Append the iFrame to the context
       $('body').after(dlif);
  }

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