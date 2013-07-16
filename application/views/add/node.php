<?php
$title = $name = $description = $fieldtype = $fieldType[] = $fieldName[] = $fieldDescription[] = $uploader = "";
$fieldCount = set_value('fieldCount', 1);

$attributes = array('class' => '', 'id' => 'mainform');
echo form_open('add/node/'.$contentType->id, $attributes);
?>
	<h2>Add new <?php echo $contentType->name; ?></h2>	
	<div class="controls controls-row">
		<label>Title*
		<input type="text" class="input-block-level" name="title" value="<?php echo set_value('title', $title, $title); ?>" data-required="true">
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
			/* 
				This is where we check the field type as per the below types:
				1 	Text 	A simple text field
				2 	Long text 	A text area for longer textual content
				3 	Date 	A date field formatted as DD/MM/YYYY
				4 	Date/time 	Date and time formatted as DD/MM/YYYY HH:MM:SS
				5 	Drop down selection 	A drop down form element with multiple values to c...
				6 	Integer 	A simple number with no decimal places
				7 	Decimal Number 	A number with decimal places as described by "leng...
				8 	Node reference 	A link to an existing node
				9 	URL 	A text field for a URL
				10 	Email 	An email
				11 	File upload 	uploaded file
			*/
			if ($type == 2){ ?>
				<textarea type="text" class="input-block-level" name="<?php echo $safeName; ?>" data-type="long-text"><?php echo set_value($safeName); ?></textarea>
			<?php 
			} else if ($type == 5){ 
				$options = explode("|", $desc = $fields['description'][$i]);
			?>
				<select class="input-block-level" name="<?php echo $safeName; ?>">
					<?php foreach ($options as $option){ 
						$thisOption = trim($option);
						?>
						<option value="<?php echo $thisOption; ?>"<?php 
							if ($thisOption == set_value($safeName)){
								echo 'selected ="selected"';
							} ?>><?php echo $thisOption; ?></option>
					<?php } ?>
				</select>
			<?php
			} else if ($type == 8){ 
				$nodes = $this->Dbmodel->getNodesByContentType($desc);
				if ($nodes->num_rows() > 0){ 
					$thisFieldVal = set_value($safeName); ?>
					<select class="input-block-level" name="<?php echo $safeName; ?>" data-type="node-ref">
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
			} else if($type == 11){ 
				$uploader = true;
				?>				
				<div class="input-block-level">
					<input type="file" class="span4" id="fileselect" name="userfile" form="upload mainform" />
					<input type="hidden" id="preLocation" value="<?php echo base_url().'uploads/'.$this->Seshmodel->getCurrentUserId().'/'; ?>" />
					<input type="hidden" class="span4" id="fileurl" name="<?php echo $safeName; ?>" />
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
					if ($required == "1"){
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
		<button type="submit" class="btn btn-primary">Save</button>
		<a href="<?php echo site_url('node'); ?>" class="btn">Cancel</a>
	</div>
</form>
<form id="upload" action="<?php echo site_url('upload/'.$contentType->id) ?>" method="POST" enctype="multipart/form-data">
	<input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="1000000" />
	<input type="hidden" id="id" name="id" value="<?php echo $contentType->id ?>" />
</form>
<script src="http://code.jquery.com/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/validate.js"></script>
<?php if ($uploader == true){ ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/dragdrop.js"></script>
<?php } ?>