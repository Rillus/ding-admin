<?php
$title = $node->title;

$attributes = array('class' => '');
echo form_open('add/node_edit/'.$node->id, $attributes);
?>
	<h2>Edit '<?php echo $node->title; ?>'</h2>	
	<div class="controls controls-row">
		<label>Title
		<?php $titley = set_value('title', $title);
		if ($titley == ""){
			$titley = $node->title;
		} ?>
		<input type="text" class="input-block-level" name="title" value="<?php echo $titley; ?>" data-required="true">
		</label>
		<?php echo form_error('title'); ?>
		
		<?php 
		$fields = unserialize($contentType->fields); 
		$content = unserialize($node->content); 
		
		for($i = 0; $i < count($fields['type']); $i++){ ?>
			<label><?php echo $fields['name'][$i]; ?>
			<?php 
			$type = $fields['type'][$i];
			$safeName = $fields['safe_name'][$i];
			$desc = $fields['description'][$i];
			if (set_value($safeName) == ""){
				$val = $content[$safeName];
			} else {
				$val = $content[$safeName];
			}
			if ($type == 2){ ?>
				<textarea type="text" class="input-block-level" name="<?php echo $safeName; ?>" data-type="long-text"><?php echo $val; ?></textarea>
			<?php 
			} else if ($type == 5){ ?>
				<select class="input-block-level" name="<?php echo $safeName; ?>">
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
				</select>
			<?php
			} else if ($type == 8){ 
				$nodes = $this->Dbmodel->getNodesByContentType($desc);
				if ($nodes->num_rows() > 0){ 
					?>
					<select class="input-block-level" name="<?php echo $safeName; ?>" data-type="node-ref">
						<option>- Select a node -</option>
						<?php foreach ($nodes->result() as $node){ print_r($nodes);?>
							<option value="<?php echo $node->id; ?>"<?php 
								if ($node->id == $val){
									echo 'selected ="selected"';
								} ?>>
								<?php echo $node->title; ?>
							</option>
						<?php
						} ?>
					</select>
				<?php
				}
			} else if($type == 11){ 
				$uploader = true;
				?>				
				<div class="input-block-level">
					<input type="file" class="span4" id="fileselect" name="userfile" form="upload mainform" />
					<input type="hidden" class="span4" id="fileurl" name="<?php echo $safeName; ?>" value="<?php echo $val ?>" />
					<div id="dropbox" class="span4">
						<span id="droplabel">...or drag and drop image here to upload...</span>
						<div id="progress"></div>
						<img id="preview" alt="[ preview will display here ]" src="" />
					</div>
					<button type="submit" class="span4" id="submitbutton" form="upload">Upload Files</button>
				</div>
			<?php } else { ?>
				<input type="text" class="input-block-level <?php 
					if ($type == 3){
						echo "datepicker";
					} else if ($type == 4){
						echo "datetimepicker";
					}
				?>" name="<?php echo $safeName; ?>" value="<?php echo set_value($safeName); ?>" data-type="<?php
					if ($type == 3){
						echo "date";
					} else if ($type == 4){
						echo "date-time";
					} else if ($type == 6){
						echo "integer";
					} else if ($type == 7){
						echo "decimal";
					} else if ($type == 9){
						echo "url";
					} else if ($type == 10){
						echo "email";
					}
				?>" data-required="<?php
					if (isset($required) && ($required == "1")){
						echo "true";
					}
				?>">
			<?php 
			} ?>
			</label>
			<?php echo form_error(urlencode($safeName)); ?>
		<?php	
		}
		?>
	</div>
	<input type="hidden" name="redirect" value="<?php if(isset($redirect)){ echo $redirect; } ?>">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
		<a href="<?php echo site_url('node'); ?>" type="button" class="btn">Cancel</a>
	</div>
</form>
<form id="upload" action="<?php echo site_url('upload/'.$contentType->id) ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="1000000" />
	<input type="hidden" id="id" name="id" value="<?php echo $contentType->id ?>" />
</form>
<script src="http://code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/validate.js"></script>
<?php if (isset($uploader) && ($uploader == true)){ ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/dragdrop.js"></script>
<?php } ?>