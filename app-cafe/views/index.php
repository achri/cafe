<html>
<title><?php echo $title;?></title>
<head>
<?php echo $header;?>
<?php echo $extraHeader;?>
<base href="<?php echo base_url(); ?>">
</head>
<body>

<?php $this->load->view('layout');?>

</body>
</html>
