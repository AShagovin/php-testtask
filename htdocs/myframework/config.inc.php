<?php defined('MYFRAMEWORK') or die('No direct script access.');

return array(
    'debug' => true,
    'database' => array(
        'development' => 'mysql://test:test@127.0.0.1/demchenkoe',
        ),
    'default_controller' => 'welcome',
    'default_action'     => 'index',
);
