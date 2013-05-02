<div id="message">
<?php if (isset($header) && $header != ""){ ?>
	<h1>
		<?php echo $header;?>
	</h1>
<?php } else { ?>
	<h1>An error has occured...</h1>
<?php } ?>
<?php if (isset($message)){ ?>
	<p><?php echo $message; ?></p>
<?php } else { ?>
	<p>...but I don't know what it is!</p>
<?php } ?>
</div>