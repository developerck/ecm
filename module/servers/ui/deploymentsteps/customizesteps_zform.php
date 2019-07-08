
<?php echo isset($error) ? $error : '';?>

<div class="panel  panel-primary">
			<div class="panel-heading" >
				Customize Deployment Steps for Server
			</div>
			<div class="panel-body">
			<div class="row">
			<div class="cell">
						<?php echo $label_server_id. $server_id?>
						</div>
			</div>
				<div class="row  ">
			<div class="cell">
			
			<div class="panel  panel-default">
			<div class="panel-heading clearfix" style="height:55px;">
				Steps <?php echo $cntrlobj->getHelpText('help_customizesteps',$cntrlobj->module);?>
				<button type="button" style="display:none" id="addstepbutton" class="btn btn-primary" value="Add Step" data-target="addstep_modal" data-contentid="addstep_modal_content"  onclick="addStep(this);">  Add Step  </button>
			</div>
			<div class="panel-body">
			<div id="customizedsteps">
			<span class="label label-danger">Please select a server!</span>
		
			
			</div>
			</div>
			</div>
			</div>
			</div>
			<div class="row  ">
			<div class="cell">
				<label>
							<?php //echo $btnsubmit?>
						</label>
						<?php //echo $btnreset?>
					</div>
		</div>
			</div>
		</div>

