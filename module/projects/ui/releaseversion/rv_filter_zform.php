<!-- elements are grouped in "rows" -->

	<?php echo isset($error) ? $error : '';?>
		<div class="panel  panel-primary">
			<div class="panel-heading">
				Filter Release Version
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="cell">
						<?php echo $label_project_id. $project_id?>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_rvname. $rvname?>

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