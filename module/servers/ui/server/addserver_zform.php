<!-- elements are grouped in "rows" -->

<?php echo isset($error) ? $error : '';?>
<div class="panel  panel-primary">
	<div class="panel-heading">Server</div>
	<div class="panel-body">
		<div class="panel  panel-default">
			<div class="panel-heading">Server Basic Detail</div>
			<div class="panel-body">
<div class="row">
			<div class="cell">
						<?php echo $label_project_id. $project_id?>
						<?php echo $cntrlobj->getHelpText('help_activeproject','projects');?>
						</div>
		</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_name. $name.$note_name?>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_description. $description?>
					</div>
				</div>
				<div class="row  ">
					<div class="cell">

							<?php echo $label_isactive_yes. $isactive_1?>


					</div>
				</div>
			</div>
		</div>

		<div class="panel  panel-default">
			<div class="panel-heading">Server ftp Detail <?php echo $cntrlobj->getHelpText('help_ftpdetail',$cntrlobj->module);?></div>
			<div class="panel-body">





				<div class="row">
					<div class="cell">
						<?php echo $label_ftptype. $ftptype?>

						</div>

				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_ftpservername. $ftpservername?>

						</div>

				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_ftpserverurl. $ftpserverurl?>

						</div>

				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_ftpusername. $ftpusername?>

						</div>

				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_ftppassword. $ftppassword?>
						</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_ftpotherdetail. $ftpotherdetail?>
						</div>
				</div>
			</div>
		</div>

		<div class="panel  panel-default">
			<div class="panel-heading">Server Database Detail <?php echo $cntrlobj->getHelpText('help_dbdetail',$cntrlobj->module);?></div>
			<div class="panel-body">



				<div class="row">
					<div class="cell">
						<?php echo $label_dbtype. $dbtype?>

						</div>

				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_dbservername. $dbservername?>

						</div>

				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_dbserverurl. $dbserverurl?>

						</div>

				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_dbusername. $dbusername?>

						</div>

				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_dbpassword. $dbpassword?>
						</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_dbotherdetail. $dbotherdetail?>
						</div>
				</div>
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