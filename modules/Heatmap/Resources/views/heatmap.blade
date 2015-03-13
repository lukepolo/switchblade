<?php
echo Asset::css('modules/absplit/main.css');
    Casset::js('heatmap.min.js');
?>
<div id="img" style="max-width: 1024px">
    <img src="<?php echo Uri::Create('assets/img/screenshots/'. $url['image_path']); ?>">
</div>
<script>
    var heatmap_data = <?php echo json_encode($heatmap); ?>;
    var heatmapInstance;
    var img_loaded = false;
    $('#img img').load(function()
    {
        img_loaded = true;
    });
    $(document).ready(function()
    {
       if(img_loaded)
       {
           render_heatmap();
       }
       else
       {
           $('#img img').load(function()
            {
                render_heatmap();
            });
       }
    });

    function render_heatmap()
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
                var point_x = ($('#img img').width() / 2) - (point.width / 2 - point.x * (($('#img img').width() / 2) / (point.width / 2)))

                heatmapInstance.addData({
                    x: point_x,
                    y: point.y,
                    value: count++
                });
            });

        });
        // TODO - add Pree - Loading Screen
    }
</script>