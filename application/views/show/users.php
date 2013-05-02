<table class="table">
	<tr>
		<th>
			<h4>Users</h4>
		</th>
		<th class="lastCol">
			<h5><a href="<?php echo site_url('users/add'); ?>">Add User</a></h5>
		</th>
	</tr>
</table>
<table class="table sort">
	<thead>
		<tr>
			<th>Forename</th>
			<th>Surname</th>
			<th>Email</th>
			<th>Phone</th>
			<th>Job Title</th>
			<th>Permissions</th>
			
			<th class="lastCol">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		foreach ($users->result() as $user){ ?>
			<tr>
				<td><?php echo $user->forename; ?></td>
				<td><?php echo $user->surname; ?></td>
				<td><?php echo $user->username; ?></td>
				<td><?php echo $user->phone; ?></td>
				<td><?php echo $user->job_title; ?></td>
				<td><?php echo $user->permissions; ?></td>
				
				<td class="lastCol"><a href="<?php echo site_url('users/user/'.$user->id); ?>">link</a> | <a href="<?php echo site_url('users/edit/'.$user->id); ?>">Edit</a></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
<script src="http://code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
	//table sorter (for tables that need sorting);
	$("table.sort").tablesorter();
	$("table.sort thead th").append(" <span class='icon'></span>");
</script>