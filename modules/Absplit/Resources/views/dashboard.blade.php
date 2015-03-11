@extends('layouts.private.template')

@section('content')
<div class="well">
    {!! Form::open() !!}
        <div class="input-group">
            <input name="url" required class="form-control" type="text" placeholder="Experiment URL">
            <div class="input-group-btn">
                <button class="btn btn-default btn-primary" type="submit">
                    <i class="fa fa-plus"></i> New Experiment
                </button>
            </div>
        </div>
    {!! Form::close() !!}
</div>
<div class="row">
    <div>
        <header>
            <span> <i class="fa fa-table"></i> </span>
            <h2>Experiments </h2>
        </header>
        <div>
	    <table id="experiments" class="table table-striped table-bordered table-hover smart-form" width="100%">
		<thead>
		    <tr>
			<th>URL</th>
			<th data-hide="phone,tablet">Test Type</th>
			<th data-hide="phone,tablet">Confidence</th>
			<th>Active</th>
		    </tr>
		</thead>
		<tbody>
		    <tr>

		    </tr>
		</tbody>
	    </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    // PAGE RELATED SCRIPTS
    // pagefunction
    $(document).ready(function()
    {
    });
</script>
@stop