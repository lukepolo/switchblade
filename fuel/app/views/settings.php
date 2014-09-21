<div class="smart-form">
    <section class="col col-5">
        <h1>Edit Site Settings</h1>
        <?php
            echo \Form::open(array(
                'method' => 'POST',
                'class' => 'smart-form client-form',
                'id' => 'register-form'
            ));
            foreach($settings as $setting)
            {
                switch($setting->type)
                {
                    case 'varchar':
                        echo \Form::label(ucwords(str_replace('_',' ', $setting->name)));
                        echo \Form::input($setting->name, $setting->data);
                    break;
                    case 'boolean': 
                    ?>
                        <label class="toggle">
                            <?php 
                                echo \Form::input($setting->name, "false", array('type' => 'hidden'));
                                
                                if($setting->data == 'false')
                                {
                                    $setting->data = false;
                                }
                                echo \Form::checkbox($setting->name, $setting->data, $setting->data, array('toggle' => true));
                                echo ucwords($setting->name);
                            ;?>
                        </label>
                    <?php
                    break;
                }
            }
            echo \Form::button('Update Settings', null, array('class' => 'btn btn-primary'));
            echo \Form::close();
        ?>
    </section>
</div>
