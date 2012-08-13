<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<meta name="description" content="Позволяет вести микроблог, общаться в режиме онлайн." />
<meta name="keywords" content="tt test task микроблог чат проверка знаний программирование" />

<title>TT: TestTask портал</title>
    <link type="text/css" rel="stylesheet" href="/css/style.css" media="screen"/>    
    <script type="text/javascript">
        var username = '<?php echo $username; ?>';
        var session_uid = <?php echo $uid; ?>;
    </script>
    <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/themes/ui-lightness/jquery-ui.css" rel="stylesheet" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js"></script>
   
    <link rel="stylesheet" type="text/css" href="/vendors/markitup/skins/simple/style.css" />
    <script type="text/javascript" src="/vendors/markitup/jquery.markitup.js"></script>

    <link rel="stylesheet" href="/vendors/prettyPhoto/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
    <script src="/vendors/prettyPhoto/js/jquery.prettyPhoto.js" type="text/javascript"></script>

    <script src="/vendors/zforms/zforms.js" type="text/javascript"></script>

    <script type="text/javascript" src="/js/plugins.js"></script>
    <script type="text/javascript" src="/js/base.js"></script>
    <script type="text/javascript" src="/js/app.js"></script>
</head>
<body>
    <div id="screen-blackout"></div>
    <div id="popup-container"></div>

    <script id="login-dialog-tmpl" type="text/x-jquery-tmpl">
        <div id="login-dialog" class="popup-window hide auth">
            <div style="float: right; margin: 10px; display: none;" class="indicator"></div>
            <form action="/user/login" method="post" class="zf" onclick="return {    oOptions:{bCheckForValid:true, bPreventSubmit:true} }">                                
                <dl>
                    <dt><label for="input-email">Электронный адрес:</label></dt>
                    <dd><input id="input-email" type="text" name="email" value="" class="zf" onclick="return {    oRequired : {iMin: '6' },    oValid : { sType : 'email' } }"/></dd>
                </dl>                              
                <dl>
                    <dt>&nbsp;</dt>
                   <dd style="text-align: center;"><input type="submit" value="войти" class="zf" /> &nbsp;&nbsp;[<a href="#signup">регистрация</a>]</dd>
                </dl>
            </form>
        </div>
    </script>

    <script id="signup-dialog-tmpl" type="text/x-jquery-tmpl">
        <div id="signup-dialog" class="popup-window hide auth">
            <div style="float: right; margin: 10px; display: none;" class="indicator"></div>
            <form action="/user/signup" method="post" class="zf" onclick="return {    oOptions:{bCheckForValid:true, bPreventSubmit:true} }">
                <dl>
                    <dt><label for="input-username">Ваше имя:</label></dt>
                    <dd>
                        <input id="input-username" type="text" name="username" value="" class="zf" onclick="return {  oRequired : {iMin: '3' }  }"/>
                    </dd>
                </dl>
                <dl>
                    <dt><label for="input-email">Электронный адрес:</label></dt>
                    <dd><input id="input-email" type="text" name="email" value="" class="zf" onclick="return {    oRequired : {iMin: '6' },    oValid : { sType : 'email' } }"/></dd>
                </dl>
                <dl>
                    <dt>&nbsp;</dt>
                    <dd style="text-align: center;"><input type="submit" value="регистрация" class="zf" />&nbsp;&nbsp;[<a href="#login">вход</a>]</dd>
                </dl>
            </form>
        </div>
    </script>

    <script id="attachment-tmpl" type="text/x-jquery-tmpl">
        
            <div class="attachment">
            {{if type=='video'}}
            <a href="${url}" rel="prettyPhoto" title="${description}">
                <img src="${getThumbnailURL(url)}" alt="${description}" width="120" height="90" /></br>
                ${description}
            </a>
            {{/if}}
            {{if type=='picture'}}
            <a href="${url}" rel="prettyPhoto" title="${description}">
                <img src="${url}" alt="${description}" width="120" height="90" /></br>
                ${description}
            </a>
            {{/if}}

            {{if type=='link'}}
            <a href="${url}" target="blank" title="${description}">
                <img src="/img/url-big.png" alt="${description}" width="120" height="90" /></br>
                ${description}
            </a>
            {{/if}}
            </div>
        
    </script>

    <script id="template-post" type="text/x-jquery-tmpl">
        <div id="post-${id}" class="post">
            <div class="post-header">
                <span class="username">${username}</span>
                <span class="date">${create_on}</span>
            </div>
                {{if uid == session_uid}}
                <a class="delete" href="#" title="Удалить пост"></a>
                {{/if}}
            <div class="clear"></div>
           
            <div class="post-content round-corner-all10">${text}</div>
            <div class="clear"></div>
            <div class="post-attachments">{{tmpl(attachments) "#attachment-tmpl"}}</div>
            <div class="clear"></div>
            <div class="post-footer">
                <a href="#" class="likeit-btn round-corner-all{{if like_already == 1}} disabled{{/if}}">
                    мне нравится (<span>${like_members_count}</span>)
                    {{if like_members.length}}
                    <div class="members">
                        <ul>
                            {{each like_members}}
                            <li><img src="${avatar}" alt="${username}" title="${username}"/></li>
                            {{/each}}
                        </ul>
                        <div class="members-rise"></div> 
                    </div>
                    {{/if}}
                    
                </a>
                <div class="clear"></div>
            </div>
        </div>
    </script>

    <script id="attach-dialog-tmpl" type="text/x-jquery-tmpl">
        <div id="attach-dialog" class="popup-window">            
            <a class="delete" href="" title="Закрыть"></a>
            <form action="${action_url}" method="post" class="${css_class} clear" enctype="multipart/form-data">
                <input type="hidden" name="post_id" value="${post_id}" />
                
                {{if type=='link'}}
                <dl>
                    <dt><label for="attach-input-link">${label_link}</label></dt>
                    <dd><input id="attach-input-link" type="text" name="url" value=""/></dd>
                </dl>
                {{else}}
                <dl>
                    <dt><label for="attach-input-file">${label_link}</label></dt>
                    <dd><input id="attach-input-file" type="file" name="file" value=""/></dd>
                </dl>
                {{/if}}
                <dl>
                    <dt><label for="attach-input-title">${label_title}</label></dt>
                    <dd><input id="attach-input-link" type="text" name="description" value=""/></dd>
                </dl>

                <dl>
                    <dt>&nbsp;</dt>
                    <dd><input type="submit" value="${submit}" /></dd>
                </dl>
            </form>
        </div>
    </script>

    
    <div id="page" class="round-corner-all10">
        <div id="system-menu">
            <a href="#logout">выход</a>
        </div>        
        <div id="post-add-new">
            <div class="post">
                <div id="post-editor-username">
                
                </div>
                <form id="post-add-form" method="post" action="/posts/add">
                    <input type="hidden" name="post_id" value="new" />
                    <div id="post-editor-container"><textarea id="post-editor" cols="90" rows="10" name="text"></textarea></div>
                </form>
                <div id="post-editor-atachments"></div>
                <div id="post-editor-buttom-buttons">
                    <ul>
                        <li>Прикрепить к посту:</li>
                        <li><a href="#" id="add-youtube-btn" title="Прикрепить видео с YouTube">Видео YouTube</a></li>
                        <li><a href="#" id="add-picture-btn" title="Прикрепить картинку">Картинка</a></li>
                        <li><a href="#"  id="add-link-btn"    title="Прикрепить ссылку">Ссылка</a></li>
                    </ul>
                    <a href="#" id="send-post-btn" class="round-corner-all">опубликовать</a>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <div id="post-container"></div>
        <div id="right-side-bar"></div>
    </div>
    <div id="page-footer">Demchenko Eugene 2011&copy;</div>
</body>
</html>