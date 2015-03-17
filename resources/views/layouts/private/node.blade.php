<script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
<script type="text/javascript">
        @if($app->environment() == 'development')
            if (localStorage.debug != 'socket.io-client:socket')
            {
                console.log('You must reload to see socket.io messages!');
                localStorage.debug='socket.io-client:socket';
            }
        @endif
    try {
//        var socket = io.connect('{{ url("/") }}:7777');
        var user_data = {
            name: '{{ Auth::user()->first_name }}',
            id: '{{ Auth::user()->id }}',
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