<?php
$title = $name = $description = $fieldtype = $fieldType[] = $fieldName[] = $fieldDescription[] = "";
$fieldCount = set_value('fieldCount', 1);

$attributes = array('class' => '');
echo form_open('add/node/'.$contentType->id, $attributes);
?>
	<h2>Add new <?php echo $contentType->name; ?></h2>	
	<div class="controls controls-row">
		<label>Title*
		<input type="text" class="input-block-level" name="title" value="<?php echo set_value('title', $title, $title); ?>">
		</label>
		<?php echo form_error('title'); ?>
		
		<?php 
		$fields = unserialize($contentType->fields); 
		
		for($i = 0; $i < count($fields['type']); $i++){ ?>
			<?php 
			$type = $fields['type'][$i];
			$safeName = $fields['safe_name'][$i];
			$desc = $fields['description'][$i];
			$required = $fields['required'][$i];
			?>
			<label><?php echo $fields['name'][$i];
			if ($required == "1"){
				echo "*";
			}
			if ($type == 2){ ?>
				<textarea type="text" class="input-block-level" name="<?php echo $safeName; ?>"><?php echo set_value($safeName); ?></textarea>
			<?php 
			} else if ($type == 3){ ?>
				<input type="text" class="input-block-level datepicker" name="<?php echo $safeName; ?>" value="<?php echo set_value($safeName); ?>">
			<?php
			} else if ($type == 4){ ?>
				<input type="text" class="input-block-level datetimepicker" name="<?php echo $safeName; ?>" value="<?php echo set_value($safeName); ?>">
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
					$thisFieldVal = set_value($safeName); ?>
					<select class="input-block-level" name="<?php echo $safeName; ?>">
						<option>- Select a node -</option>
						<?php foreach ($nodes->result() as $node){ print_r($nodes);?>
							<option value="<?php echo $node->id; ?>"<?php 
								if ($node->id == $thisFieldVal){
									echo 'selected ="selected"';
								} ?>>
								<?php echo $node->title; ?>
							</option>
						<?php
						} ?>
					</select>
				<?php
				}
			} else { ?>
				<input type="text" class="input-block-level" name="<?php echo $safeName; ?>" value="<?php echo set_value($safeName); ?>">
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