<!DOCTYPE html>
<html>
  <head>
	<meta charset="UTF-8">
    <title>Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="<?php echo base_url() ?>css/bootstrap.css" rel="stylesheet" media="all">
	<link href="<?php echo base_url() ?>css/bootstrap-responsive.css" rel="stylesheet" media="all">
	<link href="<?php echo base_url() ?>css/styles.css" rel="stylesheet" media="all">
	<script type="text/javascript">
		var baseUrl = "<?php echo site_url(); ?>/";
	</script>
  </head>
  <body <?php
	if ($this->uri->segment(1) != ""){
		echo 'id="'.$this->uri->segment(1).'" ';
	} else {
		echo 'id="home" ';
	}
	if ($this->uri->segment(2) != ""){
		echo 'class="'.$this->uri->segment(2).'" ';
	} ?>>
	
	<?php $this->load->view('templates/analytics'); ?>
	
	<div class="container">
		<header class="masthead">
			<?php $this->load->view('templates/admin-nav'); ?>
			<?php $this->load->view('templates/nav'); ?>
		</header>

		<div id="contain">