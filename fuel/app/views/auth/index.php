<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8">
        <div class="well no-padding">
                <?php
                    echo \Form::open(array(
                        'action' => Uri::Create('auth/register'),
                        'method' => 'POST',
                        'class' => 'smart-form client-form',
                        'id' => 'register-form'
                    ));
                ?>    
                <header>
                    Register
                </header>
                <fieldset>
                    <section>
                        <label class="input"> 
                            <i class="icon-append fa fa-user"></i>
                            <?php echo \Form::input('username');?>
                            <b class="tooltip tooltip-bottom-right">Enter Username</b> 
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-envelope"></i>
                            <?php echo \Form::input('email');?>
                            <b class="tooltip tooltip-bottom-right">Enter Email Address</b>
                        </label>
                    </section>
                    <section>
                        <label class="input"> 
                            <i class="icon-append fa fa-lock"></i>
                            <?php echo \Form::password('password');?>
                            <b class="tooltip tooltip-bottom-right">Enter Your Password</b>
                        </label>
                    </section>
                    <section>
                        <label class="input">
                            <i class="icon-append fa fa-lock"></i>
                            <?php echo \Form::password('confirm_password');?>
                            <b class="tooltip tooltip-bottom-right">Verify Your Password</b> 
                        </label>
                    </section>
                </fieldset>
                <fieldset>
                    <div class="row">
                        <section class="col col-6">
                            <label class="input">
                                <?php echo \Form::input('first_name');?>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="input">
                                <?php echo \Form::input('last_name');?>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default">
                                    Male<?php echo \Form::radio('male', 'gender');?>
                                </label>
                                <label class="btn btn-default">
                                    Female<?php echo \Form::radio('female', 'gender');?>
                                </label>
                                <label class="btn btn-default">
                                    Other<?php echo \Form::radio('other', 'gender');?>
                                </label>
                            </div>
                        </section>
                    </div>
                    <section>
                            <label class="checkbox">
                                <?php echo \Form::checkbox('terms'); ?>
                                I agree with the <a href="#" data-toggle="modal" data-target="#myModal"> Terms and Conditions </a>
                            </label>
                    </section>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">
                            Register
                    </button>
                </footer>
            <?php echo Form::close(); ?>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
        <div class="well no-padding">
            <?php
                echo \Form::open(array(
                    'action' => Uri::Create('login'),
                    'method' => 'POST',
                    'class' => 'smart-form client-form',
                    'id' => 'login-form'
                ));
            ?>    
                <header>
                    Sign In
                </header>
                <fieldset>
                    <section>
                        <?php echo \Form::label('Username / Email', 'email'); ?>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <?php echo \Form::input('email', \Session::get('login_username')); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter your username / email address</b>
                        </label>
                    </section>
                    <section>
                        <label class="label">Password</label>
                        <label class="input"> <i class="icon-append fa fa-lock"></i>
                            <?php echo \Form::password('password'); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
                        <div class="note">
                            <?php echo \Html::anchor(Uri::Create('auth/forgot'), 'Forgot password?'); ?>
                        </div>
                    </section>
                    <section>
                        <label class="checkbox">
                            <?php echo \Form::checkbox('remember', 'Remember', true); ?>
                            Stay signed in
                        </label>
                    </section>
                </fieldset>
                <footer>
                    <?php echo \Form::button('Sign In', null, array('class' => 'btn btn-primary')); ?> 
                </footer>
            <?php echo \Form::close(); ?>
        </div>
        <h5 class="text-center"> - Or sign in using -</h5>
        <ul class="list-inline text-center">
            <li>
                <a href="<?php echo Uri::Create('auth/register/google'); ?>" class="btn btn-primary btn-circle"><i class="fa fa-google"></i></a>
            </li>
            <li>
                <a href="<?php echo Uri::Create('auth/register/twitter'); ?>" class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></a>
            </li>
        </ul>
    </div>
</div>
<script>
    $(document).ready(function()
    {
        $("#register-form").validate({
            rules: {
                username:{
                    required: true,
                    remote: {
                        url: "<?php echo Uri::Create('auth/check/username'); ?>"
                    }
                },
                first_name: "required",
                last_name: "required",
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo Uri::Create('auth/check/email'); ?>"
                    }
                },
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 1,
                    equalTo: "#form_password",
                },
                gender: "required",
                terms: "required"
            },
            messages: {
                username: {
                    required: "Please enter a valid username",
                    remote: "Username already in use"
                },
                first_name: "Please enter your firstname",
                last_name: "Please enter your lastname",
                password: {
                    required: "Please provide a password",
                    minlength: "Your password must be at least 5 characters long"
                },
                confirm_password: {
                    required: "Passwords much match",
                    minlength: "Your password must be at least 5 characters long",
                },
                email: {
                    required: "Please enter a valid email address",
                    remote: "Email address already in use"
                },
                gender: "Please choose your gender",
                terms: "<br>Please accept our policy"
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
        
        $("#login-form").validate({
            rules: {
                email: "required",
                password: {
                    required: true,
                }
            },
            messages: {
                email: "Please enter a valid username / email address",
                password: {
                    required: "Please provide a password",
                }
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>
							