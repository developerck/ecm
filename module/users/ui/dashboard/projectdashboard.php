<?php
/**
 *
 *
 * @project ecm
 * @author developerck <os.developerck@gmail.com>
 * @copyright @devckworks
 * @version <1.1.1>
 * @since 2014
 */
  ?>
		<?php

         $format_data=$cntrlobj ->table_project_data['data'];

         $pagingStr= isset($cntrlobj ->table_project_data['paging'])?$cntrlobj ->table_project_data['paging']:'';

          ?>
			<div class="panel panel-info">
				<div class="panel-heading">
					Projects <?php echo $cntrlobj->getHelpText('help_dashboard_project',$cntrlobj->module);?>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
					
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>
										#
									</th>
									<?php foreach($format_data[ 'header'] as $header_name){ echo '<th>'.$header_name. '</th>'; } ?>
										
								</tr>
							</thead>
							<tbody>
								<?php foreach($format_data[ 'data'] as $record){
								    if(array_key_exists( 'NORECORD',$record)){ echo '<tr ><td class="norecord" colspan="6">'.$record[ 'NORECORD']. '</td></tr>';
                                    }else{
                                    	                                        echo '<tr>';
                                        echo '<td>'.$record['serial']. '</td>';
                                        echo '<td>'.$record['name']. '</td>';
                                        echo '<td>'.$record['norv']. '</td>';
                                        echo '<td class="txtcenter">'.$record['noc'].'</td>';
                                        echo '<td class="txtcenter">'.$record['nolc'].'</td>';

                                        echo '<td class="txtcenter">'.$record['noau'].'</td>';
                                        echo '</tr>';
                                         }
                                } ?>
							</tbody>
						</table>
						
					</div>
					<!-- /.table-responsive -->
				</div>
				<!-- /.panel-body -->
				<div class="panel-footer">
					<?php echo $pagingStr;?>
				</div>
			</div>
