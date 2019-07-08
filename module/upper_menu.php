<?php
/**
 *
 *
 * @project 	ecm
 * @author 	    developerck <os.developerck@gmail.com>
 * @copyright 	@devckworks
 * @version 	<1.1.1>
 * @since	    2014
 */
?>
<?php
// build menu based on rights



$user_permission = $USER['user_permission'];
$user_detail =$USER['user_detail'];
$reformarr = array ();
if (! empty ( $user_permission )) {
	foreach ( $user_permission as $value ) {
		if (! array_key_exists ( $value ['module'], $reformarr )) {
			$reformarr [$value ['module']] [$value ['controller']] = array ();
		}

		$reformarr [$value ['module']] [$value ['controller']] [] = $value ['action'];
	}
}

?>
<nav class="navbar navbar-default navbar-fixed-top" role="navigation"
	style="margin-bottom: 0">

	<ul class="nav navbar-top-links navbar-right">

<!--
			<li><a href="<?php echo $CNF->wwwroot;?>apps/setting/setting"><i class="fa fa-gears fa-fw"></i> Settings</a></li>
	-->
		<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"
			href="#"> <i class="fa fa-user fa-fw"></i> <?php echo $user_detail['displayname'];?> <i
				class="fa fa-caret-down"></i>
		</a>
			<ul class="dropdown-menu dropdown-user">
				<li><a href="<?php echo $CNF->wwwroot;?>users/user/profile"><i
						class="fa fa-user fa-fw"></i> User Profile</a></li>
				<li class="divider"></li>
				<li><a href="<?php echo $CNF->wwwroot;?>users/user/logout"><i
						class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                        	<!--<li><a href="<?php echo $CNF->wwwroot;?>users/user/changepassword"><i
						class="fa fa-sign-out fa-fw"></i> Change Password</a></li>-->
			</ul> <!-- /.dropdown-user --></li>
		<!-- /.dropdown -->

	</ul>
	<!-- /.navbar-top-links -->
	<ul class="nav navbar-top-links navbar-left">
		<li class="dropdown"><a href='<?php echo $CNF->wwwroot;?>'> <i
				class="fa fa-dashboard fa-fw"></i> Dashboard </span>
		</a></li>
		<?php if(array_key_exists('users',$reformarr)){?>
		<li class="dropdown"><a id="dLabel" class="dropdown-toggle"
			data-toggle="dropdown" data-target="#" href='#'><i class="fa fa-users fa-fw" ></i> Users <span class="fa caret"></span>
		</a>

			<ul class="dropdown-menu multi-level" role="menu"
				aria-labelledby="dropdownMenu">
				<?php

			$cntrlarr = $reformarr ['users'];
			if (array_key_exists ( 'user', $cntrlarr )) {
				?>

				<li><a  href="<?php echo $CNF->wwwroot;?>users/user/userlist">
						<i class="fa fa-list-ul fa-fw"></i> Browse User List </a></li>
				<li><a href="<?php echo $CNF->wwwroot;?>users/user/add/"><i class="fa fa-plus fa-fw"></i>  Add User</a></li>
				<?php } ?>

			</ul></li>
		<?php } ?>
		<?php if(array_key_exists('projects',$reformarr)){?>
		<?php

			$cntrlarr = $reformarr ['projects'];
			if (array_key_exists ( 'project', $cntrlarr ) ||array_key_exists ( 'releaseversion', $cntrlarr )) {
				?>
			<li class="dropdown"><a id="dLabel" class="dropdown-toggle"
			data-toggle="dropdown" data-target="#" href='#'> <i
				class="fa fa-sitemap fa-fw"></i> Projects <span class="fa caret"></span>
		</a>

			<ul class="dropdown-menu multi-level" role="menu"
				aria-labelledby="dropdownMenu">
				<?php

			$cntrlarr = $reformarr ['projects'];
			if (array_key_exists ( 'project', $cntrlarr ) ) {
				?>
				<li><a
					href="<?php echo $CNF->wwwroot;?>projects/project/projectlist">	<i class="fa fa-list-ul fa-fw"></i> Browse
						Project</a></li>
				<li><a href="<?php echo $CNF->wwwroot;?>projects/project/addproject"><i class="fa fa-plus fa-fw"></i> Add
						Project</a></li>
				<?php } ?>


                	<li class="divider"></li>
                	<li><a
					href="<?php echo $CNF->wwwroot;?>projects/project/assign">	<i class="fa fa-exchange fa-fw"></i>
						Assign Projects</a></li>
				<?php if (array_key_exists ( 'releaseversion', $cntrlarr )) {?>
				<li class="divider"></li>

				<li><a
					href="<?php echo $CNF->wwwroot;?>projects/releaseversion/rvlist">	<i class="fa fa-list-ul fa-fw"></i> Browse
						Release Version</a></li>
				<li><a href="<?php echo $CNF->wwwroot;?>projects/releaseversion/add"><i class="fa fa-plus fa-fw"></i> Add
						Release Version</a></li>
					<?php } ?>

			</ul></li>
			<?php } ?>
			<?php } ?>
            <?php if(array_key_exists('servers',$reformarr)){?>
            <?php

			$cntrlarr = $reformarr ['servers'];
			if (array_key_exists ( 'server', $cntrlarr )) {
				?>
            	<li class="dropdown"><a id="dLabel" class="dropdown-toggle"
			data-toggle="dropdown" data-target="#" href='#'> <i
				class="fa fa-cubes fa-fw"></i> Servers <span class="fa caret"></span>
		</a>

			<ul class="dropdown-menu multi-level" role="menu"
				aria-labelledby="dropdownMenu">

				<li><a
					href="<?php echo $CNF->wwwroot;?>servers/server/serverlist">	<i class="fa fa-list-ul fa-fw"></i> Browse
						Server</a></li>
				<li><a href="<?php echo $CNF->wwwroot;?>servers/server/addserver"><i class="fa fa-plus fa-fw"></i> Add
						Server</a></li>

                	<li class="divider"></li>
                	<li><a
					href="<?php echo $CNF->wwwroot;?>servers/server/assign">	<i class="fa fa-exchange fa-fw"></i>
						Assign Server</a></li>
				<li class="divider"></li>

				<li><a
					href="<?php echo $CNF->wwwroot;?>servers/server/deploymenthistory">	<i class="fa fa-history fa-fw"></i> Deployment History
						</a></li>


			</ul></li>
			<?php } ?>
            <?php } ?>

            <?php if(array_key_exists('projects',$reformarr)){?>
            <?php

			$cntrlarr = $reformarr ['projects'];
			if (array_key_exists ( 'changelog', $cntrlarr )) {
				?>
            <li class="dropdown"><a id="dLabel" class="dropdown-toggle"
			data-toggle="dropdown" data-target="#" href='#'> <i
				class="fa fa-pencil-square-o fa-fw"></i> Changelog <span class="fa caret"></span>
		</a>

			<ul class="dropdown-menu multi-level" role="menu"
				aria-labelledby="dropdownMenu">

				<li><a
					href="<?php echo $CNF->wwwroot;?>projects/changelog/browse">	<i class="fa fa-list-ul fa-fw"></i> Browse
						Changeglog</a></li>
				<li><a href="<?php echo $CNF->wwwroot;?>projects/changelog/add"><i class="fa fa-plus fa-fw"></i> Add
						Changelog</a></li>


                	<li class="divider"></li>
                <!-- TODO://Import Expor
                	<li><a
					href="<?php echo $CNF->wwwroot;?>servers/server/assign">	<i class="glyphicon glyphicon-export fa-fw"></i>
						Import Changelog/a></li>
			-->

				<li><a
					href="<?php echo $CNF->wwwroot;?>projects/changelog/export">	<i class="glyphicon glyphicon-export fa-fw"></i> Export Changelog
						</a></li>


			</ul></li>
            <?php } ?>
            <?php } ?>
              <?php if(array_key_exists('servers',$reformarr)){?>

            <li class="dropdown"><a id="dLabel" class="dropdown-toggle"
			data-toggle="dropdown" data-target="#" href='#'> <i
				class="fa fa-check-circle-o  fa-fw"></i> Deployment <span class="fa caret"></span>
		</a>

			<ul class="dropdown-menu multi-level" role="menu"
				aria-labelledby="dropdownMenu">
			 <?php

			$cntrlarr = $reformarr ['servers'];
			if (array_key_exists ( 'deploymentsteps', $cntrlarr )) {
				?>
				<li><a
					href="<?php echo $CNF->wwwroot;?>servers/deploymentsteps/customizesteps">	<i class="fa fa-wrench fa-fw"></i> Customize Deployment Steps
						</a></li>
						<?php } ?>
					<?php 		if (array_key_exists ( 'deployment', $cntrlarr )) {
				?>
				<li><a href="<?php echo $CNF->wwwroot;?>servers/deployment/deploy"><i class="fa fa-check-circle fa-fw"></i> Deployment
						</a></li>

                <?php } ?>

			</ul></li>
			<?php } ?>
	</ul>
</nav>

<!-- /#page-wrapper -->

