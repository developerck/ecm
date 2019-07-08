<!-- elements are grouped in "rows" -->

<?php echo isset($error) ? $error : '';?>
<div class="panel  panel-primary">
	<div class="panel-heading">Project</div>
	<div class="panel-body">
		<div class="panel  panel-default">
			<div class="panel-heading">SVN Server Detail</div>
			<div class="panel-body">

				<div class="row">
					<div class="cell">
						<?php echo $label_svnurl. $svnurl.$note_svnurl?>
					</div>
				</div>

				<div class="row">
					<div class="cell">
						<?php echo $label_username. $username?>
					</div>
				</div>
				<div class="row  ">
					<div class="cell">

							<?php echo $label_password. $password?>


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


	</div>
</div>
<script>

		</script>