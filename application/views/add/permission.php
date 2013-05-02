<?php
$permLevel = array (
	"Read All",
	"Read Own",
	"Create",
	"Edit All",
	"Edit Own",
	"Delete All",
	"Delete Own",
);
$types = array (
	"Views",
	"Content Types",
	"Nodes",
	"Permissions",
	"Users",
);

$attributes = array('class' => '');
if($this->Permission->level("permissions:edit all")){
	echo form_open('permissions', $attributes);
	echo '<h2>Add/Edit permissions</h2>';
} else {
	echo '<h2>Permissions</h2>';
}
?>
	<table class="table">
		<thead>
			<tr>
				<th>Permission Level</th>
				<?php 
				foreach ($permLevel as $permLev){
					echo "<td>$permLev</td>";
				} ?>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($permissions->result() as $perm){ 
				$checkered = unserialize($perm->permissions);
				?>
				<tr class="header" id="<?php echo preg_replace('/\s+/', '', $perm->name); ?>">
					<th colspan="<?php echo count($permLevel); ?>"><?php echo $perm->name; ?></th>
					<th class="lastCol"></th>
				</tr>
				<?php
				foreach ($types as $key => $type){ ?>
				<tr class="<?php echo preg_replace('/\s+/', '', $perm->name); ?> types">
					<td><?php echo $type; ?></th>
					<?php
					for ($i = 0; $i < count($permLevel); $i++){ ?>
						<td>
							<label class="checkbox">
								<input type="hidden" value="0" name="permissions[<?php echo $perm->id ?>][<?php echo $key ?>][<?php echo $i ?>]">
								<input type="checkbox" value="1" name="permissions[<?php echo $perm->id ?>][<?php echo $key ?>][<?php echo $i ?>]" <?php
									if ($checkered[$key][$i] == "1"){
										echo "checked";
									}
								?>>
							</label>
						</td>
					<?php } ?>
				</tr>
				<?php } ?>
			<?php 
			} ?>
		</tbody>
	</table>
	<?php if($this->Permission->level("permissions:create")){ ?>
		<div class="controls controls-row">
			<input type="text" class="input-block-level" placeholder="Add new permission type" name="name" value="">
		</div>
	<?php } ?>
	<?php if($this->Permission->level("permissions:edit all")){ ?>
		<input type="hidden" name="redirect" value="<?php if(isset($redirect)){ echo $redirect; } ?>">
		<div class="form-actions">
			<button type="submit" class="btn btn-primary">Save changes</button>
			<a href="<?php echo site_url('users') ?>" type="button" class="btn">Cancel</a>
		</div>
	</form>
	<?php } ?>