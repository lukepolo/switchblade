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
        <div style="margin:10px;display: none;" id="update_profile_fields">
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