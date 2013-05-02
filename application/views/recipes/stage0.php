<div id="stage0">
	<?php
	$attributes = array('class' => '');
	echo form_open('recipe/'.$viewType.'/3', $attributes);
	?>
		<h2>Cooking up a <?php echo $thisRecipe; ?></h2>
		<div class="controls controls-row">
			<h4>Use an existing data type:</h4>
			<select class="span3" name="contentType">
				<?php
				foreach($contentTypes->result() as $type){ 
					$thisContentType = set_value('contentType');
					?>
					<option value="<?php echo $type->id; ?>" <?php 
						if ($type->id == $thisContentType){
							echo 'selected ="selected"';
						} ?>
						><?php echo $type->name; ?></option>
				<?php
				} ?>
			</select>
			<input type="hidden" name="fromForm" value="1" />
			<button id="go" class="btn">go</button>
		</div>
	</form>
	<?php
	if ($this->Permission->level("content types:create")){
		$attributes = array('id' => 'templatesForm');
		echo form_open('recipe/'.$viewType.'/1', $attributes); ?>
			<div class="controls controls-row">
			<h4>Or use one of these ingredient lists:</h4>
				<div class="controls controls-row" id="content-templates">
					<input type="hidden" name="templateType" value="">
					<a href="#" class="large-button project content-template">
						Project 
						<span></span>
					</a>
					<a href="#" class="large-button userstory content-template">
						User story
						<span></span>
					</a>
					<a href="#" class="large-button subtask content-template">
						Sub-task
						<span></span>
					</a>
					<a href="#" class="large-button new content-template">Something else<span></span></a>
				</div>
			</div>
		</form>
	<?php } ?>
</div>