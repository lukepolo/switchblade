Screen Shots Library

<div class="screenshots">
    <?php
        foreach($screenshots as $screenshot)
        {
        ?>
            <div class="col-lg-1 crop">
                <img class="img-responsive" src="<?php echo Uri::Create('assets/img/screenshots/'.$screenshot['image_path']); ?>.jpg">
            </div>
        <?php
        }
    ?>
</div>

<style>
    .screenshots img {
        margin-top:15px;
    }
    .crop {
        height: 100px;
        overflow:hidden;
    }
</style>