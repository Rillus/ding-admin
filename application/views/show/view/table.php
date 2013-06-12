<table class="table">
	<tr>
		<th>
			<div class="page-header">
				<h3>{view_title} <small>{view_description}</small></h3>
			</div>
		</th>
		<th class="lastCol">{add_new_row_button}</th>
	</tr>
</table>

<table class="table sort">
	<thead>
		<tr>
			<th>Name</th>
			{fields}
				<th>{field_name}</th>
			{/fields}
			<th class="lastCol">Actions</th>
		</tr>
	</thead>
	<tbody>
		{content}
		<tr>
			<td>{title}</td>
			{values}
				<td>{value}</td>
			{/values}
			<td class="lastCol">
				{view_button}
				{edit_button}
				{delete_button}
			</td>
		</tr>
		{/content}
	</tbody>
</table>
<script src="http://code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
	//table sorter (for tables that need sorting);
	$("table.sort").tablesorter();
	$("table.sort thead th").append(" <span class='icon'></span>");
</script>

