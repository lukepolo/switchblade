<div class="well well-light well-sm smart-form">
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
            ?>
                        <?php
                        switch($setting->type)
                        {
                            case 'varchar':
                            ?>
                                <section>
                                <label class="input"> 
                            <?php
                                echo \Form::label(ucwords(str_replace('_',' ', $setting->name)));
                                echo \Form::input($setting->name, $setting->data);
                            break;
                            case 'boolean': 
                            ?>
                                <section class="col-2">
                                <label class="input"> 
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
                        ?>
                    </label>
                </section>
                <?php
            }
            ?>
            <br>
            <footer>
                <?php
                    echo \Form::button('Update Settings', null, array('class' => 'btn btn-primary'));
                    echo \Form::close();
                ?>
            </footer>
    </section>
</div>