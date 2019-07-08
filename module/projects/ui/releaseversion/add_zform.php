<!-- elements are grouped in "rows" -->

	<?php echo isset($error) ? $error : '';?>
		<div class="panel  panel-primary">
			<div class="panel-heading">
				Release Version
			</div>
			<div class="panel-body">
			<div class="row">
					<div class="cell">
						<?php echo $label_project_id. $project_id?> <?php echo $cntrlobj->getHelpText('help_activeproject',$cntrlobj->module);?>
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_rvname. $rvname.$rcname?>
						 <?php echo $cntrlobj->getHelpText('help_rv',$cntrlobj->module);?>
						 <?php echo $note_rvname?>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_description. $description?>
					</div>
				</div>
				<div class="row  ">
					<div class="cell">

							<?php echo $label_islocked_yes. $islocked_1.$note_islocked?>


					</div>
				</div>
				<div class="row  ">
					<div class="cell">
						<label>
							<?php echo $btnsubmit?>
						</label>
						<?php echo $btnreset?>
					</div>
				</div>
			</div>
		</div>
		<script>

		</script>