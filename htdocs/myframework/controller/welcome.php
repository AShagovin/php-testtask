<?php defined('MYFRAMEWORK') or die('No direct script access.');

class Controller_Welcome extends Controller{

    public function action_index(){        
                
        $this->_response = 
                view::factory('main', array(
                    'username' => $this->_options['username'],
                    'uid' => $this->_uid
                ))
                ->render();
    }
}

