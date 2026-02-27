<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Mirrel 5 CMS</title>
    <base href="./">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="<?php echo base_url();?>admin/app/assets/bootstrap-4.3.1-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">

    <link rel="stylesheet" href="<?php echo base_url();?>admin/app/assets/js/jquery-ui-1.12.1.custom/jquery-ui.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>admin/app/assets/js/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css">
    <script src="<?php echo base_url();?>site/config"></script>
    <?php foreach($css as $row){ ?>
    <link rel="stylesheet" href="<?php echo base_url();?>admin/app/<?php echo $row;?>">
    <?php } ?>
</head>

<body>
    <app-root></app-root>
    <script src="<?php echo base_url();?>admin/app/assets/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo base_url();?>admin/app/assets/bootstrap-4.3.1-dist/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>admin/app/assets/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    
    <script src="<?php echo base_url();?>admin/app/polyfills-es5.4fb98da8217a96aab5b2.js" ></script>
    <script src="<?php echo base_url();?>admin/app/polyfills-es2015.15bf1e00ce11f9cb0845.js" ></script>
    <script src="<?php echo base_url();?>admin/app/runtime-es2015.2199351a98d6401de479.js" ></script>
    <script src="<?php echo base_url();?>admin/app/main-es2015.c5eae166383e4c8b6318.js" ></script>
    <script src="<?php echo base_url();?>admin/app/runtime-es5.2199351a98d6401de479.js"></script>
    <script src="<?php echo base_url();?>admin/app/main-es5.c5eae166383e4c8b6318.js"></script>

</body>

</html> 
