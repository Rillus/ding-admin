<?php
if ($this->uri->segment(1) == "edit"){
	$name = $contentType->name;
	$description = $contentType->description;
	$fieldArray = unserialize($contentType->fields);
	$fieldType = $fieldArray['type'];
	$fieldName = $fieldArray['name'];
	$fieldDescription = $fieldArray['description'];
	$fieldRequired = $fieldArray['required'];
	$thisFieldType = set_value('fieldType[]', $fieldArray['type']);
	
	$fieldCount = count($fieldArray['name']);
	
	$attributes = array('class' => '');
	echo form_open('edit/content_type/'.$this->uri->segment(3), $attributes);
	echo "<h2>Edit a content type</h2>";	
} else {
	$name = $description = $fieldtype = $fieldType[] = $fieldName[] = $fieldDescription[] = $fieldRequired[] = "";
	//$thisFieldType = set_value('fieldType[]');
	$fieldCount = set_value('fieldCount', 1);

	$attributes = array('class' => '');
	echo form_open('add/content_type', $attributes);
	echo "<h2>Add a content type</h2>";	
}
?>
	<div class="controls controls-row">
		<input type="text" class="input-block-level" placeholder="Name" name="name" value="<?php echo set_value('name', $name); ?>">
		<?php echo form_error('name'); ?>
		<input type="text" class="input-block-level" placeholder="Description" name="description" value="<?php echo set_value('description', $description); ?>">
		<?php echo form_error('description'); ?>
	</div>
	<h3>Fields</h3>	
	<input type="hidden" name="fieldCount" value="<?php echo set_value('fieldCount', $fieldCount); ?>">
	<fieldset>
		<div class="controls controls-row new-field">
			<select name="" class="span3" disabled>
				<option value="">Text</option>
			</select>
			
			<input type="text" class="span3" name="" value="Title" disabled>

			<input type="text" class="span3" name="" value="The node's title" disabled>

			<input type="text" class="span2" name="" value="Yes" disabled>
			
		</div>
	</fieldset>
	<fieldset>
	<?php 
	for($i = 0; $i < $fieldCount; $i++){ ?>
		<div class="controls controls-row new-field">
			<select name="fieldType[]" class="span3">
				<option value="">- Field Type -</option>
				<?php 
				$thisFieldType = set_value('fieldType[]');
				foreach($fields->result() as $field){ 
					$fieldTypeDefault = false;
				?>
					<option value="<?php echo $field->id; ?>" <?php 
					//if (isset($thisFieldType[$i])) {
							if ($field->id == $thisFieldType){
							echo 'selected ="selected"';
						} 
					//} ?>
					><?php echo $field->name; ?></option>
				<?php } ?>
			</select>
			<?php if (! isset($fieldName[$i])) { $fieldName[$i] = ""; } ?>
			<?php if (! isset($fieldDescription[$i])){ $fieldDescription[$i] = ""; } ?>
			<?php if (! isset($fieldRequired[$i])){ $fieldRequired[$i] = ""; } ?>
			<input type="text" class="span3" placeholder="Field Name" name="fieldName[]" value="<?php echo set_value('fieldName[]', $fieldName[$i]); ?>">

			<input type="text" class="span3" placeholder="Field Description" name="fieldDescription[]" value="<?php echo set_value('fieldDescription[]', $fieldDescription[$i]); ?>">

			<input type="text" class="span2" name="fieldRequired[]" placeholder="Required?" value="<?php echo set_value('fieldRequired[]', $fieldRequired[$i]); ?>">

			<span class="icon-remove deleter"></span>
					
			<span class="help-inline"><?php echo form_error('fieldType[]'); ?></span>
			<span class="help-inline"><?php echo form_error('fieldName[]'); ?></span>
			<span class="help-inline"><?php echo form_error('fieldDescription[]'); ?></span>
		</div>
	<?php } ?>
	</fieldset>

	<a href="#" class="btn btn-primary" id="add-button">Add</a>
	
	<input type="hidden" name="redirect" value="<?php if(isset($redirect)){ echo $redirect; } ?>">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
		<a href="" type="button" class="btn">Cancel</a>
	</div>
	
	<script type="template" id="add-row">
		<div class="controls controls-row new-field">
			<select name="fieldType[]" class="span3">
				<option value="">- Field Type -</option>
				<?php 
				$thisFieldType = "";//set_value('fieldType[]');
				foreach($fields->result() as $field){ 
					$fieldTypeDefault = false;
				?>
					<option value="<?php echo $field->id; ?>" <?php 
					if ($field->id == $thisFieldType){
						echo 'selected ="selected"';
					} ?>
					><?php echo $field->name; ?></option>
				<?php } ?>
			</select>
			
			<input type="text" class="span3" placeholder="Field Name" name="fieldName[]" value="<?php echo set_value('fieldName[]'); ?>">

			<input type="text" class="span3" placeholder="Field Description" name="fieldDescription[]" value="<?php echo set_value('fieldDescription[]'); ?>">

			<input type="text" class="span2" name="fieldRequired[]" placeholder="Required?" value="<?php echo set_value('fieldRequired[]'); ?>">

			<span class="icon-remove deleter"></span>
					
			<span class="help-inline"><?php echo form_error('fieldType[]'); ?></span>
			<span class="help-inline"><?php echo form_error('fieldName[]'); ?></span>
			<span class="help-inline"><?php echo form_error('fieldDescription[]'); ?></span>
		</div>
    </script>
	<script type="template" id="dropdown-select">
		<select class="span3" name="fieldDescription[]">
			<option value="">- Select content type -</option>
			<?php
			foreach($contentTypes->result() as $type){ 
				$thisContentType = set_value('fieldDescription[]');
				?>
				<option value="<?php echo $type->id; ?>" <?php 
					if ($type->id == $thisContentType){
						echo 'selected ="selected"';
					} ?>
					><?php echo $type->name; ?></option>
			<?php
			} ?>
		</select>
    </script>
	<script type="template" id="description-field">
		<input type="text" class="span3" placeholder="Field Description" name="fieldDescription[]" value="<?php echo set_value('fieldDescription[]', $fieldDescription[0]); ?>">
    </script>
</form>