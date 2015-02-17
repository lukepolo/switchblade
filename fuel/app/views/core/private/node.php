<?php
    if(Auth::Check())
    {
    ?>
        <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
        <script type="text/javascript">
            <?php
                if(Fuel::$env == 'development')
                {
                ?>
                    if (localStorage.debug != 'socket.io-client:socket')
                    {
                        console.log('You must reload to see socket.io messages!');
                        localStorage.debug='socket.io-client:socket';
                    }

                <?php
                }
            ?>
            try {
                var socket = io.connect('<?php echo rtrim(Uri::Base(),'/'); ?>:7777');
                var user_data = {
                    name: '<?php echo $first_name; ?>',
                    id: '<?php echo \Auth::get_user_id()[1]; ?>',
                }

                socket.emit('user_info', user_data);

                socket.on('pull', function(data)
                {
                    console.log('PULLING');
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