@extends('layouts.private.template')

@section('content')
    <table class="table">
	<thead>
	  <tr>
	    <th>Error</th>
	    <th>Occurrences</th>
	    <th>Unquie Users</th>
	    <th>Severity</th>
	  </tr>
	</thead>
    <tbody>
	@foreach ($bugs as $bug)
	    <tr class="bug-{{ $bug->type }}">
		<td>
		    <p>{{ $bug->name }}</p>
		    <p>{{ $bug->message }}</p>
		    <small>{{ $bug->history->updated_at->diffForHumans() }} - {{ $bug->history->created_at->diffForHumans() }}</small>
		</td>
		<td>{{ $bug->history->occurences }}</td>
		<td>{{ $bug->history->unique_users }}</td>
		<td>{{ $bug->severity }}</td>
	    </tr>
        @endforeach
    </tbody>
  </table>
@stop