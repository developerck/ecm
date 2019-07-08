<!-- elements are grouped in "rows" -->

	<?php echo isset($error) ? $error : '';?>
		<div class="panel  panel-primary">
			<div class="panel-heading">
				Filter User
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="cell">
						<?php echo $label_searchname.$searchname?>
					</div>
				</div>
				<div class="row">
					<div class="cell">
						<?php echo $label_searchemail . $searchemail?>
					</div>
				</div>
                	<div class="row">
					<div class="cell">
						<?php echo $label_role. $role_id?>
					</div>
				</div>
                <div class="row  ">
					<div class="cell">

							<?php //echo $label_isactive_yes. $isactive_1?>


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