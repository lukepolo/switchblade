<?php
namespace Fuel\Tasks;

class System
{
    public function optimize_tables()
    {
        foreach(\DB::list_tables() as $table_name)
        {
            // Logger says it fails but it really doesn't
            // http://stackoverflow.com/questions/3855489/cant-optimize-innodb-table
            \DBUtil::optimize_table($table_name);
        }
    }
    
    public function repair_tables()
    {
        foreach(\DB::list_tables() as $table_name)
        {
            // Logger says it fails but it really doesn't
            // http://stackoverflow.com/questions/3855489/cant-optimize-innodb-table
            \DBUtil::repair_table($table_name);
        }
    }
    
    public function add_index()
    {
    }
}