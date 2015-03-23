<?php

namespace Modules\Tracer\Services;

use Modules\Tracer\Models\Mongo\Bug;
use Modules\Tracer\Models\Mongo\BugBrowser;
use Modules\Tracer\Models\Mongo\BugHistory;

use Modules\Tracer\Models\Mongo\BugUser;
use Modules\Tracer\Models\Mongo\BugUserHistory;


class Tracer
{
    public function store($error)
    {
        
        \Agent::setUserAgent($error['userAgent']);
        
        $bug = Bug::with(['browser' => function($query)
            {
                $browser =  \Agent::browser();
                $query->where('browser', '=', $browser)
                    ->where('version', '=', \Agent::version($browser));
            }])
            ->with('history')
            ->where('stacktrace', '=', $error['stacktrace'])
            ->first();
        
        if(empty($bug) === true)
        {
            $bug = Tracer::createBug($error);
        }
        else
        {
            if($bug->browser->count() == 0)
            {
                Tracer::createBugBrowser($bug->id);
            }
          
            $user = BugUser::where('ip', '=', \Request::getClientIp(true))
                ->first();
            
            if(empty($user) === true)
            {
                Tracer::createBugUser($bug->id);
                $bug->history->increment('unique_users');
            }
            else
            {
                Tracer::createBugUserHistory($user->id, $bug->id);
            }
            
            // Increase number of occurences
            $bug->history->increment('occurences');
            
            $bug->history->save();
        }
    }
    
    public function createBug($error)
    {
        if(\Auth::check())
        {
            $user = \Auth::user();
        }
        else
        {
            $user = \App::make('user');
        }
        
        $domain = \Domains::get($error['projectRoot']);
        
        $bug = Bug::create([
            'user_id' => $user->id, 
            'domain_id' => $domain->id,
            'type' => $error['type'],
            'url' => $error['url'],
            'severity' => $error['severity'],
            'name' => $error['name'],
            'message' => $error['message'],
            'file' => $error['file'],
            'lineNumber' => $error['lineNumber'],
            'stacktrace' => $error['stacktrace'],
        ]);
        
        Tracer::createBugBrowser($bug->id);
        Tracer::createBugHistory($bug->id);
        Tracer::createBugUser($bug->id);
    }
    
    public function createBugBrowser($bug_id)
    {
        $browser =  \Agent::browser();
        $bug_affected_browsers = BugBrowser::create([
            'bug_id' => $bug_id,
            'browser' => $browser,
            'version' => \Agent::version($browser),
        ]);
    }
    
    public function createBugHistory($bug_id)
    {
        $bug_history = BugHistory::create([
            'bug_id' => $bug_id,
            'occurences' => 1,
            'unique_users' => 1
        ]);
    }
    
    public function createBugUser($bug_id)
    {
        $bug_user =  BugUser::create([
            'ip' => \Request::getClientIp(true),
        ]);
        
        Tracer::createBugUserHistory($bug_user->id, $bug_id);
    }
    
    public function createBugUserHistory($bug_user_id, $bug_id)
    {
        $bug_user_history = BugUserHistory::create([
            'user_id' => $bug_user_id,
            'bug_id' => $bug_id
        ]);
    }
}