/**
 *
 *
 * @project 	cms
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@developerworks
 * @version 	<1.1.1>
 * @since	    2014
 */

//----------------you need  jquery for this
function beforeRender(){
	//TODO : write Defination
	
		$('#loading','body').show();
	
}

function afterRender(){
	//TODO : write Defination
	$('#loading','body').hide();
}


$(document).ready(function(){
//	in case of no recor din table then set colspan as per table
	$('table tr td.norecord').each(function(){
		var colspan=1;
		colspan =$(this).closest('table').children('thead').children('tr').children().length;
		$(this).attr("colspan",colspan);

	});
});
$(window).load(function() {
	if(!_devlibAjax.xhrPool.length){
		$("#loading").hide();	
	}
    
    if(!JS_DEBUG){
    	console.clear();
        console.log("Please contribute to project also.!\n it will be helpfull for other!\n \n \n         share the things and grow the planet! :) ");	
    }
    
});
// if ther is an error the  removing loading div
window.onerror = function (message, filename, linenumber) {
	$('#loading','body').hide();
	
	}

//popover and tooltip

$("[data-toggle=tooltip]").tooltip();


//popover static
$("[data-toggle=popover]").popover()



//hiding poppver if clikcing outide
$('body').on('click', function (e) {
	$('[data-toggle=popover]').each(function () {
		// hide any open popovers when the anywhere else in the body is clicked
		if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
			$(this).popover('hide');
		}
	});
	$('[data-toggle=ajaxpopover]').each(function () {
		// hide any open popovers when the anywhere else in the body is clicked
		if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
			$(this).popover('hide');
		}
	});
});

//----Ajax Popover function---
/*
 * required attribute
 *	data-url
 *	data-param
 *
 *
 */
function ajaxPopover(obj){
	// Imporve this fucntion
	// problem as content size changes, we have to setup that
	var popplacement = $(obj).attr('data-placement');
	popplacement = popplacement?popplacement:'left';
	var url = $(obj).attr('data-url');
	var param = $(obj).attr('data-param');
	var div_id =  "div-id-" + $.now();
	var divsel = '#'+div_id;
	$(obj).popover({
		trigger : "manual",
		placement : popplacement,
		html : true,
		content: function(){
			return '<div id="'+ div_id +'"></div>';
		}
	}).popover("show");

	_devlibGeneral.performAsyncAjax(url, param, divsel);
}

/*
 * required attribute
 *	data-url
 *	data-param
 *	data-target
 *	data-conetentid
 */

function ajaxModal(obj){
	try {
	var modalid = $(obj).attr('data-target');
	modalid = '#'+modalid;
	var url = $(obj).attr('data-url');
	var param = $(obj).attr('data-param');
	
	var div_id =  $(obj).attr('data-contentid')
	var divsel = '#'+div_id;
	$(modalid).modal({
		keyboard : true,
	}).modal("show");

	_devlibGeneral.performAsyncAjax(url, param, divsel);
	}catch(err){
			console.log(err);
	}
}

