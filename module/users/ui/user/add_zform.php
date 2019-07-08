<!-- elements are grouped in "rows" -->

<?php echo isset($error) ? $error : '';?>
<div class="panel  panel-primary">
	<div class="panel-heading">Add A New User</div>
	<div class="panel-body">
		<div class="row">
			<div class="cell">
						<?php echo $label_firstname. $firstname. $lastname?>
					</div>
		</div>
		<div class="row">
			<div class="cell">
						<?php echo $label_emailid . $emailid?>
					</div>
		</div>
		<div class="row">
			<div class="cell">
						<?php echo $label_password . $password . $note_password?>
					</div>
		</div>
		<div class="row">
			<div class="cell">
						<?php echo $label_role. $role_id?>
                        <?php echo $cntrlobj->getHelpText('help_role',$cntrlobj->module);?>

					</div>
		</div>
		<div class="row">
			<div class="cell">
						<?php echo $label_displayname . $displayname?>
					</div>
		</div>

		<div class="row">
			<div class="cell">
						<?php echo $label_signature. $signature?>
						 <?php echo $cntrlobj->getHelpText('help_signature',$cntrlobj->module);?>
						 <?php echo  $note_signature?>
					</div>
		</div>
		<div class="row  ">
			<div class="cell">

							<?php echo $label_isactive_yes. $isactive_1?>
<?php echo $cntrlobj->getHelpText('help_isactive',$cntrlobj->module);?>

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
			function autoFillDisplay(obj) {
				var name = $('#firstname').val();

				if ($('#lastname').val() != '' && name.length < 30) {
					name += " " + $('#lastname').val();
				}
				$('#displayname').val(name);
			}





		</script>