<script src="http://code.jquery.com/jquery.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>js/timepicker.js"></script>
<script src="<?php echo base_url() ?>js/bootstrap.js"></script>
<script src="<?php echo base_url() ?>js/script.js"></script>
<?php 
if (isset($scripts)){
	foreach($scripts as $script){
		echo '<script src="'.base_url("js/$script.js").'"></script>';
	}
}

if (isset($inline)){
	echo $inline;
}
?>