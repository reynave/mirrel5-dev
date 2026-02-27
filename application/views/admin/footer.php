<?php
defined('BASEPATH') or exit('No direct script access allowed');
echo $this->core->select('value', 'global_setting', 'id=1');
if ($this->core->login()) {
    ?>
<!-- Admin purpose -->
    <script type="text/javascript" src="<?php echo base_url(); ?>admin/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>admin/elFinder-2.1.50/js/elfinder.min.js" defer="defer"></script>
    <script src="https://cdn.tiny.cloud/1/cc5hqwaiqsocbfakkn5qpug7r9bx5zioxppbw9h6w4gd0286/tinymce/5/tinymce.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>admin/elFinder-2.1.50/tinymceElfinder.js"></script>
    <script type="text/javascript"  src="<?php echo base_url(); ?>admin/js/base64.js"></script>
    <script type="text/javascript"  src="<?php echo base_url(); ?>admin/js/system.js"></script>
<?php
} 