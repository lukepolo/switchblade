<?php
    if(Auth::Check())
    {
    ?>
    	<script type="text/javascript" src="<?php echo rtrim(Uri::Base(),'/'); ?>:7777/socket.io/socket.io.js"></script>
        <script type="text/javascript">
            try {
                var socket = io.connect('<?php echo rtrim(Uri::Base(),'/'); ?>:7777');
                var user_data = {
                    name: '<?php echo $first_name; ?>',
                    id: '<?php echo Auth::get('id'); ?>',
                }
                
                socket.emit('user_info', user_data);
    
                socket.on('pull', function(data)
                {
                    console.log('PULLED');
                    console.log(data);
    
                    $('div[data-id="' + data.element + '"]').prepend(data.html);
    
                    if (data.callback)
                    {
                        var fn = window[data.callback];
                        if (typeof fn === "function")
                        {
                            fn(data);
                        }
                    }
                });
            }
            catch(e)
            {
                console.log('Node is down!');
            }
        </script>
    <?php
    }
?>