<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 * @module      servers
 *
 */
namespace module\servers\lib;
//TODO: defination of class

// TODO: will make admin and user class seperate that's why making it factory

class StepGenerator{

	public function __construct(){

	}


	public function generateStep($stepdata ,$readonly=false){
		if(empty($stepdata) && !is_array($stepdata)){
			return false;

		}
		$readonly = $readonly?'readonly disabled':'';
		$stepid = $stepdata['stepid'];
		$steplabel = $stepdata['steplabel'];
		$stepinputtype = $stepdata['stepinputtype'];
		$stepinputname = $stepdata['stepinputname'];
		$steprequired = $stepdata['steprequired'];
		$stepcomment = $stepdata['stepcomment'];
		$input ='';

		if($stepinputtype == 'text'){
			$input = '<input type="text" name="'.$stepinputname.'" id="id_'.$stepinputname.'" '.$readonly.' value="" class="steptext form-control"/>';
		}else if($stepinputtype == 'checkbox'){
			$input = '<input type="checkbox" name="'.$stepinputname.'" id="id_'.$stepinputname.'" value="" '.$readonly.' class="stepcheckbox "/>';
		}
		$required = $steprequired?'<span class="required">*</span>':'';
		$reuiredinput = $required?' class="requiredinput" ':'';
		$stepcomment = $stepcomment?'<div class="note" >'.$stepcomment.'</div>':'';
		$str = '<div class="steprow"  id="step_'.$stepid.'">
					<div class="stepcell"><input '.$reuiredinput.' type="checkbox" '.$readonly.'/>'.$required.
						'<label><strong>'.$steplabel.'</strong> </label>
								'.$input. $stepcomment.'
					</div>
				</div>';
		return $str;
	}
	
	public function generateFilledStep($stepdata ,$readonly=false){
		if(empty($stepdata) && !is_array($stepdata)){
			return false;
	
		}
		//$readonly = $readonly?'readonly disabled':'';
		$steptblid = $stepdata['id'];
		$stepid = $stepdata['stepid'];
		$steplabel = $stepdata['steplabel'];
		$stepinputtype = $stepdata['stepinputtype'];
		$stepinputname = $stepdata['stepinputname'];
		$steprequired = $stepdata['steprequired'];
		$stepcomment = $stepdata['stepcomment'];
		$input ='';
		$stepcheckbox ='stepchk['.$steptblid.']';
		$steptxt ='steptxt['.$steptblid.']';
	
		if($stepinputtype == 'text'){
			$input = '<input type="text" name="'.$steptxt.'" id="id_'.$stepinputname.'" '.$readonly.' value="" class="steptext form-control"/>';
		}else if($stepinputtype == 'checkbox'){
			$input = '<input type="checkbox" name="'.$stepinputname.'" id="id_'.$stepinputname.'" value=""  class="stepcheckbox "/>';
		}
		$required = $steprequired?'<span class="required">*</span>':'';
		$reuiredinput = $required?' class="requiredinput" ':'';
		$stepcomment = $stepcomment?'<div class="note" >'.$stepcomment.'</div>':'';
		$str = '<div class="steprow"  id="step_'.$stepid.'">
					<div class="stepcell"><input '.$reuiredinput.' name="'.$stepcheckbox.'" value="'.$steptblid.'" type="checkbox" '.$readonly.'/>'.$required.
					
						'<label><strong>'.$steplabel.'</strong> </label>
								'.$input. $stepcomment.'
					</div>
				</div>';
		return $str;
	}

	public function generateDeployedStep($stepdata ,$readonly=true){
		if(empty($stepdata) && !is_array($stepdata)){
			return false;
	
		}
		$readonly = $readonly?'readonly disabled':'';
		$stepid = $stepdata['stepid'];
		$steplabel = $stepdata['steplabel'];
		$stepinputtype = $stepdata['stepinputtype'];
		$stepinputname = $stepdata['stepinputname'];
		$stepinputvalue = $stepdata['stepinputvalue'];
		$steprequired = $stepdata['steprequired'];
		$stepcomment = $stepdata['stepcomment'];
		$input ='';
	
		if($stepinputtype == 'text'){
			$input = '<input type="text" size="50" name="'.$stepinputname.'" id="id_'.$stepinputname.'" '.$readonly.' value="'.$stepinputvalue.'" class="steptext form-control"/>';
		}else if($stepinputtype == 'checkbox'){
			$input = '<input type="checkbox" name="'.$stepinputname.'" id="id_'.$stepinputname.'" value="'.$stepinputvalue.'" '.$readonly.' class="stepcheckbox"/>';
		}
		$required = $steprequired?'<span class="required">*</span>':'';
		$reuiredinput = $required?' class="requiredinput" ':'';
		$stepcomment = $stepcomment?'<div class="note" >'.$stepcomment.'</div>':'';
		$str = '<div class="steprow"  id="step_'.$stepid.'">
					<div class="stepcell"><input '.$reuiredinput.' type="checkbox" '.$readonly.' checked/>'.$required.
						'<label><strong>'.$steplabel.'</strong> </label>
								'.$input. $stepcomment.'
					</div>
				</div>';
		return $str;
	}
	
}