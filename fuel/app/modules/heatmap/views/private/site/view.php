<?php
echo Asset::css('modules/absplit/main.css'); 
    Casset::js('heatmap.min.js');
?>
<div id="img" style="max-width: 1024px">
    
    <img src="<?php echo Uri::Create('assets/img/screenshots/'.  urlencode($url).'.png'); ?>">
</div>
<script>
    var heatmap_data = <?php echo json_encode($heatmap); ?>;
    $(document).ready(function()
    {
        $('#img img').load(function()
        {
            var heatmapInstance = h337.create({
                container: $('#img')[0],
                radius: 60
            });

            $(heatmap_data).each(function(index, heatmap_object)
            {   
                $(heatmap_object.data).each(function(index, point)
                {
                    heatmapInstance.addData({
                        x: point.x,
                        y: point.y,
                        value: point.value
                    });
                });
            });
        });
    });
</script>