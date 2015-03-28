@extends('layouts.private.template')

@section('content')
    <h1>
        {{ $bug->name }} <small> {{ $bug->message }} @ {{ $bug->lineNumber }}</small>
          <p class="pull-right bug-js">
            {{ $bug->type }}
        </p>
        <p>
            <small>{{ $bug->url }}</small>
        </p>
    </h1>
    <h2>
        Browsers Affected
        <div>
            @foreach($bug->browsers as $browser)
                {{ $browser->browser }}<br>
                <small>
                    @foreach($browser->versions as $version)
                        {{ $version->version }}
                    @endforeach
                </small>
            @endforeach
        </div>
    </h2>  
    <h2>
        History : <br>
        <small>
            <p>Occurences {{ $bug->history->occurences }}</p>
            <p>Unique Users {{ $bug->history->unique_users }}</p>
            <p>First Seen {{ $bug->history->created_at->diffForHumans() }}</p>
            <p>Last Seen {{ $bug->history->updated_at->diffForHumans() }}</p>
        </small>
    </h2>
    <hr>
    <h2>Stack Trace</h2>
    <pre>
        <code>
            {{ $bug->stacktrace }}
        </code>
    </pre>
@stop