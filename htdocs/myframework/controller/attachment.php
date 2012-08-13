<?php defined('MYFRAMEWORK') or die('No direct script access.');


class Controller_Attachment extends controller{
    
    /**
     *  прикрепить ссылку к посту     
     */

    public function action_link($type='link', $url = null)
    {
        
        if( empty($_REQUEST['post_id']) ||
            (!is_numeric($_REQUEST['post_id']) && $_REQUEST['post_id']!= 'new') )
        {
            $this->_actions_data['_errors'][] = 'Неверные параметры.';
            $this->_actions_data['_ok'] = 0;
            return;
        }
        $post_id = $_REQUEST['post_id'];
        if($url == null){
            $url = $_REQUEST['url'];
        }
        
        $description = $_REQUEST['description'];

        if($post_id == 'new')
        {
            $postsCtrl = new Controller_Posts($this->_options, $this->_params);
            $post_id = $postsCtrl->action_add(true);
        }

        $attachment = new posts_attachments(array(
            'post_id' => $post_id,
            'type'    => strtolower($type),
            'url'     => $url,
            'description' => $description,
        ));

        $attachment->save();
        
        $this->_actions_data['attachment'] = $attachment->attributes();

    }

    /**
     * прокрепить youtube видео к посту
     */
    
    public function action_video(){
        try{
            if(empty($_REQUEST['url'])) 
                throw new Exception('Неверные параметры.');

            
            $url = strtolower($_REQUEST['url']);
            if( !preg_match('@^(?:http|https)://www.youtube.com/@', $url ) )
            {
                throw new Exception('Можно добавить только видео с YouTube.com.');
            }
            
            $this->action_link('video');
        }
        catch(Exception $e){
            $this->_actions_data['_errors'][] = $e->getMessage();
            $this->_actions_data['_ok'] = 0;
        }
    }

    public function action_image()
    {
        try{
            if( empty($_FILES['file']['tmp_name']))
                throw new Exception('Необходимо указать файл.');

            //проверим картинка ли это?

            if(!$data = getimagesize($_FILES['file']['tmp_name']) )
                throw new Exception ("Можно загружать картинки только стандарнтных форматов: gif, jpeg, png.");

            //переместим в папку attachments

            $path_parts = pathinfo($_FILES["file"]["name"]);            
            $filename = uniqid('pic_', true).'.'.$path_parts['extension'];

            move_uploaded_file(
                    $_FILES['file']['tmp_name'],
                    ATTACHMENTS_PATH.DIRECTORY_SEPARATOR. $filename
                    );


            //добавим в базу

            $this->action_link('picture', ATTACHMENTS_URL.$filename);

            
        }
        catch(Exception $e){
            $this->_actions_data['_errors'][] = $e->getMessage();
            $this->_actions_data['_ok'] = 0;
        }
    }

    /**
     *  выполняется перед action_* функцией
     */

    public function before()
    {
        parent::before();
        
        $this->_actions_data['_ok'] = 1;

        return true;
    }
    

    /**
     *  выполняется после action_* функции и формирует json ответ
     */

    public function after()
    {
        parent::after();
        
        $this->_response = json_encode( $this->_actions_data );

        return true;
    }
}