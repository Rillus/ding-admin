<?php if (isset($hashtag)){ ?>
	<h1 id="hashtag">#<?php echo $hashtag; ?></h1>
<?php } ?>
<div id="grid">
	<div id="columns" class="column js-masonry" data-masonry-options='{ "columnWidth": 200, "itemSelector": ".entry", "gutter": 20 }'>
		<?php
		foreach ($nodes->result() as $thisNode){ 
			$nodeContent = unserialize($thisNode->content);
			$thisContentFields = $contentFields[$thisNode->content_type];
			$thisFieldsCount = count($thisContentFields['type']);
			?>
			<div class="entry <?php echo $thisContentFields['type_safe_name']; ?>">
				<a href="<?php echo site_url('entry/'.$thisNode->safe_title); ?>" data-href="<?php echo site_url('entry/'.$thisNode->safe_title); ?>" class="entry-link">
					<?php if (($thisContentFields['type_safe_name'] == "status_update")||($thisContentFields['type_safe_name'] == "milestone")) { ?>
						<div class="title">
							<h3 title="<?php echo htmlentities ($thisNode->title); ?>"><?php echo $thisNode->title; ?></h3>
						</div>
					<?php }
					if ($thisContentFields['type_safe_name'] == 'check_in'){ ?>
						<div class="map_canvas" style="width:200px; height:200px; opacity:0.5"></div>
					<?php } ?>
					<p class="time"><?php echo $this->Datemodel->formatDate($thisNode->create_date); ?></p>
					<?php
					for ($j = 0; $j < $thisFieldsCount; $j++){
						if (isset($nodeContent[$thisContentFields['safe_name'][$j]])){
							if ((isset($nodeContent[$thisContentFields['safe_name'][$j]])) && ($nodeContent[$thisContentFields['safe_name'][$j]] != "")){
								if ($thisContentFields['type'][$j] == 11){ ?>
									<div class="entry-image" style="background-image:url('<?php echo $nodeContent[$thisContentFields['safe_name'][$j]]; ?>');"></div>
								<?php 
								} else if (($thisContentFields['type'][$j] == 3)||($thisContentFields['type'][$j] == 4)){ ?>
									<p class="<?php echo $thisContentFields['safe_name'][$j] ?> icon"><?php echo $this->Datemodel->milestone($nodeContent[$thisContentFields['safe_name'][$j]]); ?></p>
								<?php
								} else { 
									?>
									<p class="<?php echo $thisContentFields['safe_name'][$j] ?> icon"><?php echo $nodeContent[$thisContentFields['safe_name'][$j]]; ?></p>
								<?php 
								}
							}
						}
					} ?>
				</a>
				<?php if (! isset($username)){
					$username = $this->Dbmodel->getUserById($thisNode->created_by);
					$username = $username->username;
				} ?>
				<p class="username"><a href="<?php echo site_url('user/'.$username); ?>"><span class="icon-sect-icon" title="User source: Sect"></span><?php echo $username; ?></a></p>
				<div class="inspect">
					<a href="<?php echo site_url('entry/'.$thisNode->safe_title); ?>" data-href="<?php echo site_url('entry/'.$thisNode->safe_title); ?>" class="icon-expand" title="View"></a>
				</div>
				<div class="interact">
					<?php if ($this->Dbmodel->loved($this->Seshmodel->getCurrentUserId(), $thisNode->id)){
						$loved = true;
					} else {
						$loved = false;
					} ?>
					<a href="<?php echo site_url('entry/'.$thisNode->safe_title); ?>#love" data-item ="<?php echo $thisNode->safe_title; ?>"
						<?php if ($loved){ ?>
							class="icon-heart pre-loved" title="I'm all outta love">
						<?php } else { ?>
							class="icon-heart" title="I love it!">
						<?php } ?>
					</a>
					<a href="<?php echo site_url('entry/'.$thisNode->safe_title); ?>#comment" class="icon-bubble" title="Comment"></a>
					<a href="<?php echo site_url('entry/'.$thisNode->safe_title); ?>" data-item ="<?php echo $thisNode->safe_title; ?>" class="icon-share" title="Share"></a>
				</div>
				<div class="controls">
					<?php
					echo $this->Viewmodel->addRedirect('node/'.$thisNode->safe_title, "Link", "nodes:view all, nodes:view own"); 
					echo $this->Viewmodel->addRedirect('add/node_edit/'.$thisNode->id, "Edit", "nodes:edit all, nodes:edit own");
					echo $this->Viewmodel->addRedirect('delete/node/'.$thisNode->id, "Delete", "nodes:delete all, nodes:delete own");
					?>
				</div>
			</div>
		<?php 
		} ?>
	</div>
</div>