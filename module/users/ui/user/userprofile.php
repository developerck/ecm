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
$user_detail = $data ['user_detail'];
$user_role = $data  ['user_role'];
$user_projects = $data  ['user_projects'];
$user_servers = $data  ['user_servers'];

?>


<div class="panel  panel-warning">
	<!--  <div class="panel-heading">Profile</div>-->
	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#basic" data-toggle="tab">Basic</a></li>
			<li class=""><a href="#role" data-toggle="tab">Role</a></li>
			<li class=""><a href="#projects" data-toggle="tab">Projects</a></li>
			<li class=""><a href="#servers" data-toggle="tab">Servers</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="basic">
				<div class="table-responsive">
					<table class="table table-striped">

						<tbody>
							<tr>

								<td>Name</td>
								<td><?php echo $user_detail['firstname']." ".$user_detail['lastname']; ?></td>
							</tr>
							<tr>

								<td>Email-Id</td>
								<td><?php echo $user_detail['emailid']; ?></td>
							</tr>
							<tr>

								<td>Display Name</td>
								<td><?php echo $user_detail['displayname']; ?></td>
							</tr>
							<tr>

								<td>Signature</td>
								<td><pre><?php echo nl2br( $user_detail['signature']); ?></pre></td>
							</tr>
							<tr>

								<td>Account Created On</td>
								<td><?php echo pDate($user_detail['creationtime']); ?></td>
							</tr>
						</tbody>
					</table>



				</div>
			</div>
			<!-- Roles Start -->
			<div class="tab-pane fade" id="role">
				<div class="table-responsive">
					<table class="table table-striped">

						<tbody>
							<tr>

								<td>Role</td>
								<td><?php echo $user_role['rolename']; ?></td>
							</tr>

						</tbody>
					</table>



				</div>
			</div>

			<!-- Roles END -->
			<!-- Project Start -->
			<div class="tab-pane fade" id="projects">
				<div class="table-responsive">
					<table class="table table-striped">

						<tbody>
                                    <?php if($user_role['shortname']=='ADMIN'){?>
                                        <tr>

								<td>You are Admin . You have access to all Porjects.</td>

							</tr>
                                        <?php
																																				} else {
																																					if (count ( $user_projects ) > 0) {
//TODO: ajax pagination
																																						$maxshow = 100;
																																						$count = 1;
																																						foreach ( $user_projects as $project ) {
																																							echo '<tr><td>' . $project ['name'] . '</td></tr>';
																																							$count ++;
																																							if ($count >= $maxshow) {
																																								echo '<tr><td> User has access to  total <span class="badge">' . count ( $user_projects ) . '</span> projects </td></tr>';
																																								break;
																																							}
																																						}
																																					} else {
																																						echo '<tr><td ><span class="label label-danger"> No Project Assigned yet!! </span></td></tr>';
																																					}
																																				}
																																				?>

                                    </tbody>
					</table>



				</div>
			</div>
			<!-- Projects END -->

			<!-- Servers Start -->
			<div class="tab-pane fade" id="servers">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Project Name</th>
								<th>Server Name</th>
							</tr>
						</thead>
						<tbody>
                                    <?php if($user_role['shortname']=='ADMIN'){?>
                                        <tr>

								<td colspan="2">You are Admin . You have access to all Servers.</td>

							</tr>
                                        <?php
                                        //TODO: ajax pagination
																																				} else {
																																					if (count ( $user_servers ) > 0) {
																																						$maxshow = 100;
																																						$count = 1;
																																						foreach ( $user_servers as $server ) {
																																							echo '<tr>';
																																							echo '<td>' . $server ['projectname'] . '</td>';
																																							echo '<td>' . $server ['name'] . '</td>';
																																							echo '</tr>';
																																							$count ++;
																																							if ($count >= $maxshow) {
																																								echo '<tr><td colspan="2"> User has access to  total  <span class="badge">' . count ( $user_servers ) . '</span> servers.! </td></tr>';
																																								break;
																																							}
																																						}
																																					} else {
																																						echo '<tr><td colspan="2"><span class="label label-danger"> No Server Assigned yet! </span></td></tr>';
																																					}
																																				}
																																				?>

                                    </tbody>
					</table>



				</div>
			</div>
			<!-- Servers END -->
		</div>

	</div>
</div>