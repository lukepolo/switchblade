@extends('core/private/template')
@section('content')
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="well well-light well-sm no-margin no-padding">
            <div class="row">
                <div class="col-sm-3 profile-pic" style="cursor: pointer">
                    <input id="profile_picture" type="file" style="visibility: hidden;">
                    <img class="profile-picture" src="{{ Auth::user()->profile_img }}">
                </div>
                <div class="col-sm-6">
                    <h1>
                        {{ Auth::user()->first_name }}<span class="semi-bold"> {{ Auth::user()->last_name }}</span> <small id="edit-profile" style="cursor: pointer;"><a href="#">edit</a></small>
                    </h1>
                    <ul class="list-unstyled">
                        <li>
                            <p class="text-muted">
                                <i class="fa fa-envelope"></i>&nbsp;&nbsp;{{ Auth::user()->email }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row" style="margin:10px; display:none;" id="update_profile_fields">
                {!! Form::open(array('class' => 'col-sm-6 form-horizontal form-group-sm')) !!}
                    <div class="row col-sm-12">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="icon-append fa fa-envelope"></i>    
                                </div>
                                {!! Form::text('email', Auth::user()->email) !!}
                            </div>
                        </div>
                        @if(empty(Auth::user()->provider->id) === true)
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="icon-append fa fa-lock"></i>
                                    </div>
                                    {!! Form::password('current_password') !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="icon-append fa fa-lock"></i>
                                    </div>
                                    {!! Form::password('new_password') !!}
                                </div>
                            </div>
                         @endif
                    </div>
                    <div class="row col-sm-12">
                        <div class="form-group">
                            {!! Form::text('first_name', Auth::user()->first_name) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::text('last_name', Auth::user()->last_name) !!}
                        </div>
                    </div>
                    <div class="row col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">
                                Update Profile
                            </button>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="row" style="margin:10px;" id="subscriptions">
                <hr>
                <h3>Subscriptions</h3>
                <table class="table table-striped table-condensed">
                    <thead>
                        <th>Subscription</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Next Charge Date</th>
                        <th></th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row" style="margin:10px;" id="payments">
                <hr>
                <h3>Payments</h3>
                <table class="table table-striped table-condensed">
                    <thead>
                        <th>Order</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Charge Date</th>
                        <th></th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="row" style="margin:10px;">
                <hr>
                <h2>Javascript Code <small>Copy and paste directly after your &lthead> tag </small></h2>
                <pre class="prettyprint">
    <code class="language-js">
    &lt;script type="text/javascript">
        (function(g,c,e,f,a,b,d){window[a]=function(){window[a].q.push(arguments)};window[a].q=[];window[a].t=+new Date;b=c.createElement(e);d=c.getElementsByTagName(e)[0];b.async=1;b.src=f;d.parentNode.insertBefore(b,d)})(window,document,"script","//luke.switchblade.io/assets/js/blade.js","swb");
        swb('auth','{{ Auth::user()->api_key }}');
        swb('get_mods');    
    &lt;/script></code>
                </pre>
            </div>
        </div>
    </div>
    <script>
        var image;
        $(document).ready(function()
        {
            prettyPrint();
            
            $('#edit-profile').click(function()
            {
               $('#update_profile_fields').slideToggle();
            });
            $('.profile-picture').click(function()
            {
               $('#profile_picture').click();
            });

            $("#profile_picture").change(function()
            {
                image = this;
                data= new FormData();
                data.append("file", image.files[0]);

                $.ajax({
                    url: '{{ url('profile/image') }}',
                    type : 'post',
                    data: data,
                    processData: false,
                    contentType: false,
                    dataType: 'json'
                }).success(function(result)
                {
                    readURL(image);
                }).error(function(error)
                {
                    error = JSON.parse(error.responseText);
                    alert(error.Error);
                });
            });
            
            $.ajax({
                url: '{{ url('payment/invoices') }}',
                type: 'GET',
                success: function(invoices)
                {
                    $.each(invoices, function(invoice_id, invoice)
                    {
                        if(invoice.refunded)
                        {
                            var refund_text = 'Refunded';
                        }
                        else
                        {
                            var refund_text = '<a href="#' + invoice_id + '">Start Refund</a>';
                        }
                        
                        // Show all the invoice items
                        $.each(invoice.items, function(index, item)
                        {
                            plan = item.plan;
                        });
                        
                        $('#payments table tbody').append('\
                        <tr>\
                            <td>' + invoice_id + '</td>\
                            <td>' + ucwords(plan) + '</td>\
                            <td>$' + invoice.dollars + '</td>\
                            <td>' + invoice.date + '</td>\
                            <td>' + refund_text + '</td>\
                        </tr>')
                    });
                }
            });
        });

        function readURL(input)
        {
            if (input.files && input.files[0]) 
            {
                var reader = new FileReader();

                reader.onload = function (e) 
                {
                    $('.profile-picture').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    {!! Form::open(array('url' => url('payment/subscribe'))) !!}
        <script
            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
            data-key="pk_test_CnzLQclPLDvRDirFJPVKMYQl"
            data-amount="2000"
            data-name="Demo Site"
            data-description="2 widgets ($20.00)"
            data-image="https://lh6.googleusercontent.com/-P0TiaIJ9Mek/AAAAAAAAAAI/AAAAAAAAJs4/NJKMzC7iZg8/photo.jpg?sz=50">
        </script>
    {!! Form::close() !!}
</form>
@stop