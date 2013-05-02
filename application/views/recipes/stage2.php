<?php
$name = $description = $columns[] = $colLength = "";
$fieldCount = set_value('fieldCount', 1);

$attributes = array('class' => '');
echo form_open('recipe/'.$viewType.'/3', $attributes);
?>
	<h2>Cooking up a <?php echo $thisRecipe; ?></h2>
	<h3>...to view <?php echo $contentType->name; ?></h3>
	<div class="controls controls-row">
		<input type="text" class="input-block-level" placeholder="<?php echo $thisRecipe; ?> name" name="name1" value="<?php echo set_value('name1', $name, $name); ?>" 
		<?php if ($this->session->flashdata('name')){ 
			echo "disabled"; 
		} ?>>
		<?php echo form_error('name1'); ?>
		<input type="text" class="input-block-level" placeholder="Description" name="description1" value="<?php echo set_value('description1', $description); ?>"
		<?php if ($this->session->flashdata('description')){ 
			echo "disabled"; 
		} ?>>
		<?php echo form_error('description1'); ?>
		<input name="viewType" type="hidden" value="<?php echo $viewType ?>">
		
		<input name="contentType" type="hidden" value="<?php echo $contentType->id ?>">
	</div>
	<?php if (($viewType == 1)||($viewType == 3)){ //a table or checklist ?>
		<h4>Select the columns to include on your <?php echo $thisRecipe; ?></h4>
		<fieldset id="resultHolder">
			<div class="controls controls-row new-field" id="results">
				<?php 
				$contentFields = unserialize($contentType->fields);
				for ($i = 0; $i < count($contentFields['type']); $i++){  ?>
					<label class="checkbox">
						<input type="checkbox" name="columns[<?php echo $contentFields['safe_name'][$i] ?>]"> <?php echo $contentFields['name'][$i] ?>
					</label>
				<?php } ?>
			</div>
		</fieldset>
	<?php } else if ($viewType == 2){ ?>
		<fieldset id="resultHolder">
			<input type="hidden" name="fieldCount" value="<?php echo $fieldCount; ?>">
			<h4>Name the columns on your task board</h4>
			<?php for ($i = 0; $i < $fieldCount; $i++){ ?>
				<div class="controls controls-row new-field" id="results">
					<input type="text" class="span11" placeholder="Category" name="columns[]" value="<?php echo set_value('columns[]'); ?>">
					<span class="icon-remove deleter"></span>
				</div>
			<?php } ?>
		</fieldset>
		
		<a href="#" class="btn btn-primary" id="add-button">Add</a>
	<?php } ?>
	<script type="template" id="add-row">
		<div class="controls controls-row new-field" id="results">
			<input type="text" class="span11" placeholder="Category" name="columns[]" value=""><span class="icon-remove deleter"></span>
		</div>
    </script>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary">Save changes</button>
		<a href="<?php echo site_url() ?>" type="button" class="btn">Cancel</a>
	</div>
</form>