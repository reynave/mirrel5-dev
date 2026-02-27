<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="bg-danger p-2"></div>

<main class="bg-body pt-4">
    <div class="container">

        <div class="row">
            <div class="col-12">
                <h4>Search</h4>
                <p>Home > Search Result > Showing <?php echo count($this->core->search()); ?> for “<?php echo $this->input->get('q'); ?>”</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php
                foreach ($this->core->search() as $row) {
                    ?>
                    <p><a href="<?php echo $row['url']; ?>" class="search-title"><?php echo $row['name']; ?></a></p>
                    <p><a href="<?php echo $row['url']; ?>" class="search-url"><?php echo $row['url']; ?></a> - <?php echo $row['content']; ?></p>
                    <hr>
                <?php
                }
                ?>
            </div>
        </div>


    </div>
</main>