<?php 
$currentType = "";
$firstOne = true;
foreach ($nodes->result() as $node){ 
	if ($node->content_type != $currentType){ 
		$currentType = $node->content_type;
		
		$type = $this->Dbmodel->getContentTypeById($currentType);
		if (! isset($type->fields)){
			continue;
		}
		
		$fields = unserialize($type->fields);
		if ($firstOne == true){
			$firstOne = false;
		?>
		</table>
		<?php } ?>
		<table class="table">
		<tr>
			<th colspan="<?php echo count($fields['type'])+1; ?>">
				<h4><?php echo $type->name; ?></h4>
			</th>
			<th class="lastCol">
				<h5><a href="<?php echo site_url('add/node/'.$type->id); ?>">Add <?php echo $type->name; ?></a></h5>
			</th>
		</tr>
		<tr>
			<th>Title</th>
			<?php 
			for ($i = 0; $i < count($fields['type']); $i++){ ?>
				<th>
					<?php echo $fields['name'][$i]; ?>
				</th>
			<?php } ?>
			<th class="lastCol">Actions</th>
		</tr>
		<?php
	}
 ?>
	<tr>
		<td><a href="<?php echo site_url('node/'.$node->safe_title); ?>"><?php echo $node->title; ?></a></td>
		<?php 
		$content = unserialize($node->content);
		for ($i = 0; $i < count($fields['type']); $i++){ ?>
			<td>
				<?php echo $content[$fields['safe_name'][$i]] ?>
			</td>
		<?php } ?>
		<td class="lastCol">
			<?php 
			echo $this->Viewmodel->addRedirect('add/node_edit/'.$node->id, "Edit", "nodes:edit all, nodes:edit own");
			echo $this->Viewmodel->addRedirect('delete/node/'.$node->id, "Delete", "nodes:delete all, nodes:delete own");
			?>
		</td>
	</tr>
<?php } ?>
</table>