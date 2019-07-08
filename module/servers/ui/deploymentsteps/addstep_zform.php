<?php echo isset($error) ? $error : '';?>
		<div class="panel  panel-primary">
			
			<div class="panel-body">
			<div class="row">
					<div class="cell">
						<label>Server: </label><span style="margin-left:5px;" class="label label-primary"><?php echo $serverdetail['name']." (".$serverdetail['projectname'].")"?></span>
					</div>
				</div>
			
				<div class="row">
					<div class="cell">
						<?php echo $label_steplabel. $steplabel?>
						 
						 <?php echo $note_steplabel?>
					</div>
				</div>
			<div class="row">
					<div class="cell">
						<?php echo $label_stepinputtype. $stepinputtype_text.$label_stepinputtype_text.$stepinputtype_none.$label_stepinputtype_none;?>
						 <?php echo $note_stepinputtype;?>
						
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_steprequired. $steprequired_1.$label_steprequired_1.$steprequired_0.$label_steprequired_0;?>
						 <?php echo $note_steprequired;?>
						
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_stepcomment. $stepcomment?>
					</div>
				</div>

				<div class="row  ">
					<div class="cell">
						<label>
							<?php echo $btnaddstepsubmit?>
						</label>
						<?php echo $btnaddstepreset?>
					</div>
				</div>
			</div>
		</div>
		<script>

		</script>