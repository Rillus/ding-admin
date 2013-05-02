<?php 
$currentType = "";
$firstOne = true;
foreach ($views->result() as $view){ 
	if ($view->view_type != $currentType){ 
		$currentType = $view->view_type;

		$type = $this->Dbmodel->getViewTypeById($currentType);
		$contentType = $this->Dbmodel->getContentTypeById($view->content_type);
		if ($firstOne == true){
			$firstOne = false;
		?>
		</table>
		<?php } ?>
		<table class="table">
		<tr>
			<th colspan="4">
				<h4><?php echo $type->name; ?>s</h4>
			</th>
		</tr>
		<tr>
			<th>Name</th>
			<th>Description</th>
			<th>Showing content type</th>
			
			<th class="lastCol">Actions</th>
		</tr>
		<?php
	}
 ?>
	<tr>
		<td><?php echo $view->name; ?></td>
		<td><?php echo $view->description; ?></td>
		<td><?php echo $contentType->name; ?></td>

		<td class="lastCol"><a href="<?php echo site_url('view/'.$view->id); ?>">link</a> <!--| <a href="<?php echo site_url('add/view_edit/'.$view->id); ?>">Edit</a>--></td>
	</tr>
<?php } ?>
</table>