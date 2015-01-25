<?php
echo Asset::css('modules/absplit/main.css'); 
    Casset::js('heatmap.min.js');
?>
<div id="img" style="max-width: 1024px">
    <img src="<?php echo Uri::Create('assets/img/screenshots/'. $url['image_path'] .'.jpg'); ?>">
</div>
<script>
    var heatmap_data = <?php echo json_encode($heatmap); ?>;
    var heatmapInstance;
    $(document).ready(function()
    {
        heatmapInstance = h337.create({
            container: $('#img')[0],
            radius: 15
        });

        $(heatmap_data).each(function(index, heatmap_object)
        {   
            var count = 1;
            $(heatmap_object.data).each(function(index, point)
            {
                heatmapInstance.addData({
                    x: point.x,
                    y: point.y,
                    value: count++
                });
            });
        });
    });
</script>