<table class="table">
	<tr>
		<th>
			<div class="page-header">
				<h3><?php echo $view->name; ?> <small><?php echo $view->description; ?></small></h3>
			</div>
		</th>
		<?php if ($this->Permission->level("nodes:create")){ ?>
			<th class="lastCol"><a href="<?php echo site_url("add/node/".$contentType->id) ?>">Add new row</th>
		<?php } ?>
	</tr>
</table>

<table class="table sort">
	<thead>
		<tr>
			<th>Name</th>
			<?php
			$viewFields = unserialize($view->fields);
			$contentFields = unserialize($contentType->fields);
			for ($i = 0; $i < count($contentFields['type']); $i++){ 
				if (isset($viewFields[$contentFields['safe_name'][$i]])){
					if ($viewFields[$contentFields['safe_name'][$i]] == "on"){?>
					<th>
						<?php echo $contentFields['name'][$i]; ?>
					</th>
					<?php
					}
				}
			} ?>
			
			<th class="lastCol">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($nodes->result() as $node){ ?>
		<tr>
			<td><?php echo $node->title; ?></td>
			<?php
			$nodeContent = unserialize($node->content);
			for ($i = 0; $i < count($contentFields['type']); $i++){
				if (isset($viewFields[$contentFields['safe_name'][$i]])){
					if ($viewFields[$contentFields['safe_name'][$i]] == "on"){ ?>
						<td>
							<?php echo $nodeContent[$contentFields['safe_name'][$i]]; ?>
						</td>
					<?php 
					}
				}
			} ?>

			<td class="lastCol">
				<?php if ($this->Permission->level("nodes:view all, nodes:view own")){
					$this->Viewmodel->addRedirect(site_url('node/'.$node->safe_title), "Link"); 
				}
				if ($this->Permission->level("nodes:edit all, nodes:edit own")){
					$this->Viewmodel->addRedirect(site_url('add/node_edit/'.$node->id), "Edit");
				}
				if ($this->Permission->level("nodes:delete all, nodes:delete own")){
					$this->Viewmodel->addRedirect(site_url('delete/node/'.$node->id), "Delete");
				} ?>
			</td>
		</tr>
		<?php
		} ?>
	</tbody>
</table>
<script src="http://code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
	//table sorter (for tables that need sorting);
	$("table.sort").tablesorter();
	$("table.sort thead th").append(" <span class='icon'></span>");
</script>

