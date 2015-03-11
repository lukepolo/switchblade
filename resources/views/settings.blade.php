@extends('core/private/template')
@section('content')
    <div class="row well well-light well-sm">
	<h1>Edit Site Settings</h1>
	{!! Form::open(array('class' => 'col-sm-6 form-horizontal form-group-sm')) !!}
	    @foreach($settings as $setting)
		<div class="form-group">
		    <?php
			switch($setting->type)
			{
			    case 'varchar':
				echo Form::label(ucwords(str_replace('_',' ', $setting->name), array('class' => 'col-sm-2')));
				echo Form::text($setting->name, $setting->data);
			    break;
			    case 'boolean':
				?>
				    <div class="col-sm-offset-2 col-sm-10">
					<div class="checkbox">
					    <label>
						<?php
						    echo Form::hidden($setting->id, "0", array('class' => 'col-sm-10'));
						    if($setting->data == 0)
						    {
							$setting->data = false;
						    }
						    echo Form::checkbox($setting->id, true, $setting->data);
						    echo ucwords($setting->name);
						?>
					    </label>
					</div>
				    </div>
				<?php
			    break;
			}
		    ?>
		</div>
	    @endforeach
	    <div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
		    <button type="submit" class="btn btn-success">Update</button>
		</div>
	    </div>
	{!! Form::close() !!}
    </div>
@endsection
