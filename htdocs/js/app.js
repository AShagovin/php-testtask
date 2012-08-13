$(document).ready(function(){   
    
    $("#post-container").postList();

    //session_uid = getCookie('uid');


    if(!session_uid){
        //покажем окно авторизации
        $('#popup-container').empty().loginDialog();
        return;
    }

    $('#post-editor').postEditor();

    $("a.delete" ).click(function(){
        alert('del');
        console.log('this');
        return false;
    });

    $.router({
        'logout': function(){
            server({
                url: '/user/logout',
                success: function(data){
                    window.location = '/';
                },
                error: function(data){
                    window.location = '/';
                }
            });
        }
    });

    function addAttachment2Editor(attachment){
        $('#post-add-form input[name=post_id]').val(attachment.post_id);
        
        var att = $('#attachment-tmpl').tmpl([attachment]).appendTo('#post-editor-atachments');

        $("a[rel^='prettyPhoto']", att).prettyPhoto({
            callback: function(){
                window.location.hash = '#none';
            }
        });

        $('#popup-container').empty();
    }
       

    $('#add-youtube-btn').click(function(){        

        $('#popup-container').empty().attachDialog({
            submit: 'прикрепить видео',
            css_class: 'youtube',
            action_url: '/attachment/video',
            post_id: $('#post-add-form input[name=post_id]').val(),
            success: addAttachment2Editor
        });
        return false;
    });

    $('#add-picture-btn').click(function(){

        $('#popup-container').empty().attachDialog({
            type: 'picture',
            label_link: 'выберите файл',
            submit: 'прикрепить картинку',
            css_class: 'picture',
            action_url: '/attachment/image',
            post_id: $('#post-add-form input[name=post_id]').val(),
            success: addAttachment2Editor
        });
        return false;
    });

    $('#add-link-btn').click(function(){

        $('#popup-container').empty().attachDialog({
            submit: 'прикрепить ссылку',
            css_class: 'link',
            action_url: '/attachment/link',
            post_id: $('#post-add-form input[name=post_id]').val(),
            success: addAttachment2Editor
        });
        return false;
    });

    //
    eventProcessStart(5000);

});