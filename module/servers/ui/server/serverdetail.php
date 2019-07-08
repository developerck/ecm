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
$data =$cntrlobj->form['data'];
$srv_basic = $data ['server_basic'];
$srv_assign = $data ['server_assign'];
$srv_ftp = $data ['server_ftp'];
$srv_db = $data ['server_db'];
?>


<div class="panel  panel-warning">

	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#basic" data-toggle="tab">Basic</a></li>
			<li ><a href="#ftp" data-toggle="tab">FTP</a></li>
			<li ><a href="#db" data-toggle="tab">DB</a></li>
			<li ><a href="#assign" data-toggle="tab">Assigned User</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="basic">
				<div class="table-responsive">
					<table class="table table-striped">

						<tbody>
							<tr>

								<td>Name</td>
								<td><?php echo $srv_basic['name']; ?></td>
							</tr>
							<tr>

								<td> Description</td>
								<td><pre><?php echo nl2br( $srv_basic['description']); ?></pre></td>
							</tr>

							<tr>

								<td>Is Active</td>
								<td><?php echo ($srv_basic['isactive']?'<span class="label label-success">Active</span>':'<span class="label label-danger">In-active</span>'); ?></pre></td>
							</tr>
							<tr>

								<td> Created On</td>
								<td><?php echo pDate($srv_basic['creationtime']); ?></td>
							</tr>
						</tbody>
					</table>



				</div>
			</div>
<!-- FTP START -->

						<div class="tab-pane fade" id="ftp">
				<div class="table-responsive">
					<table class="table table-striped">

						<tbody>
							<tr>
								<td>Name</td>
								<td><?php echo $srv_ftp['ftpservername']; ?></td>
							</tr>
														<tr>
								<td>Type</td>
								<td><?php echo $srv_ftp['ftptype']; ?></td>
							</tr>

														<tr>
								<td>Host</td>
								<td><?php echo $srv_ftp['ftpserverurl']; ?></t	d>
							</tr>

														<tr>
								<td>Port</td>
								<td><?php echo $srv_ftp['ftpport']; ?></td>
							</tr>

												<tr>
								<td>UserName</td>
								<td><?php echo $srv_ftp['ftpusername']; ?></td>
							</tr>

												<tr>
								<td>Password</td>
								<td><?php echo $srv_ftp['ftppassword']; ?></td>
							</tr>


							<tr>


								<td> Other Detail</td>
								<td><pre><?php echo nl2br( $srv_ftp['ftpotherdetail']); ?></pre></td>
							</tr>


							<tr>

								<td> Updated On</td>
								<td><?php echo pDate($srv_ftp['updationtime']); ?></td>
							</tr>
						</tbody>
					</table>



				</div>
			</div>
			<!-- FTP END -->

			<!--  DB START -->
						<div class="tab-pane fade" id="basic">
				<div class="table-responsive">
					<table class="table table-striped">

						<tbody>
							<tr>
								<td>Name</td>
								<td><?php echo $srv_db['dbservername']; ?></td>
							</tr>
														<tr>
								<td>Type</td>
								<td><?php echo $srv_db['dbtype']; ?></td>
							</tr>

														<tr>
								<td>Host</td>
								<td><?php echo $srv_db['dbserverurl']; ?></t	d>
							</tr>

														<tr>
								<td>Port</td>
								<td><?php echo $srv_db['dbport']; ?></td>
							</tr>

												<tr>
								<td>UserName</td>
								<td><?php echo $srv_db['dbusername']; ?></td>
							</tr>

												<tr>
								<td>Password</td>
								<td><?php echo $srv_db['dbpassword']; ?></td>
							</tr>


							<tr>


								<td> Other Detail</td>
								<td><pre><?php echo nl2br( $srv_db['dbotherdetail']); ?></pre></td>
							</tr>


							<tr>

								<td> Updated On</td>
								<td><?php echo pDate($srv_db['updationtime']); ?></td>
							</tr>
						</tbody>
					</table>


				</div>
			</div>

			<!-- DB END -->
			<!-- Assigned Start -->
			<div class="tab-pane fade" id="assign">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>User Name</th>
								<th>Role</th>
								<th>Status</th>
								<th>Assigned On</th>
							</tr>
						</thead>
						<tbody>
                                                                            <?php
// TODO: ajax pagination
if (count ( $srv_assign) > 0) {
$maxshow = 100;
$count = 1;
foreach ( $srv_assign as $record ) {
$userobj = new \module\users\lib\UserFactory($record['user_id']);

$user_detail =$userobj->userobj['user_detail'];
$user_role = $userobj->userobj['user_role'];

echo '<tr>';
echo '<td>' . $user_detail['firstname']." ".$user_detail['lastname']. '</td>';
echo '<td>' . $user_role['rolename']  . '</td>';
echo '<td class="txtcenter">' . ($user_detail ['isactive'] ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">In-active</span>') . '</td>';
echo '<td>' . pDate($record['assignedtime'])  . '</td>';
echo '</tr>';
$count ++;
if ($count >= $maxshow) {
echo '<tr><td colspan="4"> This server is assigned to more than   <span class="badge">' . count ( $srv_assign ) . '</span> users .! </td></tr>';
break;
}
}
} else {
echo '<tr><td colspan="4"><span class="label label-danger"> No User Assigned yet! </span></td></tr>';
}

?>
                                    </tbody>
					</table>



				</div>
			</div>
			<!-- Assigned END -->

		</div>

	</div>
</div>