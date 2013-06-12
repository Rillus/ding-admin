<?php 
	$viewFields = unserialize($view->fields);
	$contentFields = unserialize($contentType->fields);
	$viewSettings = unserialize($view->settings); ?>
<style>
#columns .colWidth, .table .colWidth {
	width:<?php echo $width; ?>%
}
</style>
<link href="<?php echo base_url() ?>css/taskboard.js.css" rel="stylesheet" media="all">
<table class="table">
	<tr>
		<th>
			<div class="page-header">
				<h3><?php echo $view->name; ?> <small><?php echo $view->description; ?></small></h3>
			</div>
		</th>
			<th class="lastCol">
				<?php echo $this->Viewmodel->addRedirect("add/node/".$contentType->id, "Add new card", "nodes:create"); ?>
			</th>
	</tr>
</table>
<table class="table">
	<tr>
		<?php
		for($i = 0; $i < count($viewFields); $i++){ ?>
			<th class="colWidth"><?php echo $viewFields[$i]; ?></th>
		<?php } ?>
	</tr>
</table>
<div id="columns">
	<?php
	foreach ($nodes->result() as $node){
		if (isset($viewSettings[$node->id]['column'])){
			if (isset($viewSettings[$node->id]['order'])){
				$widgetOrder[$viewSettings[$node->id]['column']][$viewSettings[$node->id]['order']] = $node;
			} else {
				$widgetOrder[$viewSettings[$node->id]['column']][] = $node;
			}
		} else {
			$widgetOrder[1][] = $node;
		}
	}
	
	for($i = 1; $i <= count($viewFields); $i++){ ?>
		<ul id="column<?php echo $i ?>" class="column colWidth">
			<?php
			if (isset($widgetOrder[$i])){
				ksort($widgetOrder[$i]);
				foreach ($widgetOrder[$i] as $thisWidget) { ?>
					<li class="widget <?php 
						if (isset($viewSettings[$thisWidget->id]['colour'])){ 
							echo $viewSettings[$thisWidget->id]['colour'] ;
						} else { 
							echo "color-lime"; 
						} ?>" id="widget<?php echo $thisWidget->id; ?>" >
						<div class="widget-head">
							<h3 title="<?php echo htmlentities ($thisWidget->title); ?>"><?php echo $thisWidget->title; ?></h3>
						</div>
						<div class="widget-content">
							<?php
							$nodeContent = unserialize($thisWidget->content);
							$contentFieldsCount = count($contentFields['type']);
							for ($j = 0; $j < $contentFieldsCount; $j++){ 
								echo '<p>'.$contentFields['name'][$j].': '.$nodeContent[$contentFields['safe_name'][$j]].'</p>'; ?>
							<?php } 
							echo $this->Viewmodel->addRedirect('node/'.$thisWidget->safe_title, "Link", "nodes:view all, nodes:view own"); 
							echo $this->Viewmodel->addRedirect('add/node_edit/'.$thisWidget->id, "Edit", "nodes:edit all, nodes:edit own");
							echo $this->Viewmodel->addRedirect('delete/node/'.$thisWidget->id, "Delete", "nodes:delete all, nodes:delete own");
							?>
						</div>
					</li>
				<?php 
				}
			} ?>
		</ul>
	<?php
	} ?>
</div>