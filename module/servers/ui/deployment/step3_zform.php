
<?php echo isset($error) ? $error : '';?>

<div class="panel  panel-primary">
			<div class="panel-heading" >
				Follow Deployment Steps for Server
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
				<div class="panel  panel-warning">
				<div class="panel-body" >

				<?php
			// showing selected changelog

			if (! empty ( $cntrlobj->form ['data']['selectedchangelog'] )) {

				foreach ( $cntrlobj->form ['data']['selectedchangelog'] as $cid=>$val ) {
					echo '<span class="label label-default"><a class="ajaxmodel" style="cursor:pointer" data-target="modal" data-contentid="modal_content"  data-url= "' . $CNF->wwwroot . 'projects/changelog/getChangelogInfoById" data-param=\'{"id":'.$cid .'}\' onclick="ajaxModal(this);">' . $val . '</a></span>&nbsp;';
				}

			} else {
				echo '<span class="label label-warning fa-ws">No Changelog Selected For Deployment.</span> ';
			}
			?>
				</div>
				
<div class="panel-footer" style="text-align:right;height:55px;">
			<?php
			if(extension_loaded ( 'svn' )){
				echo '	<span style="margin-right:20px;">Click to make a file patch from svn :'. $changelogpatchlink.'</span>';
			}
			?>
			<?php
			if (! empty ( $cntrlobj->form ['data']['selectedchangelog'] )) {
			?>
<div class="dropdown" style="float:right">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
    Export
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo  \devlib\AppController::generateGetLink ( array (), $cntrlobj->baseurl . "exportCombined" )?>"  alt="Export" title="Export" >Combined File</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo  \devlib\AppController::generateGetLink ( array (), $cntrlobj->baseurl . "exportByLogType" )?>"  alt="Export" title="Export">Combined By Log Type</a></li>
    <li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo  \devlib\AppController::generateGetLink ( array (), $cntrlobj->baseurl . "exportSeperate" )?>"  alt="Export" title="Export"">Separate Per Issue</a></li>
  </ul>
</div>
			<?php }?>
			</div></div>
			</div>
			
			</div>

				<div class="row  ">
			<div class="cell">

			<div class="panel  panel-default">
			<div class="panel-heading clearfix" style="height:55px;">
				Steps <?php echo $cntrlobj->getHelpText('help_followsteps',$cntrlobj->module);?>

			</div>
			<div class="panel-body">
			<div id="customizedsteps">
			<?php
			if(!empty($cntrlobj->form['data']['steps'])){
				include_once dirname ( __file__ ) . '/deploymentsteps.php' ;
			}else{
				echo '<span class="label label-danger"> No Steps Defined For Deployment On This Server!</span>';

			}


			?>

			</div>
			</div>
			</div>
			</div>
			</div>

			<div class="row">
					<div class="cell">
						<?php echo $label_comment. $comment?>
					</div>
				</div>
			<div class="row  ">
			<div class="cell">
				<label>
							<?php echo $btnsubmit?>
						</label>
						<?php //echo $btnreset?>
					</div>
		</div>
			</div>
		</div>

