<?php

namespace ABSplit;
// This may Change but for now Hybrid sounds good
class Controller_Experiment extends \Controller_Hybrid
{
    public $template = 'private';
    public function action_new()
    {
        if(\Input::Post())
        {
            $experiment = \Model_Absplit_Experiment::Forge(array(
                'user_id' => \Auth::get('id'),
                'url' => \Input::post('url'),
                'absplit_experiment_type_id' => 1,
                'active' => false,
                'absplit_experiment_data_id' => 1
            ));
            
            try
            {
                $experiment->save();
                \Response::redirect(\Uri::Create('absplit/editor/'.$experiment->id));
            }
            catch(\Exception $e)
            {
                \Session::set_flash('error', 'The experiment failed to save, please contact customer support '.\Settings::get('helpdesk'));
                \Response::redirect_back();
            }
        }
    }
    
    public function action_search($limit = 10)
    {
        $order_by = \Input::Get('columns.'.\Input::Get('order.0.column').'.data');
        if($order_by == 'confidence')
        {
            $order_by = 'url';
        }
        $total_count = \Model_Absplit_Experiment::query()
            ->where('user_id', \Auth::get('id'));
        
        if(\Input::Get('search.value'))
        {
            $total_count = $total_count->where('url', 'LIKE', '%'.\Input::Get('search.value').'%');
        }

        $total_count = $total_count->count();
            
        $experiments = \Model_Absplit_Experiment::query()
            ->where('user_id', \Auth::get('id'));
        if(\Input::Get('search.value'))
        {
            $experiments = $experiments->where('url', 'LIKE', '%'.\Input::Get('search.value').'%');
        }
        $experiments = $experiments->rows_offset(\Input::Get('start'))
            ->rows_limit($limit)
            ->order_by($order_by, \Input::Get('order.0.dir'))
            ->get();

        foreach($experiments as $experiment)
        {
            $experiment->url = \Html::anchor(\Uri::Create('absplit/editor/'.$experiment->id), $experiment->url);
            
            // TODO - P-VALUE TEST
            $experiment->confidence = 'NA';
            
            if($experiment->active == false)
            {
                $experiment->active = false;
            }
            
            // We need to format the HTML correctly here
            $experiment->active = 
                '<label class="input"> 
                    <label class="toggle">'.
                        \Form::input('active', "false", array('type' => 'hidden')).
                        \Form::checkbox('active', $experiment->active, $experiment->active, array('toggle' => true)).
                    '</label>
                </label>';
        }
        
        $data = new \stdClass;

        $data->data = $experiments;
        $data->iTotalRecords = $limit;
        $data->iTotalDisplayRecords = $total_count;

        return $this->response($data);
    }
}