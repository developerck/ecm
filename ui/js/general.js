/**
 *
 *
 * @project 	cms
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@developerworks
 * @version 	<1.1.1>
 * @since	    2014
 * @file		general.js
 */
var lastFailed;

// check if data is _ecmajaxexception

function isECMAjaxException(data){
// for maintianing html response and json response
	var response=false;
	try{
		response = jQuery.parseJSON(data);
	}catch(err){
		// not json so we do not mean that
	}

	if(!response){
		response = data;
	}

	if(typeof response =='object')
	{
			if(typeof response._ecmajaxexception !=='undefined'){
				// very bad ):
				//making it modal alert
				var disalert= '<div id="_ecmajaxexception" class="alert alert-danger alert-dismissible ecmajaxexception" role="alert">'
  +'<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'
  +'<strong>Ajax Exception!</strong> '
  +response._ecmajaxexception
+'</div>';
				return disalert;
			}else{
				return false;

			}
	}else{
		// we hope ajax exception should be in json object alaways
		 return false

	}
}


var _devlibGeneral = function(){


	var errordiv ='<div class="error-div" style="width:100%;height:100%; text-align:center;"><span class="fa fa-chain-broken fa-2x"></span><div><a href="javascript:void(0);" onclick="lastFailed(); return false;">Click Here <span class="glyphicon glyphicon-repeat"></span></a> </div></span></div>';
	function logDetail(detail){
		if(typeof JS_DEBUG !== 'undefined'){
			if(JS_DEBUG){
				console.log(detail);
			}
		}else{
			console.log(detail);
		}

	}

	/*
	 * ajax paging function
	 *
	 */
	function ajaxPaging(pagingData)
	{
		var htmlContent='';
		var divSelector='#'+pagingData.param.paging.div;

		$(divSelector).ajaxStart(function() {
			logDetail('>>> Paging Started');
			if(!$('#loadingDiv',divSelector).length){
				$(divSelector).html('<div id="loadingDiv" style="width:100%; height:100%;line-height:100%;text-align:center;"><span class="fa fa-spinner fa-2x fa-spin"></span></div>');
			}
		});


		$(divSelector).ajaxStop(function() {
			logDetail('>>> Paging Stopped');
			if($('#loadingDiv',divSelector).length){
				$('#loadingDiv',divSelector).remove();
			}

		});


		//----------call start

		jQuery.ajax({
			type: "POST",
			url: pagingData.param.paging.pagename,
			data: pagingData,
			cache: false,
			dataType: "json",
			global:"false",
			success: function(response){
				logDetail('>>> Paging Ajax Success');
				if(errmsg = isECMAjaxException(response)){
					$(divSelector).html(errmsg +errordiv );
					return null;
				}
				if (typeof pagingData.param.paging.method !== "undefined") {
					window[pagingData.param.paging.method](response);
				}else{

					$(divSelector).html(response);
				}
			}
		});


		lastFailed= function(){
			ajaxPaging(pagingData);
		}
		//-----------------on ajax Error

		$(divSelector).ajaxError(function() {
			logDetail('>>> Paging Error');
			if($('#loadingDiv',divSelector).length){
				$('#loadingDiv',divSelector).remove();
			}
			$(divSelector).html(errordiv );
		});
	}










	/*
	 *  perform Async Ajax
	 *
	 *
	 */



	function performAsyncAjax(url,param,div, callback) {
		lastFailed= function(){
			performAsyncAjax(url,param,div, callback);
		}
		if (typeof div !== "undefined" && div !='' ) {

			//var errodiv= '<div id="reload-err" class="error-div" style="width:100%;height:100%; line-height:100%;text-align:center;"><a href="javascript:void(0);" onclick="lastFailed(); return false;"><span class="glyphicon glyphicon-repeat"></span></a> </div>';
			$.ajax({
				type: "POST",
				url: url,
				data: {"postdata":param},
				cache: false,
				async: true,
				global:false,
				dataType:'html',
				beforeSend:function(){
					if(!$('#loadingDiv',div).length){
						logDetail('>>> Sync Ajax Start:performAsyncAjax');
						console.log($('#reload-err',div).length);
						if($('#reload-err',div).length){
							$('#reload-err',div).remove();
						}
						$(div).html('<div id="loadingDiv" style="width:100%;height:100%; line-height:100%;text-align:center;"><span class="fa fa-spinner fa-2x fa-spin"></span></div>');
					}
				},
		success: function(response){
					logDetail('>>> Sync Ajax Success:performAsyncAjax');
					if(errmsg = isECMAjaxException(response)){
						$(div).html(errmsg +errordiv );
						return null;
					}

					if(typeof callback !== "undefined" && callback !=''){
						window[callback](response);
					}else{

						$(div).html(response);

					}
				},
				error: function(jqXHR,message,errorThrown){
				    logDetail(jqXHR);
                    logDetail(errorThrown);
					logDetail('>>> Sync Ajax Error:performAsyncAjax');
					if($('#loadingDiv',div).length){
						$('#loadingDiv',div).remove();
					}

					$(div).html(errordiv);

				},
				complete:function(){
					logDetail('>>> Sync Ajax Stop:performAsyncAjax');

					if($('#loadingDiv',div).length){
						$('#loadingDiv',div).remove();
					}

				}
			});
		}else{
			logDetail('>>> Div not passed!');
			return false;
		}





	}


	return {

		ajaxPaging : ajaxPaging,

		performAsyncAjax: performAsyncAjax


	}

}();


/*
 Ajax layer Setup
 below ajax layer provide queues solution
 TODO: Need To Enhancement

 */


var _devlibAjax = function () {
	//		logging detail in console
	var xhrPool = [];
	var errordiv ='<div class="error-div" style="width:100%;height:100%; text-align:center;"><span class="fa fa-chain-broken fa-2x"></span><div><a href="javascript:void(0);" onclick="lastFailed(); return false;">Click Here <span class="glyphicon glyphicon-repeat"></span></a> </div></span></div>';

	function abortAllAjax() {
		$.each(xhrPool, function(idx, jqXHR) {
			jqXHR.abort();
		});
	};

	function logDetail(detail) {
		if(typeof JS_DEBUG !== 'undefined'){
			if(JS_DEBUG){
				console.log(detail);
			}
		}else{
			console.log(detail);
		}
	}
	//------set global options of jquery Ajax
	$.ajaxSetup({
		async: true,
		type: "POST",

		global:false,
		timeout: 60000,
		retryAfter: 10000,
		statusCode: {
			404: function () {
				logDetail("page not found");
			}
		},

	});

	//function before send

	function beforeSendFunction(jqXHR, setting) {
		var existReq=$.grep(xhrPool, function(x) {return x == jqXHR});
		if (typeof beforeRender !== "undefined") {
			if (typeof beforeRender === "function") {
				window.beforeRender();
			}
		}
		if (existReq.length != 0) {
			//request pre
			logDetail("request aborted in before send fuunction");
			$.each(existReq, function(idx, lcxhr) {
				lcxhr.abort();
			});
		}

		xhrPool.push(jqXHR);
		logDetail(setting.url);

		// code for attaching loading sign on pages.

	}

	//--------function handle error

	function handleError(xhr, message, errorThrown) {
		// handle error on  network failure
		if (xhr.status == "0") {
			logDetail(" Some Error Occurred ::>> " + message);
			logDetail(errorThrown);

			xhr.complete();

		} else {
			logDetail("Error other than Status 0 : status >> " + xhr.status);
			logDetail("Message >> " + message);
			logDetail(errorThrown);
		}

	}

	//--------- global function on success

	function onSuccess(data) {

		if(typeof data.response !== "undefined"){
			if (typeof data.response.logged !== "undefined") {
				if (!data.response.logged ) {
					logDetail('user logout!');
					window.location.reload();
				}
			}
		}
		//code for remove loading sign
		//$( ".Wrapper .HeaderNew .loading" ).remove();
	}

	function onComplete(jqXHR) {
		logDetail("On Complete Execute in MyAjax");
		xhrPool = $.grep(xhrPool, function(x){return x!=jqXHR});
		if (typeof afterRender !== "undefined") {
			if (typeof afterRender === "function") {
				window.afterRender();
			}
		}
	}

	function setOption(option) {
		//-----do store is a key to set storing in local storage
		var defaultOpt = {
				url: '',
				query: '',
				responsedatatype:'json',
				fnError:'',

		};
		var retOpt = {};
		if (typeof option !== 'undefined') {
			if (typeof option.url !== 'undefined') {
				retOpt["url"] = option.url;
			} else{
				retOpt["url"] = defaultOpt.url;
			}
			if (typeof option.query !== 'undefined') {
				retOpt["query"] =option.query;
			} else {
				retOpt["query"] = defaultOpt.query;
			}
			if (typeof option.responsedatatype !== 'undefined') {
				retOpt["responsedatatype"] = option.responsedatatype;
			} else{
				retOpt["responsedatatype"] = defaultOpt.responsedatatype;
			}
			if (typeof option.fnError !== 'undefined') {
				retOpt["fnError"] = option.fnError;
			} else{
				retOpt["fnError"] = defaultOpt.fnError;
			}

			return retOpt;

		} else {
			return retOpt = defaultOpt;
		}
	}


	function doAjax(fnRender,option ) {
		//   before Ajax Call
		lastFailed= function(){
			doAjax(fnRender,option);
		}

		option = setOption(option);
		if (option.url =='') {
			return null;
		}
		//-----check if render function exist
		/*if (typeof _loc[fnRender] != 'function') {
				logDetail(fnRender + " :: Render function Not Exist!");
				return null;
			}
		 */

		return $.ajax({
			dataType: option.responsedatatype,
			cache: false,
			url: option.url,
			data: {postdata: JSON.stringify(option.query)},
			beforeSend: function (jqXHR, setting) {

				beforeSendFunction(jqXHR, setting);
			},
			success: function (data) {
				logDetail(data);

				/*if (data.response ==false) {
					logDetail('Server side response !false');
				}
				*/
				/*if(option.responsedatatype=='json'){
					data = $.parseJSON(data);
				}
				*/
				onSuccess(data);
				if(errmsg = isECMAjaxException(data)){
					// alert if exception occured!

					$('.errortopnotification','body').remove();
					$('body').prepend('<div class="errortopnotification">'+errmsg+errordiv+'</div>');
					return null;
				}
				// next commented line will call a render fucntion, if render fucntion fuction
				if(fnRender !=''){
					// removing in caes of success
					$('.errortopnotification','body').remove();
					window[fnRender]( data);
				}

			},
			error: function (xhr, textStatus, errorThrown) {
				if(option.fnError !=''){
					window[fnError]( xhr);
				}
				handleError(xhr, textStatus, errorThrown);
			}
			,complete: function (xhr) {
				onComplete(xhr);

			}
		});
	}

	return {
		xhrPool : xhrPool,
		abortAllAjax :abortAllAjax,
		onSuccess:onSuccess,
		onComplete: onComplete,
		doAjax:doAjax
	}

}();