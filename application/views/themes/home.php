<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<main role="main" class="container">
    <div class="row">
        <div class="col-md-8 col-12 blog-main">
            <h3 class="pb-4 mb-4 font-italic border-bottom">
                <?php echo $core['content']['name']; ?>
            </h3>


            <div class="blog-post ">
                <h2 class="blog-post-title fnText" <?php echo $core['content']['data']['h1']; ?>> <?php echo $core['content']['h1']; ?> </h2>

                <div class="fnRichtext" <?php echo $core['content']['data']['content']; ?>>
                    <?php echo $core['content']['content']; ?>
                </div>

                <?php echo $core['content']['edit'] ?>
            </div><!-- /.blog-post -->

            <h4>widget</h4>
            <?php foreach ($custom['widget'] as $row) { ?>
                <div>
                    <div class="fnRichtext" <?php echo $row['data']['content']; ?>>
                        <?php echo $row['content']; ?>
                    </div>
                <?php echo $row['modal'];?>
                </div>
            <?php } ?>
            <h4>home3</h4>
            <?php foreach ($custom['home3'] as $row) { ?>
                <div>
                    <div class="fnRichtext" <?php echo $row['data']['content']; ?>>
                        <?php echo $row['content']; ?>
                    </div>
                <?php echo $row['modal'];?>
                </div>
            <?php } ?>
        </div><!-- /.blog-main -->




        <aside class="col-md-4 col-12 blog-sidebar">

            <div class="p-4">
                <h4 class="font-italic">Archives</h4>
                <ol class="list-unstyled mb-0 fnSortable">
                    <?php foreach ($core['content']['list'] as $row) { ?>

                        <li><a href="<?php echo $row['href'] ?>"><?php echo $row['name'] ?></a> <?php echo $row['action']; ?> </li>

                    <?php } ?>

                </ol>
                <?php echo $core['content']['insert']; ?>
            </div>

        </aside><!-- /.blog-sidebar -->

    </div><!-- /.row -->

</main><!-- /.container -->