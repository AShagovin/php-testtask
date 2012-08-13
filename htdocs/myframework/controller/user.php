<?php defined('MYFRAMEWORK') or die('No direct script access.');


class Controller_User extends controller{

    /**
     * авторизует пользователя
     * если такого пользователя нет, то создает его
     */

    public function action_login(){

        try{
            if( empty( $_REQUEST['email']))
            {
                throw new Exception('Все поля должны быть заполнены.');
            }

            $user = user::find_by_email($_REQUEST['email']);

            if($user == null){
                throw new Exception('Пользователь с таким электронным адресом не зарегистрирован.');
            }

            session::set('uid', $user->uid);
            session::set('username', $user->name);

            //setcookie('uid', $user->uid, time()+60*60*24*30, '/');

            $this->_actions_data['_ok'] = 1;
            $this->_actions_data['uid'] = $user->uid;

        }
        catch (Exception $e){
             $this->_actions_data['_errors'][] = $e->getMessage();
             $this->_actions_data['_ok'] = 0;
        }
        
    }

    /**
     * авторизует пользователя
     * если такого пользователя нет, то создает его
     */

    public function action_signup(){
        try{

            if( empty( $_REQUEST['email']) || empty( $_REQUEST['username']) )
            {                
                throw new Exception('Все поля должны быть заполнены.');
            }

            $user = user::find_by_email($_REQUEST['email']);

            if($user != null){
                
                throw new Exception('Пользователь с таким электронным адресом уже зарегистирован.');
            }

            $user = new user(array(
                'name' => $_REQUEST['username'],
                'email' => $_REQUEST['email']
            ));
            $user->save();

            //зарегистрировали, тут же авторизуем

            $this->action_login();

            
        }
        catch(Exception $e){
             $this->_actions_data['_errors'][] = $e->getMessage();
             $this->_actions_data['_ok'] = 0;
        }

    }

    /**
     * завершает сессию пользователя
     */

    public function action_logout(){

        session::delete('uid');
        setcookie('uid', '', time()-360000, '/');
        setcookie('username', '', time()+60*60*24*30, '/');
        $this->_actions_data['_ok'] = 1;
    }


    /**
     *  выполняется после action_* функции и формирует json ответ
     */

    public function after()
    {
        $this->_response = json_encode( $this->_actions_data );

        return true;
    }
}