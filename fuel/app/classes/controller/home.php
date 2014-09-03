<?php
class Controller_Home extends Controller_Template
{
	public function action_index()
	{
	    $this->template->title = ''; 
	    $this->template->content = View::forge('home/index');
	}
}
