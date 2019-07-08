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
//form start
$form = $cntrlobj->form ['form'];
$data = $cntrlobj->form ['data'];
$data['server_id']= $cntrlobj->f3->get('SESSION.servers.customize.server_id');

$serverarr = $cntrlobj->form ['data']['serverarr'];


$form->add('label', 'label_server_id', 'server_id', 'For Server ');
$obj = $form->add('select', 'server_id', $data['server_id'],array("onchange"=>"getSteps(this);"));

$serverarr = array(''=>'- Select Server -')+ $serverarr;


$obj->add_options($serverarr,true);

$obj->set_rule(array(
		'required' => array('error', 'Server Name is required!')
));

// "submit"
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);

// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/customizesteps_zform.php' );



?>
<div class="modal fade" id="addstep_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg"">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Deployment Step <?php echo $cntrlobj->getHelpText('help_addstep',$cntrlobj->module);?></h4>
      </div>
      <div class="modal-body">
	  <div id="addstep_modal_content"></div>

      </div>

    </div>
  </div>
</div>

<script src="<?php echo$CNF->wwwroot.$CNF->uidir;?>/js/jquery_ui.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	getSteps('#server_id');




});

function addStep(obj){
	try{
	var server_id_val = $('#server_id').val();
	if(server_id_val ==''){
		//show alert
		return null;
	}
	var modalid = $(obj).attr('data-target');
	modalid = '#'+modalid;
	var div_id =  $(obj).attr('data-contentid');
	var url = '<?php echo $CNF->wwwroot?>servers/deploymentsteps/addstepview';
	var divsel='#'+div_id;
	var param = {"server_id":server_id_val};
	$(modalid).modal({
		keyboard : true,
	}).modal("show");
	_devlibGeneral.performAsyncAjax(url, param, divsel);

	}catch(err){
			console.log(err);
	}


}

function editStep(obj,stepid){
	try{
	var server_id_val = $('#server_id').val();
	if(server_id_val ==''){
		//show alert
		return null;
	}
	var modalid = $(obj).attr('data-target');
	modalid = '#'+modalid;
	var div_id =  $(obj).attr('data-contentid');
	var url = '<?php echo $CNF->wwwroot?>servers/deploymentsteps/addstepview';
	var divsel='#'+div_id;
	var param = {"server_id":server_id_val,"stepid":stepid};
	$(modalid).modal({
		keyboard : true,
	}).modal("show");
	_devlibGeneral.performAsyncAjax(url, param, divsel);

	}catch(err){
			console.log(err);
	}


}


function deleteStep(obj,id){
	try{
		if(id ==''){
			//show alert
			return null;
		}
		var url = '<?php echo $CNF->wwwroot?>servers/deploymentsteps/deletestep';
		var opt ={
				"responsedatatype" :'json',
				"url" :url,
				"query":{
						"id":id
						}
		}		;
			_devlibAjax.doAjax('renderDeleteSteps',opt);


	}catch(err){
			console.log(err);
	}


}

//response should be either true with blank message or failed with message
function renderDeleteSteps(response){

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

					window.location.reload();
			}else{

			}
		}catch(err){
			console.log(err);
		}
	}
}

function getSteps(obj){

	if($(obj).val() !=''){
		$('#addstepbutton').show();
		var url = '<?php echo $CNF->wwwroot?>servers/deploymentsteps/getStepsByServer';
		var opt ={
				"responsedatatype" :'html',
				"url" :url,
				"query":{
						"server_id":$(obj).val()
						}
		}		;
			_devlibAjax.doAjax('renderGetSteps',opt);
		}else{
			$('#addstepbutton').hide();
			$('#customizedsteps').html('<span class="label label-danger">Please Select a server!</span>');
		}

}

function renderGetSteps(response){
 var renderdivobj =$('#customizedsteps');
	if(response =='[]'){
		renderdivobj.html('<span class="label label-danger">No Steps Defined Yet! Please define some steps.</span>');
	}else{
		renderdivobj.html(response);

			$("#customizedsteps ul").sortable({ opacity: 0.6, cursor: 'move', update: function(event,ui) {

				var arr = $(this).sortable("toArray");
				/*var count =1;
				$.each(arr,function(i,data){
					var $hiddeninputname= $('#step_table_id_'+data);

					$hiddeninputname.val(count);
					count++;

					});
				*/
				// call ajax to save

				saveStepSequence(arr);

			},
			start: function(e, ui) {
		        $(ui.helper).addClass('dragging');
		    },
		    stop: function(e, ui) {
		        $(ui.helper).removeClass('dragging');
		    }
			});

	}
}

function saveStepSequence(arr){
	try{
		var server_id_val = $('#server_id').val();
		if(server_id_val ==''){
			//show alert
			return null;
		}

		var url = '<?php echo $CNF->wwwroot?>servers/deploymentsteps/savestepsequence';
		var opt ={
				"responsedatatype" :'json',
				"url" :url,
				"query":{
						"server_id":server_id_val,
						"steparr":arr
						}
		}		;
			_devlibAjax.doAjax('renderStepSequence',opt);


	}catch(err){
			console.log(err);
	}

}

function renderStepSequence(response){

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

					window.location.reload();
			}else{

			}
		}catch(err){
			console.log(err);
		}
	}
}

</script>
<style>
#customizedsteps{

	width:100%;
	float:left;
}
#addstepbutton{
	float:right;
}

ul.steps{}
ul.steps>li{border:1px solid #2a6496;text-align:left; margin:0;padding:5px; cursor: 'move';min-height:60px;}
ul.steps label{}
ul.steps span.steplabel{float:right;}
ul.steps li div.steprow{float:left;width:90%}

.dragging{background:#f1f7a5}
</style>