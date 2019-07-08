<!-- elements are grouped in "rows" -->

<?php echo isset($error) ? $error : '';?>
<div class="panel  panel-primary">
	<div class="panel-heading">Project</div>
	<div class="panel-body">
		<div class="panel  panel-default">
			<div class="panel-heading">Basic Project Detail</div>
			<div class="panel-body">

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

		<div class="panel  panel-default" style="display:none" >
			<div class="panel-heading">Project SCM Detail <?php echo $cntrlobj->getHelpText('help_scmdetail',$cntrlobj->module);?></div>
			<div class="panel-body">





				<div class="row">
					<div class="cell">
						<?php echo $label_scmtype. $scmtype?>

						</div>

				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_secmervername. $secmervername?>

						</div>

				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_secmerverurl. $secmerverurl?>

						</div>

				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_scmusername. $scmusername?>

						</div>

				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_scmpassword. $scmpassword?>
						</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_scmotherdetail. $scmotherdetail?>
						</div>
				</div>
			</div>
		</div>

		<div class="panel  panel-default" style="display:none">
			<div class="panel-heading">Project Database Detail <?php echo $cntrlobj->getHelpText('help_dbdetail',$cntrlobj->module);?></div>
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