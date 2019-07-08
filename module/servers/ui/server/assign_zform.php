<!-- elements are grouped in "rows" -->

<?php echo isset($error) ? $error : '';?>
<div class="panel  panel-primary">
	<div class="panel-heading">Assign Server To User</div>
	<div class="panel-body">

		<div class="row">
			<div class="cell">
						<?php echo $label_server_id. $server_id?>
						<?php echo $cntrlobj->getHelpText('help_activeserver',$cntrlobj->module);?>
						</div>
		</div>

		<div class="row">
			<div class="cell" >
						<?php echo $label_users_id. $users_id.$note_users_id?>
						<div  id="selected_users"></div>
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

	<script>

		</script>