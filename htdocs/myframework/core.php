<?php

define('MYFRAMEWORK', '0.01');

define('CLASSES_PATH', __DIR__);
define('VENDORS_PATH', CLASSES_PATH.DIRECTORY_SEPARATOR.'vendors');
define('MODELS_PATH' , CLASSES_PATH.DIRECTORY_SEPARATOR.'model');

define('ATTACHMENTS_PATH', realpath(    __DIR__.DIRECTORY_SEPARATOR.'..'.
                                        DIRECTORY_SEPARATOR. 'attachments'
                                    ));

define('ATTACHMENTS_URL', 'http://testtask.0gx.ru/attachments/');


/**
 * config
 */

class config{
    protected static $_config = false;

    public static function get($key, $default){
        if( self::$_config === false ){
            self::$_config = include_once 'config.inc.php';

            if ( !is_array(self::$_config) )
                    die('Invalid config.inc.php format.');
        }

        if( isset( self::$_config[$key]) )
            return self::$_config[$key];
        else
            return $default;
    }
}

/**
 * session
 */

class session{

    protected static $_init = false;
    
    public static function init(){
        session_start();
        self::$_init = true;
    }

    public static function destroy(){
        session_destroy();
        self::$_init = false;
    }
    
    public static function get($key, $default = NULL){

        if( !self::$_init )
            session::init();
        
        if( isset($_SESSION[$key]) )
            return $_SESSION[$key];

        return $default;        
    }

    public static function set($key, $value){
        if( !self::$_init )
            session::init();

        $_SESSION[$key] = $value;
    }

    public static function delete($key){
        if( !self::$_init )
            session::init();

        unset($_SESSION[$key]);
    }
}

/**
 * controller
 */

class controller{    

    public static function error($errno, $errstr, $errfile, $errline){
        $str = "ERROR #$errno:$errstr\nAT: $errfile:$errline";
        error_log($str);
        if(defined('DEBUG'))
            die($str);        
        else
            die('Sorry, server internal error');
    }


    public static function execute($uri, $format = 'auto'){

        //вычислим формат в котором будем отдавать результат

        if($format === 'auto'){
            
            if( isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'xmlhttprequest'){
                //ajax запрос, отдаем в json
                $format = 'json';
            }
            else{
                $format = 'html';
            }
        }

        //разберем запрос

        $arr = array();
        //<controller>/<action>/<id>
        preg_match('/^(?:\/([\w]+)(?:\/([\w]+)(?:\/([\w]+))?)?)/', $uri, $arr);

        $options = array(
            'controller' => !empty( $arr[1] ) ? $arr[1] : config::get('default_controller', 'welcome'),
            'action'     => !empty( $arr[2] ) ? $arr[2] : config::get('default_action'    , 'index'),
            'id'         => !empty( $arr[3] ) ? $arr[3] : NULL,
            'username'   => session::get('username', false),
            'format'     => $format,
            );

        //запускам обработчик
        
        $class_name = 'Controller_'.ucfirst($options['controller']);
        $controller = new $class_name($options, $params);

        ob_start();

        if( !$controller->before() ){

            header("HTTP/1.0 403 Forbidden");
            ob_end_clean();
            return 403;
        }               

        $action_name = 'action_'.$options['action'];
        $controller->$action_name();

        if( $controller->after() ){
            
            ob_clean();        
            echo $controller->_response;
            ob_flush();
        }

        ob_end_clean();

        return $controller->_response;

    }

    protected $_params = false;
    protected $_options = false;
    protected $_response = false;
    protected $_actions_data = array();
    protected $_uid = 0;

    public function  __construct(&$options, &$params)
    {
        $this->_options = $options;
        $this->_params = $params;
        $this->_uid = session::get('uid', 0);

    }
    public function  before()               {return true;}
    public function  after()                {return true;}

}

/**
 * view
 */

class view{

    public static function factory($filename, $data = array() ){
        return new view($filename, $data);
    }

    protected static $_global_data = array();
    protected $_data = array();
    protected $_filename = NULL;


    public function set_global($key, $value){
        view::$_global_data[$key] = $value;
        return $this;
    }

    public function set_data($key, $value){
        $this->_data[$key] = $value;
        return $this;
    }

    public function  __construct($filename, $data = array() ) {

        $this->_filename = CLASSES_PATH.DIRECTORY_SEPARATOR.
                'view'.DIRECTORY_SEPARATOR.$filename.'.php';

        if(!file_exists($this->_filename))
            throw new Exception('View "'.$this->_filename.'" not found');
            
        $this->_data = $data;
    }

    public function render(){
        extract(view::$_global_data, EXTR_SKIP);
        extract($this->_data, EXTR_OVERWRITE);
        ob_start();                
        include $this->_filename;
        $content =  ob_get_contents();
        ob_clean();

        return $content;
    }

    public function  __toString() {
        return $this->render();
    }
}


//Инициализируем фреймворк

ini_set('gc_probability', 0); //fix ubuntu session errors

set_error_handler( 'controller::error' );
error_reporting(E_ALL);

if( config::get('debug', false) ){
    define('DEBUG', true);    
}

setlocale(LC_ALL, 'ru.UTF8');


spl_autoload_register('myframework_loader');

function myframework_loader($class_name){

    $filename = strtolower( CLASSES_PATH.DIRECTORY_SEPARATOR.
            str_replace('_', DIRECTORY_SEPARATOR, $class_name).'.php' );    
    
    if(!file_exists($filename))
        return;
  
    
    include_once $filename;
}


/**
 * Инициализируем сторонние библиотеки
 */

//ActiveRecord

include VENDORS_PATH.'/activerecord/ActiveRecord.php';

$cfg = ActiveRecord\Config::instance();
$cfg->set_model_directory(MODELS_PATH);
$cfg->set_connections( config::get('database', NULL) );



/**
 * Запускаем контроллер на выполнение запроса
 */

controller::execute( $_SERVER['REQUEST_URI'] );