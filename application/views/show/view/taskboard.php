<?php 
	$viewFields = unserialize($view->fields);
	$contentFields = unserialize($contentType->fields);
	$viewSettings = unserialize($view->settings); ?>
<style>
#columns .colWidth, .table .colWidth {
	width:<?php echo (100/count($viewFields)); ?>%
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
		<?php if ($this->Permission->level("nodes:create")){ ?>
			<th class="lastCol">
				<?php $this->Viewmodel->addRedirect(site_url("add/node/".$contentType->id), "Add new card"); ?>
			</th>
		<?php } ?>
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
							<?php } ?>
							<?php if ($this->Permission->level("nodes:view all, nodes:view own")){
								$this->Viewmodel->addRedirect(site_url('node/'.$thisWidget->safe_title), "Link"); 
							}
							if ($this->Permission->level("nodes:edit all, nodes:edit own")){
								$this->Viewmodel->addRedirect(site_url('add/node_edit/'.$thisWidget->id), "Edit");
							}
							if ($this->Permission->level("nodes:delete all, nodes:delete own")){
								$this->Viewmodel->addRedirect(site_url('delete/node/'.$thisWidget->id), "Delete");
							} ?>
						</div>
					</li>
				<?php 
				}
			} ?>
		</ul>
	<?php
	} ?>
</div>
<script src="http://code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/touchpunch.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/taskboard.js"></script>