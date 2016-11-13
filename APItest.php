<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>HiShopper API test</title>
    <script type="text/javascript" src="static/js/jquery-2.1.0.min.js"></script>
    <style type="text/css">
        .block{
            float: left;
            width: 100%;
        }
        .mtop-20{
            margin-top: 20px;
        }
        .border{
            border: 1px solid lightgray;
        }
        .pad{
            padding: 5px;
        }
        .w50{
            width: 50%;
        }
    </style>
</head>
<body>
    <script type="application/javascript">
        function sendRequest(_method, _req, _indx){

            var  _data         = $('#data'+_indx).val()
                ,_dataType     = 'json'
                ,_contentType  = 'application/json; charset=utf-8'
                ,_headers      = {}
                ,_auth         = $('body').data('auth')
                ,_processData  = true
                ,_fdata;

            if( _auth )
                _headers = {
                    'x-auth': _auth
                };

            _fdata = _data;
            if( $('#pic')[0].files.length > 0 ){
                _fdata = new FormData();
                _fdata.append('data', _data);
                $.each($('#pic')[0].files, function (i, file) {
                    _fdata.append('file-' + i, file);
                });

                _dataType = 'text';
                _contentType = false;
                _processData = false;
            }

            $.ajax({
                url: _req,
                method: _method,
                headers: _headers,
                dataType: _dataType,
                contentType: _contentType,
                processData: _processData,
                data: _fdata,
                success: function(resp){
                    
                    console.log(resp);
                    
                    if( resp ){
                        //show server response
                        $('#response' + _indx).text(JSON.stringify(resp));

                        //save auth token to body data
                        if(    resp.result != null
                            && typeof resp.result.pt != undefined
                        ){
                            $('body').data('auth', resp.result.pt);
                        }
                    }
                }
            });
        }
    </script>

    <div class="block border">
        <input id="pic" name="pic" type="file" multiple="">
    </div>

    <?php
        $req = [
             ['caption'=>'Ошибка в URL', 'url'=>'oeritoer', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Авторизация по телефону, пользователь заблокирован', 'url'=>'auth/phone', 'params'=>'{"phone":"79171111111"}']
            ,['caption'=>'Авторизация по телефону, не задан телефон', 'url'=>'auth/phone', 'params'=>'{}']
            ,['caption'=>'Авторизация по телефону, ошибка в формате', 'url'=>'auth/phone', 'params'=>'{"phone":"9170010203"}']
            ,['caption'=>'Авторизация по телефону', 'url'=>'auth/phone', 'params'=>'{"phone":"79170010203"}']
            ,['caption'=>'Ввод кода из sms, неправильный код', 'url'=>'auth/verify', 'params'=>'{"phone":"79170010203","code":"0001","user":2,"device":{"uuid":"0a89df6v7df6sv7r6s07f","pt":"df79b6sd8fbg6","x":320,"y":480}}']
            ,['caption'=>'Ввод кода из sms, неправильный id пользователя', 'url'=>'auth/verify', 'params'=>'{"phone":"79170010203","code":"0001","user":0,"device":{"uuid":"0a89df6v7df6sv7r6s07f","pt":"df79b6sd8fbg6","x":320,"y":480}}']
            ,['caption'=>'Ввод кода из sms', 'url'=>'auth/verify', 'params'=>'{"phone":"79170010203","code":"2058","user":2,"device":{"uuid":"0a89df6v7df6sv7r6s07f","pt":"df79b6sd8fbg6","x":320,"y":480}}']
            ,['caption'=>'Добавление пуш токена', 'url'=>'auth/setpt', 'params'=>'{"pt":"df79b6sd8fbg6"}']

            ,['caption'=>'Профиль пользователя', 'url'=>'user/profile', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Публичный профиль пользователя', 'url'=>'user/public/1', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Сделки в публичном профиле', 'url'=>'user/public/1/deals', 'params'=>'max=3&count=2', 'method'=>'GET']
            ,['caption'=>'Объявления в публичном профиле', 'url'=>'user/public/1/ads', 'params'=>'max=3&count=2', 'method'=>'GET']
            ,['caption'=>'Проверка никнейма', 'url'=>'user/nickname', 'params'=>'{"nickname":"test"}']
            ,['caption'=>'Редактирование профиля', 'url'=>'user/update', 'params'=>'{"name":"Вася Пупкин", "city":"Москва", "nickname":"Pinkpanter","email":"pinkpanter@mail.ru"}']
        ];

    $c = 0;
    foreach ($req as $rq){
        echo '  <div class="block border">
                    <div class="block pad">
                        <table class="w50">
                            <tr>
                                <td align="left" class="w50">'.$rq['caption'].'</td>
                                <td align="right" class="w50">
                                    <table>
                                        <tr>
                                            <td>
                                                <input id="data'.$c.'" type="text" value=\''.$rq['params'].'\'/>
                                            </td>
                                            <td>
                                                <input type="button" value="Отправить запрос" onclick="sendRequest('.(isset($rq['method']) ? '\''.$rq['method'] .'\'' : '\'POST\'').', \'/hiShopperAPI/'.$rq['url'].'\', '.$c.')"/>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="response'.$c.'" class="block pad w50">
                    </div>
                </div>';
        $c++;
    }
    ?>
</body>
</html>