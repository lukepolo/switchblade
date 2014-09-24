<?php
    if(Session::get('error'))
    {
    ?>
        <script>
            $(document).ready(function()
            {
                $.smallBox({
                    title : 'Error', 
                    content : '<?php echo Session::get('error'); ?>',
                    icon : 'fa fa-warning swing animated',
                    color : '#C46A69',
                    sound_file : 'failure'
                });
            });
        </script>
    <?php
        \Session::set('error', '');
    }
    elseif(Session::get('success'))
    {
    ?>
        <script>
            $(document).ready(function()
            {
                $.smallBox({
                    title : 'Success', 
                    content : '<?php echo Session::get('success'); ?>',
                    icon : 'fa fa-check swing animated',
                    color : '#739E73'
                });
            });
        </script>
    <?php
        \Session::set('success', '');
    }
?>