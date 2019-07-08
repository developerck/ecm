<!-- elements are grouped in "rows" -->

<?php echo isset($error) ? $error : '';?>
<div class="panel  panel-primary">
	<div class="panel-heading">Changelog Detail</div>
	<div class="panel-body">


				<div class="row">
					<div class="cell">
						<?php echo $label_project_id. $project_id?><?php echo $cntrlobj->getHelpText('help_activeproject',$cntrlobj->module);?>
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_releaseversion_id. $releaseversion_id?><?php echo $cntrlobj->getHelpText('help_notlockedrv',$cntrlobj->module);?>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_issueid. $issueid?>
						<?php echo $cntrlobj->getHelpText('help_issueid',$cntrlobj->module);?>
						<?php echo $note_issueid?>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_labelname. $labelname_sel.$labelname?>
						<?php echo $cntrlobj->getHelpText('help_labelname',$cntrlobj->module,'tooltip','left');?>

					</div>
				</div>

					<div class="row">
					<div class="cell">
						<?php echo $label_filelog.$filelog?>
						<?php echo $cntrlobj->getHelpText('help_filelog',$cntrlobj->module,'tooltip','left');?>
						<?php //echo $note_filelog?>
						<div class="panel  panel-warning" id="filelogs_label" style="display:none">

							<div class="panel-body">
						</div>
						</div>
					</div>
				</div>
					<div class="row">
					<div class="cell">
						<?php echo $label_scriptlog. $scriptlog?>
						<?php echo $cntrlobj->getHelpText('help_scriptlog',$cntrlobj->module,'tooltip','left');?>
					</div>
				</div>
					<div class="row">
					<div class="cell">
						<?php echo $label_settings.$settings?>
						<?php echo $cntrlobj->getHelpText('help_settings',$cntrlobj->module,'tooltip','left');?>
					</div>
				</div>
					<div class="row">
					<div class="cell">
						<?php echo $label_comment. $comment?>
						<?php echo $cntrlobj->getHelpText('help_changelogcomment',$cntrlobj->module,'tooltip','left');?>
					</div>
				</div>
				<div class="row  ">
					<div class="cell">

							<?php //echo $label_islocked_yes. $islocked_1.$note_islocked?>


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