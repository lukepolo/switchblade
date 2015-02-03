<?php

class Settings
{
    public static $settings = array();
    
    static function get($option)
    {
        if(empty(static::$settings) === true)
        {
            Settings::get_all();
        }
        
        if (key_exists($option, static::$settings))
        {
            return static::$settings[$option]->data;
        }
    }
    
    static function get_all($refresh = false)
    {
        if(empty(static::$settings) === true || $refresh === true)
        {
            if($refresh || Fuel::$env == 'development')
            {
                Cache::delete('settings');
            }
            
            // try to retrieve the cache and save to $content var
            try
            {
                static::$settings = Cache::get('settings');
            }
            catch (\CacheNotFoundException $e)
            {
                try
                {
                    $settings = Model_Setting::query()
                        ->get();

                    $get_settings = array();

                    if(empty($settings) === false)
                    {
                        foreach($settings as $setting)
                        {
                            $get_settings[$setting->name] = $setting;
                        }
                    }

                    static::$settings = $get_settings;

                    Cache::set('settings', static::$settings);
                }
                catch(\Exception $e)
                {
                    Migrate::current('default', 'app');
                }
            }
        }
        
        return static::$settings;
    }
    
    static function pagination($total_count, $per_page = 25)
    {
        $config = array(
            'total_items'    => $total_count,
            'per_page'       => $per_page,
            'uri_segment'    => 3,
            'uri_segment'    => 'page',
        );
        
        // Create a pagination instance named 'mypagination'
        return Pagination::forge('pagination', $config);
    }
    
    
    static function time_ago($tm)
    {
        $rcs = 0;
        $cur_tm = time(); $dif = $cur_tm-$tm;
        $pds = array('second','minute','hour','day','week','month','year','decade');
        $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
        for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
     
        $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
        if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
        return $x .' ago';
    }
}
