<div class="col-sm-12 col-md-12 col-lg-12">
    <div class="well well-light well-sm no-margin no-padding">
        <div class="row">
            <div class="col-sm-3 profile-pic" style="cursor: pointer">
                <input id="profile_picture" type="file" style="visibility: hidden;"><?php echo \Html::img($user_image, array('class' => 'select_picture')); ?>
            </div>
            <div class="col-sm-6">
                <h1>
                    <?php echo $first_name; ?> <span class="semi-bold"><?php echo $last_name; ?></span> <small id="edit-profile" style="cursor: pointer;"><a href="#">edit</a></small>
                </h1>
                <ul class="list-unstyled">
                    <li>
                        <p class="text-muted">
                            <i class="fa fa-envelope"></i>&nbsp;&nbsp;<?php echo Auth::get('email'); ?>
                        </p>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row" style="margin:10px;display: none;" id="update_profile_fields">
            <?php
                echo \Form::open(array(
                    'method' => 'POST',
                    'class' => 'smart-form client-form',
                    'id' => 'profile-form'
                ));
            ?>    
                <fieldset>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-envelope"></i>
                            <?php echo \Form::input('email', Auth::get('email'));?>
                            <b class="tooltip tooltip-bottom-right">Enter Email Address</b>
                        </label>
                    </section>
                     <section>
                        <label class="input"> 
                            <i class="icon-append fa fa-lock"></i>
                            <?php echo \Form::password('current_password');?>
                            <b class="tooltip tooltip-bottom-right">Current Password</b>
                        </label>
                    </section>
                    <section>
                        <label class="input"> 
                            <i class="icon-append fa fa-lock"></i>
                            <?php echo \Form::password('new_password');?>
                            <b class="tooltip tooltip-bottom-right">Change Your Password</b>
                        </label>
                    </section>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <section class="col col-6">
                            <label class="input">
                                <?php echo \Form::input('first_name', $first_name);?>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="input">
                                <?php echo \Form::input('last_name', $last_name);?>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label class="select">
                                <?php echo \Form::select('gender', Auth::get('gender'), array('male' => 'Male', 'female' => 'Female', 'na' => 'Prefer Not To Answer')); ?>
                            </label>
                        </section>
                    </div>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">
                            Update Profile
                    </button>
                </footer>
            <?php echo Form::close(); ?>
        </div>
        <?php
            if(empty($payments) === false)
            {
            ?>
                <div class="row" style="margin:10px;">
                    <hr>
                    <h3>Payments</h3>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Order Date</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <?php
                                foreach($payments as $payment)
                                {
                                ?>
                                    <tr>
                                        <td><?php echo $payment->id; ?></td>
                                        <td><?php echo $payment->product->name; ?></td>
                                        <td><?php echo $payment->product->description; ?></td>
                                        <td><?php echo money_format('%.2n', $payment->amount / 100); ?></td>
                                        <td><?php echo \Date::forge($payment->created_at); ?></td>
                                        <td>
                                            <?php
                                                if($payment->refund == false)
                                                {
                                                ?>
                                                    <a href="<?php echo Uri::Create('payments/refund/'.$payment->id); ?>">Start Refund</a>
                                                <?php
                                                }
                                                else
                                                {
                                                ?>
                                                    Refunded
                                                <?php
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <hr>
                </div>
            <?php
            }
        ?>
        <div class="row" style="margin:10px;">
            <h2>Javascript Code <small>Copy and paste directly after your &lthead> tag </small></h2>
            <pre class="prettyprint">
<code class="language-js">
&lt;script type="text/javascript">
    (function(g,c,e,f,a,b,d){c.getElementsByTagName("html")[0].style.visibility="hidden";window[a]=function(){window[a].q.push(arguments)};window[a].q=[];window[a].t=+new Date;b=c.createElement(e);d=c.getElementsByTagName(e)[0];b.async=1;b.src=f;d.parentNode.insertBefore(b,d)})(window,document,"script","//luke.switchblade.io/assets/js/blade.js","swb");
    swb('auth','<?php echo Auth::get('apikey'); ?>');
    swb('get_mods');    
&lt;/script>
</code>
            </pre>
        </div>

    </div>
</div>
<script>
    var image;
    $(document).ready(function()
    {
        $('#edit-profile').click(function()
        {
           $('#update_profile_fields').slideToggle();
        });
        $('.select_picture').click(function()
        {
           $('#profile_picture').click();
        });

        $("#profile_picture").change(function()
        {
            image = this;
            data= new FormData();
            data.append("image", image.files[0]);
             
            $.ajax({
                url: '<?php echo Uri::Create('profile/update_img'); ?>',
                type : 'post',
                data: data,
                processData: false,
                contentType: false
            }).done(function(result)
            {
                result = JSON.parse(result);
                if($.type(result.message) == 'string')
                {
                    $.smallBox({
                        title : 'Error', 
                        content : result.message,
                        icon : 'fa fa-warning swing animated',
                        color : '#C46A69'
                    });
                }
                else
                {
                    readURL(image);
                }
            });
        });
        
        $("#profile-form").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                        email: true
                },
                current_password: {
                    required: function(element)
                    {
                        if($('#form_new_password').val())
                        {
                            return true; 
                        }
                        else
                        {
                            return false;
                        }
                    }
                },
                new_password: {
                    required: function(element)
                    {
                        if($('#form_current_password').val())
                        {
                            return true; 
                        }
                        else
                        {
                            return false;
                        }
                    }
                },
                gender: "required",
            },
            messages: {
                first_name: "Please enter your firstname",
                current_password: "You must enter your current password",
                new_password: "You must enter a new password",
                last_name: "Please enter your lastname",
                email: "Please enter a valid email address",
                gender: "Please choose your gender",
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
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
                $('.select_picture').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>