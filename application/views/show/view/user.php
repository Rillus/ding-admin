<h1><?php echo $user->forename.' '.$user->surname; ?></h1>
<h4><?php echo $user->job_title ?></h4>

<ul>
	<li>Email: <?php echo $user->username ?></li>
	<li>Phone: <?php echo $user->phone ?></li>
	<li>Permissions: <?php echo $user->permissions ?></li>
	<li><a href="<?php echo site_url('users/edit/'.$user->id); ?>">Edit</a></li>
</ul>