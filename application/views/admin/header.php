<?php
defined('BASEPATH') or exit('No direct script access allowed');
echo $this->core->select('value', 'global_setting', 'id=2');
if ($this->core->login()) {
    ?>

<!-- Admin purpose -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css"
    integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>admin/js/jquery-ui-1.12.1.custom/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>admin/js/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>admin/elFinder-2.1.50/css/elfinder.min.css" />
    <link href="<?php echo base_url(); ?>admin/css/mirrel5.css" rel="stylesheet">  
    <script>
        let base_url = "<?php echo base_url(); ?>"; 
        let api = "<?php echo base_url(); ?>api/"; 
        let tinyMCEconnector = "<?php echo base_url(); ?>admin/elFinder-2.1.50/php/connector.minimal.php";
        let current_url = "<?php echo current_url(); ?>";
    </script>
<?php  } 