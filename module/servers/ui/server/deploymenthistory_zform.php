<!-- elements are grouped in "rows" -->

<?php echo isset($error) ? $error : '';?>
<div class="panel  panel-primary">
	<div class="panel-heading">Check Deployment History</div>
	<div class="panel-body">

		<div class="row">
			<div class="cell">
						<?php echo $label_server_id. $server_id?>
						<?php echo $cntrlobj->getHelpText('help_activeserver',$cntrlobj->module);?>
						</div>
		</div>







	</div>
</div>
	