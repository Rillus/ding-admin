<link href="<?php echo base_url() ?>css/timeline.css" rel="stylesheet" media="all">
<style>
#columns .colWidth, .table .colWidth {
	width:<?php echo (100/count($viewFields)); ?>%
}
</style>
<div id="timeline">
	<table class="table">
		<tr>
			<th>
				<div class="page-header">
					<h3><?php echo $view->name; ?> <small><?php echo $view->description; ?></small></h3>
				</div>
			</th>
			<th>
				<input id="search" /><button id="go">Go!</button>
			</th>
			<th class="lastCol"><?php echo $this->Viewmodel->addRedirect("add/node/".$contentType->id, "Add update", "nodes:create"); ?></th>
		</tr>
	</table>
	
	<div id="posts">
		<?php
		$viewFields = unserialize($view->fields);
		$contentFields = unserialize($contentType->fields);
		
		foreach ($nodes->result() as $node){ ?>
			<article>
				<div>
					<h5><?php echo $this->Datemodel->formatDate($node->create_date); ?></h5>
					<h3><?php echo $node->title; ?></h3>
					<?php
					$nodeContent = unserialize($node->content);
					for ($i = 0; $i < count($contentFields['type']); $i++){
						if (isset($viewFields[$contentFields['safe_name'][$i]])){
							if ($viewFields[$contentFields['safe_name'][$i]] == "on"){
								if ($contentFields['type'][$i] == 11){ ?>
									<img src="<?php echo base_url()."uploads/".$node->created_by."/".$nodeContent[$contentFields['safe_name'][$i]]; ?>" alt="" />
								<?php
								} else { ?>
									<p><?php echo $nodeContent[$contentFields['safe_name'][$i]]; ?></p>
								<?php 
								}
							}
						}
					} ?>
					<div class="tools">
						<?php
						echo $this->Viewmodel->addRedirect('node/'.$node->safe_title, "Link", "nodes:view all, nodes:view own");
						echo $this->Viewmodel->addRedirect('add/node_edit/'.$node->id, "Edit", "nodes:edit all, nodes:edit own");
						echo $this->Viewmodel->addRedirect('delete/node/'.$node->id, "Delete", "nodes:delete all, nodes:delete own");
						?>
					</div>
				</div>
				<span></span>
			</article>
		<?php
		} ?>
		<div class="cleft"></div>
	</div>
</div>
<script type="template" id="tweet-template">
	<article>
		<div class="tweet">
			<a class="user" href="http://twitter.com/{{from_user}}" target="_blank">
				<img src="{{profile_image_url}}" class="tweet-image" />
				<p>{{from_user_name}}</p>
			</a>
			<h5>{{created_at}}</h5>
			<p>{{text}}</p>
			<p>Location: {{geo}}</p>
		</div>
		<span></span>
	</article>
</script>
<script src="http://code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/timeline.js"></script>