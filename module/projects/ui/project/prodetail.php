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
$pro_basic = $data ['project_basic'];
$pro_assign = $data ['project_assign'];
?>


<div class="panel  panel-warning">

	<div class="panel-body">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#basic" data-toggle="tab">Basic</a></li>
			<li ><a href="#assign" data-toggle="tab">Assigned User</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="basic">
				<div class="table-responsive">
					<table class="table table-striped">

						<tbody>
							<tr>

								<td>Name</td>
								<td><?php echo $pro_basic['name']; ?></td>
							</tr>
							<tr>

								<td>Description</td>
								<td><pre><?php echo nl2br( $pro_basic['description']); ?></pre></td>
							</tr>

							<tr>

								<td>Is Active</td>
								<td><?php echo ($pro_basic['isactive']?'<span class="label label-success">Active</span>':'<span class="label label-danger">In-active</span>'); ?></pre></td>
							</tr>
							<tr>

								<td> Created On</td>
								<td><?php echo pDate($pro_basic['creationtime']); ?></td>
							</tr>
						</tbody>
					</table>



				</div>
			</div>

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
if (count ( $pro_assign) > 0) {
$maxshow = 100;
$count = 1;
foreach ( $pro_assign as $record ) {
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
echo '<tr><td colspan="4"> This project is assigned to more than   <span class="badge">' . count ( $pro_assign ) . '</span> users .! </td></tr>';
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