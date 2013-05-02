<?php
$name = $description = $fieldtype = $fieldType[] = $fieldName[] = $fieldDescription[] = "";
$fieldCount = set_value('fieldCount', 1);

$attributes = array('class' => '');
echo form_open('add/view', $attributes);
?>
	<h2>Add a View</h2>	
	<div class="controls controls-row">
		<input type="text" class="input-block-level" placeholder="View name" name="name" value="<?php echo set_value('name', $name, $name); ?>" 
		<?php if ($this->session->flashdata('name')){ 
			echo "disabled"; 
		} ?>>
		<?php echo form_error('name'); ?>
		<input type="text" class="input-block-level" placeholder="Description" name="description" value="<?php echo set_value('description', $description, $description); ?>"
		<?php if ($this->session->flashdata('description')){ 
			echo "disabled"; 
		} ?>>
		<?php echo form_error('description'); ?>
		<select name="viewType" class="input-block-level" id="view-type" <?php if ($this->session->flashdata('viewType')){ 
			echo "disabled"; 
		} ?>>
			<option value="">- View Type -</option>
			<?php 
			$thisViewType = set_value('viewType');
			foreach($views->result() as $view){ 
			?>
				<option value="<?php echo $view->id; ?>" <?php 
				if ($view->id == $thisViewType){
					echo 'selected ="selected"';
				} ?>
				><?php echo $view->name; ?></option>
			<?php } ?>
		</select>
		<?php echo form_error('viewType'); ?>
		<select name="contentType" class="input-block-level" id="content-type" <?php if ($this->session->flashdata('contentType')){ 
			echo "disabled"; 
		} ?>>
			<option value="">- Content Type to view -</option>
			<?php 
			$thisContentType = set_value('contentType');
			foreach($contentTypes->result() as $contentType){ 
			?>
				<option value="<?php echo $contentType->id; ?>" <?php 
				if ($contentType->id == $thisContentType){
					echo 'selected ="selected"';
				} ?>
				><?php echo $contentType->name; ?></option>
			<?php } ?>
		</select>
		<?php echo form_error('contentType'); ?>
	</div>
	<fieldset id="resultHolder">
		<div class="controls controls-row new-field" id="results">
	
		</div>
	</fieldset>
	<script type="template" id="table-template">
		<label class="checkbox">
			<input type="checkbox" name="columns[{{safe_name}}]"> {{name}}
		</label>
    </script>
	<script type="template" id="dragbox-template">
		<input type="text" class="input-block-level" placeholder="Category" name="columns[]" value="">	
    </script>
	<script type="template" id="addButton-template">
		<a href="#" class="btn btn-primary" id="add-button">Add</a>
	</script>
	<input type="hidden" name="redirect" value="<?php if(isset($redirect)){ echo $redirect; } ?>">
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
		<a href="" type="button" class="btn">Cancel</a>
	</div>
</form>