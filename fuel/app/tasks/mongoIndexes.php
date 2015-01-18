<?php
namespace Fuel\Tasks;

class Mongo
{

    public function createIndexes()
    {
        $mongodb->add_index('user_domains', array(
            'domain' => 1,
            'user_id' => 1
        ), array(
            'unique' => true
        ));
    }
}
