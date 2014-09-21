<div class="row row-centered">
    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
        <div class="well no-padding">
            <?php
                echo \Form::open(array(
                    'method' => 'POST',
                    'class' => 'smart-form client-form',
                    'id' => 'forgot-form'
                ));
            ?>    
                <header>
                    Forgot your password, no problem!
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
                </fieldset>
                <footer>
                    <?php echo \Form::button('Find My Password!', null, array('class' => 'btn btn-primary')); ?> 
                </footer>
            <?php echo \Form::close(); ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function()
    {
        $("#forgot-form").validate({
            rules: {
                email: "required",
            },
            messages: {
                email: "Please enter a valid username / email address",
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