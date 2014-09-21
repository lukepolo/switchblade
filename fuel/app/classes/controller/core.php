<?php
class Controller_Core extends Controller_Template
{
    public $public_classes = array(
        'action_404',
    );
    public function action_404()
    {
        $this->template->content = View::Forge('core/404');
    }
}
