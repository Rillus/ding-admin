<?php 
$fields = unserialize($contentType->fields);
$content = unserialize($node->content); 
?>

<h1><?php echo $node->title; ?></h1>
<h4>A <?php echo $contentType->name; ?> created on <?php echo $this->Datemodel->formatDate($node->create_date); ?></h4>
	
<?php 
for ($i = 0; $i < count($fields['type']); $i++){ ?>
	<p><?php echo $fields['name'][$i]; ?>:</br>
	<?php echo $content[$fields['safe_name'][$i]]; ?></p>
<?php } ?>