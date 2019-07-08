<!-- elements are grouped in "rows" -->

	<?php echo isset($error) ? $error : '';?>
		<div class="panel  panel-primary">
			<div class="panel-heading">
				Filter Changelog
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="cell">
						<?php echo $label_project_id. $project_id?>
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_server_id. $server_id?>
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_releaseversion_id. $releaseversion_id?>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_issueid. $issueid?>

					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_labelname. $labelname?>

					</div>
				</div>
                <div class="row  ">
					<div class="cell">

							<?php //echo $label_islocked_yes. $islocked_1?>


					</div>
				</div>
				<div class="row">
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