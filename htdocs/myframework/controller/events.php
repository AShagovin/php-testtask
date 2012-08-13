<?php defined('MYFRAMEWORK') or die('No direct script access.');

class Controller_Events extends controller{
    
    public function action_get(){
        
    
        $lec = session::get('last_events_check');                    

        //юзеров и обновлений не много - просто смотрим менялось что то или нет.
        //если менялось, то просто загрузим полностью список постов.

        $sql = 'SELECT COUNT(id) as count, NOW() as now  FROM events WHERE action != \'draft\' AND event_on >= \''.$lec.'\'';
        
        $count = events::find_by_sql($sql);

        session::set('last_events_check', $count[0]->now );

        //echo $sql; die;
        if( $count[0]->count > 0 ){
            $this->_response = controller::execute('/posts/list');
            return;
        }

        $this->_response = json_encode(array('_ok' => 1));
    }
}

