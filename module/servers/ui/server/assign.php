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
$data['server_id']= '';
$data['users_id']= '';
$serverarr = $cntrlobj->form ['data']['serverarr'];
$userarr = $cntrlobj->form ['data']['userarr'];
$fuserarr = array();

foreach($userarr as $userval){
	if(!isset($fuserarr[$userval['rolename']])){
		$fuserarr[$userval['rolename']] = array();
	}
	$fuserarr[$userval['rolename']][$userval['id']] = $userval['name'];

}

//form start

$form->add('label', 'label_server_id', 'server_id', 'For Server ');
$obj = $form->add('select', 'server_id', $data['server_id'],array("onchange"=>"getUser(this);"));

$serverarr = array(''=>'- Select server -')+ $serverarr;


$obj->add_options($serverarr,true);

$obj->set_rule(array(
		'required' => array('error', 'Server Name is required!')
));

// TODO : In Future Filter with Role
$form->add('label', 'label_users_id', 'users_id', 'Assign To Users');
$obj = $form->add('select', 'users_id[]', $data['users_id'],array('multiple'=>'multiple',"class"=>"multiselect"));
$obj->set_rule(array(
		'required' => array('error', 'At Least one user is required!')
));
$obj->add_options($fuserarr);
$form->add('note', 'note_users_id', 'users_id',
		'At Least one selection is required!');



// "submit"
$form->add ( 'submit', 'btnsubmit', 'Submit' );
$form->add ( 'reset', 'btnreset', 'Cancel' );
$form->assign('cntrlobj', $cntrlobj);

// generate output using a custom template
$form->render ( dirname ( __file__ ) . '/assign_zform.php' );


?>
<?php
// in case of non-empty user array
if(!empty($userarr)){
?>
<link href="<?php echo$CNF->wwwroot.$CNF->uidir;?>/js/plugins/multiselect/css/bootstrap-multiselect.css" rel="stylesheet">
<script src="<?php echo$CNF->wwwroot.$CNF->uidir;?>/js/plugins/multiselect/js/bootstrap-multiselect.js"></script>
<style>

.btn-group{
position: relative !important;
width: 350px;
max-height: 200px;
overflow-y:scroll;
margin-left: 5px;
padding: 0;
max-height: 200px;
background-color: #fff;
background-image: none;
border: 1px solid #ccc;
border-radius: 4px;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
-webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

</style>
<script>


$(document).ready(function() {
    $('#users_id').multiselect({
    	includeSelectAllOption: true,
    	includeSelectAllDivider: true,
    	enableCaseInsensitiveFiltering:true,
    	selectAllText: 'Select All',
    	enableFiltering: true,
    	checkboxName: 'multiselect_users[]',
    	nonSelectedText: '- Select Users -',
        filterBehavior: 'text',
        filterPlaceholder: 'Search',
        templates: {
            button:'',
            ul: '<ul class="multiselect-container list" id="ul_users_id"></ul>',
            filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
            li: '<li><label></label></li>',
            divider: '<li class="multiselect-item divider"></li>',
            liGroup: '<li class="multiselect-item group"><label class="multiselect-group"></label></li>'
        },
        onChange: function(element, checked) {

          },
          buttonText: function(options) {        	  var selected = [];
              options.each(function() {
                selected.push([$(this).text(), $(this).data('order')]);
              });

              selected.sort(function(a, b) {
                return a[1] - b[1];
              })

              var text = '';
              for (var i = 0; i < selected.length; i++) {
                text += '&nbsp;<span class="label label-default">'+selected[i][0]+'</span>&nbsp;';
              }
              $('#selected_users').html(text);

            },
        });

});

function getUser(obj){
	if($(obj).val() !=''){
		var url = '<?php echo $CNF->wwwroot?>servers/server/getAssignedUserOnServer';
		var opt ={
				"url" :url,
				"query":{
						"serverid":$(obj).val()
						}
		}		;
			_devlibAjax.doAjax('doUserSelection',opt);
		}else{
			var response ={};
			doUserSelection(response);
		}

}

function doUserSelection(response){
	$el = $('#users_id');
	if(!$.isEmptyObject(response)){

		$.each(response, function(i,data) {
			  $el.multiselect('select', data);
		    });
	}else{

		multiselect_deselectAll($el);
	}
}

function multiselect_deselectAll($el) {
    $('option', $el).each(function(element) {
      $el.multiselect('deselect', $(this).val());
    });
  }

	</script>
 <?php } ?>