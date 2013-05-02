<?php
$attributes = array('class' => '');
echo form_open('add/node_edit/'.$node->id, $attributes);
?>
	<h2>Edit '<?php echo $node->title; ?>'</h2>	
	<div class="controls controls-row">
		<label>Title
		<?php 
		if (set_value('title') == ""){
			$title = $node->title;
		} ?>
		<input type="text" class="input-block-level" name="title" value="<?php echo $title; ?>">
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
			if ($type == 1 || $type == 3 || $type == 4 || $type == 6 || $type == 7){ ?>
			<input type="text" class="input-block-level" name="<?php echo $safeName; ?>" value="<?php echo $val; ?>">
			<?php 
			}
			if ($type == 2){ ?>
			<textarea type="text" class="input-block-level" name="<?php echo $safeName; ?>"><?php echo $val ?></textarea>
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