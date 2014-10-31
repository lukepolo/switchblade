<?php
class Node
{
    // EXAMPLE : Node::php_to_node('append', 16, 'activity', '<p>NODE TEST</p>', 'mentioned');
    public static function php_to_node($node_function, $user_ids, $element, $html, $callback)
    {
        try 
        {
            $elephant = new ElephantIO\Client(new ElephantIO\Engine\SocketIO\Version1X(rtrim(Uri::Base(),'/').':7777'));

            $elephant->initialize();

            $elephant->emit($node_function, array($user_ids, $element, $html, $callback));

            $elephant->close();
        }
        catch (Exception $ex)
        {
            // TODO - PUT TO LOG FILE
        }
    }
}