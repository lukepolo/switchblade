<?php

namespace Modules\Tracer\Services;

use Modules\Tracer\Models\Mongo\Bug;
use Modules\Tracer\Models\Mongo\BugBrowser;
use Modules\Tracer\Models\Mongo\BugBrowserVersion;
use Modules\Tracer\Models\Mongo\BugHistory;

use Modules\Tracer\Models\Mongo\BugUser;
use Modules\Tracer\Models\Mongo\BugUserHistory;


class Tracer
{
    public function store($error)
    {
        
        \Agent::setUserAgent($error['userAgent']);
        
        $bug = Bug::with(['browsers' => function($query)
            {
                $query->where('browser', '=',  \Agent::browser())->with(['versions' => function($query)
                {
                    $query->where('version', '=', \Agent::version(\Agent::browser()));
                }]);
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
            if($bug->browsers->count() == 0)
            {
                Tracer::createBugBrowser($bug->id);
            }
            elseif($bug->browsers[0]->versions->count() == 0)
            {
                Tracer::createBugBrowserVersion($bug->browsers[0]->id, \Agent::version(\Agent::browser()));
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
        $bug_browser = BugBrowser::create([
            'bug_id' => $bug_id,
            'browser' => \Agent::browser(),
        ]);
        
        Tracer::createBugBrowserVersion($bug_browser->id, \Agent::version(\Agent::browser()));
    }
    
    public function createBugBrowserVersion($browser_id, $version)
    {
        $bug_browser = BugBrowserVersion::create([
            'bug_browser_id' => $browser_id,
            'version' => $version
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
