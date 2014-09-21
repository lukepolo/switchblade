<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
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
                                <label class="select">
                                    <?php echo \Form::select('gender', null, array('male' => 'Male', 'female' => 'Female', 'na' => 'Prefer Not To Answer')); ?>
                                </label>
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
                    <div class="message">
                        <i class="fa fa-check"></i>
                        <p>
                            Thank you for your registration!
                        </p>
                    </div>
            </form>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
        <div class="well no-padding">
            <?php
                echo \Form::open(array(
                    'action' => Uri::Create('login'),
                    'method' => 'POST',
                    'class' => 'smart-form client-form'
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
                            <?php echo \Form::input('email'); ?>
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