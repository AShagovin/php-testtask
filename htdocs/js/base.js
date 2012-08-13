(function () {

    //выводит див с текстовым описанием статуса выполнения операции

    $.fn.actionStatus = function(o){

        var o = jQuery.extend({
            type: 'success',
            text: 'Выполнено успешно'
        }, o);

        var div = $(this).data('actionStatus');
        if( div )
            div.remove();

        if(o.text=='')return;

        var t;

        if( typeof o.text == 'object' || typeof o.text == 'array' ){
            t = '<ul>';
            for( var k in o.text ){
                if( !o.text.hasOwnProperty(k) ) continue;

                t+= '<li>'+o.text[k]+'</li>';
            }
            t+= '</ul>';
        }
        else{
            t = o.text;
        }


        div = $('<div class="status status-'+o.type+' hide">'+t+'</div>').prependTo(this);
        $(this).data('actionStatus', div);
        div.show(o.type =='error'? 'highlight':'fade');

        var closeBtn = $('<a class="close-small"></a>').prependTo(div).click(function(){
                var p = $(this).parent();
                p.hide('fade', function(){

                p.parent().removeData('actionStatus');
                p.remove();
            });
        });

        if(o.type !=='error')
            setTimeout(function(){closeBtn.trigger('click');}, 3000);

    };
    

    function appendPost(data, container){

        if(typeof data.posts == "undefined")        return;

        var post;
        
        if(typeof data.prepend != undefined && data.prepend == 1)
            post = $( "#template-post" ).tmpl( data.posts ).prependTo( container);
        else
            post = $( "#template-post" ).tmpl( data.posts ).appendTo( container);
        
        $("a[rel^='prettyPhoto']", post).prettyPhoto({
            callback: function(){
                window.location.hash = '#none';
            }
        });

        //delete

        $("a.delete", post).click(function(){

            if( $(this).hasClass('indicator') ) return false;

            var post = $(this).parents('div.post');
            var post_id = post.attr('id').replace('post-', '');


            $(this).addClass('indicator');

            server({
                url: '/posts/delete',
                post: 'post_id='+post_id,
                success: function(data){                    
                    post.remove();
                },
                error: function(data){
                    $(this).removeClass('indicator');                    
                    post.actionStatus({text: data._errors, type: 'error'});
                }
            })
        });
        

        //like button
        $("a.likeit-btn", post).click(function(){

            var post = $(this).parents('div.post');
            var post_id = post.attr('id').replace('post-', '');
            
            var del  = $("a.delete", post);
            if( del.hasClass('indicator') ) return false;
            if( $(this).hasClass('disabled') ) return false;

            del.addClass('indicator')                        

            var counter = $('span', $(this) );

            server({
                url: '/posts/like',
                post: 'post_id='+post_id,
                success: function(data){
                    del.removeClass('indicator');
                    post.actionStatus({text:''});
                    counter.text(data.like_members_count);
                    
                },
                error: function(data){
                    del.removeClass('indicator');
                    post.actionStatus({text: data._errors, type: 'error'});
                }
            })

            return false;
        });               

    }

    $.fn.postList = function(options){

        var o = jQuery.extend({
        }, options);

        var container = this;

        server({
            url: '/posts/list?limit=10',
            success: appendPost,
            param: container
        });


        $(window).bind('posts_changed', function(event, data){
            container.empty();            
            appendPost(data, container);            
        });
    }    

    $.fn.postEditor = function(options){/*
        myHtmlSettings = {
            nameSpace:       "bbcode", // Useful to prevent multi-instances CSS conflict
            onShiftEnter:    {keepDefault:false, replaceWith:'<br />\n'},
            onCtrlEnter:     {keepDefault:false, openWith:'\n<p>', closeWith:'</p>\n'},
            onTab:           {keepDefault:false, openWith:'     '},
            markupSet:  [
                {name:'Bold', key:'B', openWith:'<strong>', closeWith:'</strong>'},
                {name:'Italic', key:'I', openWith:'<em>', closeWith:'</em>'},
                {name:'Stroke through', key:'S', openWith:'<del>', closeWith:'</del>'},        
            ]
        }
        $(this).markItUp(myHtmlSettings);*/

        var form = $(this).parents('form');        

        $('#post-editor-username').html(
            '<span class="username">'+ username + '</span>');

        /**
         *  Кнопка опубликовать
         */

        $('#send-post-btn').click(function(){

            var post  = $('#post-editor').val();
            if( post == '' && $('#post-editor-atachments div.attachment').size() == 0){
                form.actionStatus({text: 'Нельзя опубликовать пустые сообщения. Напишите текст сообщения и/или прикрепите к нему вложения.', type: 'error'});                
                return false;
            }
            form.actionStatus({text: ''});
            server({
                url: '/posts/add',
                post: form.serialize(),
                success: function(data){
                    form.actionStatus({text: ''});

                    appendPost({ posts: [data.post], prepend:1 }, $("#post-container"));

                    //очищаем форму
                    $('#post-editor-atachments').empty();
                    $('#post-add-form input[name=post_id]').val('new');
                    $('#post-editor').val('');
                },
                error: function(data){
                    form.actionStatus({text: data._errors, type: 'error'});
                },
                inidicator: $('.indicator', form)

            });

            return false;
        });

    }

    /**
     *  Attach dialog
     */

    $.fn.attachDialog = function(options){

        var o = jQuery.extend({
            action_url: '/attachment/link',
            label_link: 'ссылка',
            label_title: 'описание',
            submit: 'прикрепить',
            type: 'link',
            post_id: 'new',
            close: function(){
                $('#popup-container').empty();
            },
            success: null
        }, options);

        var dialog = $('#attach-dialog-tmpl').tmpl(o).appendTo(this);

        $('a.delete', dialog).click(function(){

            if( $(this).hasClass('indicator') )
                return false;

            if(o.close)o.close();
            return false;
        });

        var form = $('form', dialog);

        if(o.type == 'picture'){
            //загружаем файл

            form.iframePostForm({
                    post: function(){
                        $('a.delete', dialog).addClass('indicator');
                        return true;
                    },
                    complete : function (response)
                    {
                        $('a.delete', dialog).removeClass('indicator');

                        var data = JSON.parse(response);
                        if(data._ok==1){
                            form.actionStatus({text: ''});                            

                            if(o.success)
                                o.success( data.attachment );
                        }else{
                            form.actionStatus({text: data._errors, type: 'error'});
                        }
                    }
            });

        }else{
            form.submit(function(){

                server({
                    url: o.action_url,
                    post: form.serialize()+'&post_id='+o.post_id,
                    success: function(data){
                        //form.actionStatus({text: ''});
                        if(o.success)
                            o.success(data.attachment);
                    },
                    error: function(data){
                        form.actionStatus({text: data._errors, type: 'error'});
                    }
                });
                return false;
            });
        }
    }

    /**
     *  Login dialog
     */

    $.fn.loginDialog = function(options){

        var o = jQuery.extend({
            action_url: '/user/login',          
        }, options);
        
        screen_blackout(true);


        var dialog = $('#login-dialog-tmpl').tmpl(o).appendTo(this);

        dialog.show('fade', 800);       

        var form = $('form', dialog);

        if(ZForms)
            ZForms.buildForm( form[0] );        
        
        form.submit(function(){
            server({
                url: o.action_url,
                post: form.serialize(),
                success: function(){
                    form.actionStatus({text: 'Авторизация прошла успешно.'});

                    window.location = '/';
                },
                error: function(data){
                    form.actionStatus({text: data._errors, type: 'error'});
                },
                inidicator: $('#login-dialog .indicator')

            });
        });

        $('a[href=#signup]',dialog).click(function(){
            $('#popup-container').empty().signupDialog();
            return false;
        });
    }

    /**
     *  Signup dialog
     */

    $.fn.signupDialog = function(options){

        var o = jQuery.extend({
            action_url: '/user/signup',          
        }, options);

        screen_blackout(true);


        var dialog = $('#signup-dialog-tmpl').tmpl(o).appendTo(this);

        dialog.show('fade', 800);

        var form = $('form', dialog);

        if(ZForms)
            ZForms.buildForm( form[0] );

        form.submit(function(){
            server({
                url: o.action_url,
                post: form.serialize(),
                success: function(){
                    form.actionStatus({text: 'Авторизация прошла успешно.'});

                    window.location = '/';
                },
                error: function(data){
                    form.actionStatus({text: data._errors, type: 'error'});
                },
                inidicator: $('#signup-dialog .indicator')

            });
        });

        $('a[href=#login]',dialog).click(function(){
            $('#popup-container').empty().loginDialog();
            return false;
        });
    }

})(jQuery);

/**
 * ajax запрос на сервер
 */

function server(o){
    var o = jQuery.extend({
        url: null,        
        post:'',
        indicator: null,
        success: null,
        error: null,
        param: null
    }, o);

    if(o.indicator)
        o.indicator.show();

    $.post(
        o.url,
        o.post,
        function(data, textStatus, XMLHttpRequest){
            if(o.indicator)
                o.indicator.hide();
            
            if( data._ok == 1 ){

                if(o.success)
                    o.success(data, o.param);
            }
            else{
                if(o.error)
                    o.error(data, o.param);
            }

        },
        'json'
    );

};

/* покажет затемненый фон для модульаных окон */

function screen_blackout(show){
        if(show){
            $('#screen-blackout').show();
            $('body').css('overflow', 'hidden');
        }
        else{
            $('#screen-blackout').hide();
            $('body').css('overflow', 'auto');
        }
    }

/**
 * сформирует урл для видео превью
 */

function getThumbnailURL(url){
    
    var arr = null;

    //<!--http://www.youtube.com/watch?v=<videoId>-->
    
    if( arr = /^(?:(?:http|https):\/\/)www.youtube.com\/watch\?v=([^\&]+)/.exec(url) ){
        return  'http://img.youtube.com/vi/'+arr[1]+'/2.jpg';
    }
    
    return '/img/video.png';
}

var eventHandlers = [];
var timerInterval = false;

function eventProcess(){    

    server({        
        url:'/events/get',
        success: function(data){
            setTimeout(eventProcess, timerInterval);
            
            if(typeof data['posts'] != 'undefined'){
                //генерируем событие                
                $(window).trigger('posts_changed', data);
            }
        }
    });
}

function eventProcessStart(interval){
    timerInterval = interval;
    setTimeout(eventProcess, interval);
}



function setCookie (name, value, expires, path, domain, secure) {
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

function getCookie(name) {
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if (cookie.length > 0) {
		offset = cookie.indexOf(search);
		if (offset != -1) {
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if (end == -1) {
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return(setStr);
}