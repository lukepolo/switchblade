<?php
namespace Fuel\Tasks;

class System
{
    public function optimize_tables()
    {
        foreach(\DB::list_tables() as $table_name)
        {
            \Cli::write($table_name);
            if(\DBUtil::optimize_table($table_name) === false)
            {
                \Cli::write('Well lets try to repiar it');
                // Do something
            }
        }
    }
    
    public function repair_tables()
    {
       
    }
}