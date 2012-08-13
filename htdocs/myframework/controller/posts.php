<?php defined('MYFRAMEWORK') or die('No direct script access.');

class Controller_Posts extends controller{
    
    public function action_add($draft = false)
    {
        try{

            if($draft){
                //это внутренний вызов.
                //просто создаем пустой пост
                $post = new posts(array(
                    'uid' => $this->_uid,                    
                    'status' => 'draft',
                    ));

                $post->save();

                return $post->id;
            }


            if( empty($_REQUEST['post_id']) || 
                (!is_numeric($_REQUEST['post_id']) && $_REQUEST['post_id'] !== 'new')  )
            {
                throw new Exception('Неверные параметры.');
            }

            if(!$this->_uid){
                throw new Exception('Публиковать сообщения могут только авторизованые пользователи.');
            }

            $post_id = $_REQUEST['post_id'];
            $text = $_REQUEST['text'];

            if( $post_id === 'new' && $text === ''){
                throw new Exception('Нельзя опубликовать пустые сообщения. Напишите текст сообщения и/или прикрепите к нему вложения.');
            }

            if($post_id === 'new'){
                $post = new posts(array(
                    'uid' => $this->_uid,
                    'text' => $text,
                    'status' => 'active',
                    ));

                $post->save();

                //вернем новый пост
                $this->action_get($post->id);
                return;
            }

            $post = posts::find_by_id($post_id);
            if(!$post){
                throw new Exception('Сообщение не найдено.');
            }

            $post->text = $text;
            $post->status = 'active';
            $post->save();

            //вернем новый пост
            $this->action_get($post->id);
        }
        catch(Exception $e){
            $this->_actions_data['_errors'][] = $e->getMessage();
                 $this->_actions_data['_ok'] = 0;

            return 0;
        }
    }

    public function action_delete()
    {
        try{

            if( empty($_REQUEST['post_id']) || !is_numeric($_REQUEST['post_id'])  )
            {
                throw new Exception('Неверные параметры.');
            }

            if(!$this->_uid){
                throw new Exception('Удалять могут только авторизованые пользователи.');
            }

            $post = posts::find_by_id($_REQUEST['post_id']);
            if(!$post){
                throw new Exception('Сообщение не найдено.');
            }

            if( $post->uid !== $this->_uid ){
                throw new Exception('Можно удалять только свои сообщения.');
            }

            $post->delete();

            //удаляем также все связанные записи в других таблицах
            
            posts_attachments::connection()
                ->query('DELETE FROM posts_attachments WHERE post_id='. $_REQUEST['post_id']);
            
            posts_like_members::connection()
                ->query('DELETE FROM posts_like_members WHERE post_id='. $_REQUEST['post_id']);

        }
        catch(Exception $e){
            $this->_actions_data['_errors'][] = $e->getMessage();
            $this->_actions_data['_ok'] = 0;
        }
    }

    /**
     *  вернет список атачментов к посту
     *
     *  результат в $this->_actions_data['attachments']
     */

    public function action_attachments($post_id = null)
    {
        $this->_actions_data['attachments'] = array();

        if( $post_id == null)
        {
            if( empty($_REQUEST['post_id']) || !is_numeric($_REQUEST['post_id']) )
            {
                return;
            }
            $post_id = $_REQUEST['post_id'];
        }

        foreach(posts_attachments::find_all_by_post_id($post_id) as $attachment)
        {
            $this->_actions_data['attachments'][] = $attachment->attributes();
        }
    }

    /**
     * вернет список лайк мемберс
     *
     * результат в $this->_actions_data['like_members']
     */
    
    public function action_like_members($post_id = null, $limit = null)
    {
        $this->_actions_data['like_members'] = array();

        if( $post_id === null)
        {
            if( empty($_REQUEST['post_id']) || !is_numeric($_REQUEST['post_id']) )
            {
                return;
            }
            $post_id = $_REQUEST['post_id'];
        }

        if( $limit === null)
        {
            if( !empty($_REQUEST['like_members_limit']) &&
                is_numeric($_REQUEST['like_members_limit']) )
            {
                $limit = $_REQUEST['like_members_limit'];
            }
        }

        $sql = 'SELECT a.uid,b.email,b.public_email,b.name as username
                FROM posts_like_members a, users b
                WHERE b.uid = a.uid AND a.post_id='.$post_id.
               ' ORDER BY id DESC';
        if($limit)
            $sql.= ' LIMIT'.$limit;

        foreach(posts_like_members::find_by_sql($sql) as $like_members)
        {

            $lm = $like_members->attributes();
            
            $lm['avatar'] = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $lm['email'] ) ) );

            if($lm['public_email'] !== 'Y'){
                unset($lm['email']);
            }
            unset($lm['public_email']);

            $this->_actions_data['like_members'][] = $lm;
        }
    }

    /**
     * вернет количество лайк мемберс
     *
     * результат в $this->_actions_data['like_members_count']
     */
    public function action_like_members_count($post_id = null)
    {
        if( $post_id === null)
        {
            if( empty($_REQUEST['post_id']) || !is_numeric($_REQUEST['post_id']) )
            {
                return;
            }
            $post_id = $_REQUEST['post_id'];
        }
        
        $this->_actions_data['like_members_count'] =
            ActiveRecord\ConnectionManager::get_connection()
                ->query_and_fetch_one(
                'SELECT count(id) as count FROM posts_like_members WHERE post_id='.$post_id
                );               
    }


    /**
     *  вернет пост $post_id
     *
     *  результат в $this->_actions_data['post']
     */

    public function action_get($post_id = null)
    {
        if( $post_id === null)
        {
            if( empty($_REQUEST['post_id']) || !is_numeric($_REQUEST['post_id']) )
            {
                $this->_actions_data['_post']['status'] = 'post_id not defined.';
                return;
            }
            $post_id = $_REQUEST['post_id'];
        }        

        $sql = 'SELECT a.id,a.uid,a.text,a.create_on,b.email,b.name as username,b.public_email 
                FROM posts a, users b
                WHERE b.uid = a.uid AND a.id='.$post_id;

        $post = posts::find_by_sql($sql);

        if(count($post) == 0){
            $this->_actions_data['_post']['status'] = 'Post #'.$post_id.' not found.';
            return;
        }
        
        $attr = $post[0]->attributes();


        if( is_object($attr['create_on']) )
            $attr['create_on'] = $attr['create_on']->format('d M Y H:i:s');

        $this->_actions_data['post'] = $attr;        

        $this->_actions_data['post']['like_already'] = $this->is_already_like($post_id) ? 1:0;
        //
        $this->action_like_members_count($post_id);
        $this->_actions_data['post']['like_members_count'] = $this->_actions_data['like_members_count'];
        unset( $this->_actions_data['like_members_count'] );

        //
        $this->action_like_members($post_id);
        $this->_actions_data['post']['like_members'] = &$this->_actions_data['like_members'];
        unset( $this->_actions_data['like_members'] );        

        //
        $this->action_attachments($post_id);
        $this->_actions_data['post']['attachments'] = &$this->_actions_data['attachments'];
        unset( $this->_actions_data['attachments'] );        

    }

    /**
     *  вернет поcледние $limit постов
     *
     *  результат в $this->_actions_data['posts']
     */

    public function action_list($limit = null)
    {
        if($limit === null)
        {
            $limit = (!empty($_REQUEST['posts_list_limit']) && is_numeric($_REQUEST['posts_list_limit']) )
                 ? $_REQUEST['posts_list_limit'] :  10 ;
        }
        
        $sql = 'SELECT a.id,a.uid,a.text,a.create_on,b.email,b.name as username,b.public_email 
                FROM posts a, users b
                WHERE b.uid = a.uid
                AND a.status = \'active\'
                ORDER BY id DESC
                LIMIT '.$limit;
                
        foreach( posts::find_by_sql($sql) as $post)
        {

            $attr = $post->attributes();

            if( is_object($attr['create_on']) )
                $attr['create_on'] = $attr['create_on']->format('d M Y H:i:s');


            $attr['like_already'] = $this->is_already_like($attr['id']) ? 1:0;

            //
            $this->action_like_members_count($attr['id']);
            $attr['like_members_count'] = $this->_actions_data['like_members_count'];
            unset( $this->_actions_data['like_members_count'] );

            //
            $this->action_like_members($attr['id']);
            $attr['like_members'] = &$this->_actions_data['like_members'];
            unset( $this->_actions_data['like_members'] );

            //
            $this->action_attachments($attr['id']);
            $attr['attachments'] = &$this->_actions_data['attachments'];
            unset( $this->_actions_data['attachments'] );

            $this->_actions_data['posts'][] = $attr;
        }
        
    }

    /**
     *  вернет true, если uid уже нажимал like button
     *   
     */

    public function is_already_like($post_id, $uid = null)
    {
        if($uid === null)
            $uid = $this->_uid;
        
        $like = posts_like_members::find_by_post_id_and_uid($post_id, $uid);        

        return $like !== null;
    }

    /**
     *  отметит пост как понравившийся текущему пользователю
     */

    public function action_like($post_id = null)
    {
        if( $post_id === null)
        {
            if( empty($_REQUEST['post_id']) || !is_numeric($_REQUEST['post_id']) )
            {
                $this->_actions_data['_errors'][] = 'Неверные параметры.';
                $this->_actions_data['_ok'] = 0;
                return;
            }
            $post_id = $_REQUEST['post_id'];
        }
        if( $this->is_already_like($post_id) )
        {
            $this->_actions_data['_errors'][] = 'Вы уже отмечали это сообщение.';
            $this->_actions_data['_ok'] = 0;
            return;
        }

        $plm = new posts_like_members(array(
            'post_id' => $post_id,
            'uid' => $this->_uid,
            ));
        $plm->save();

        //вернем количество
        $this->action_like_members_count();
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