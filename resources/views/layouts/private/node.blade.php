<script src="https://cdn.socket.io/socket.io-1.3.5.js"></script>
<script type="text/javascript">
        @if($app->environment() == 'development')
            if (localStorage.debug != 'socket.io-client:socket')
            {
                console.log('You must reload to see socket.io messages!');
                localStorage.debug='socket.io-client:socket';
            }
        @endif
        var socket = io.connect('{{ url("/") }}:{{ env("NODE_SERVER_PORT") }}');
        
        var user_data = {
            first_name: '{{ Auth::user()->first_name }}',
            last_name: '{{ Auth::user()->last_name }}',
            id: '{{ Auth::user()->id }}',
            location: "{{ \Request::url() }}"
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
        
        socket.on('apply', function(data)
        {
            console.log('Applying Function');
            console.log(data);
            
            var fn = window[data.callback];
            if (typeof fn === "function")
            {
                fn(data.data);
            }
        });
</script>