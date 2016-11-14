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
            ,['caption'=>'Редактирование профиля + файл', 'url'=>'user/update', 'params'=>'{"name":"Вася Пупкин", "city":"Москва", "nickname":"Pinkpanter","email":"pinkpanter@mail.ru"}']
            ,['caption'=>'Редактирование профиля, никнейм занят', 'url'=>'user/update', 'params'=>'{"nickname":"test"}']
            ,['caption'=>'Редактирование профиля, удаление фото', 'url'=>'user/update', 'params'=>'{"photo":"delete"}']

            ,['caption'=>'Подписка, не задан "издатель"', 'url'=>'user/follow', 'params'=>'{}']
            ,['caption'=>'Подписка, не найден "издатель"', 'url'=>'user/follow', 'params'=>'{"publisher":0}']
            ,['caption'=>'Подписка, уже подписан', 'url'=>'user/follow', 'params'=>'{"publisher":3}']
            ,['caption'=>'Подписка', 'url'=>'user/follow', 'params'=>'{"publisher":1}']

            ,['caption'=>'Отписка, не задан "издатель"', 'url'=>'user/unfollow', 'params'=>'{}']
            ,['caption'=>'Отписка, не найден "издатель"', 'url'=>'user/unfollow', 'params'=>'{"publisher":0}']
            ,['caption'=>'Отписка, не подписан', 'url'=>'user/unfollow', 'params'=>'{"publisher":2}']
            ,['caption'=>'Отписка', 'url'=>'user/unfollow', 'params'=>'{"publisher":1}']

            ,['caption'=>'Добавление в избранное, не задано объявление', 'url'=>'user/favorite/add', 'params'=>'{}']
            ,['caption'=>'Добавление в избранное, не найдено объявление', 'url'=>'user/favorite/add', 'params'=>'{"ad":0}']
            ,['caption'=>'Добавление в избранное, уже добавлено', 'url'=>'user/favorite/add', 'params'=>'{"ad":1}']
            ,['caption'=>'Добавление в избранное', 'url'=>'user/favorite/add', 'params'=>'{"ad":2}']

            ,['caption'=>'Удаление из избранного, не задано объявление', 'url'=>'user/favorite/remove', 'params'=>'{}']
            ,['caption'=>'Удаление из избранного, не найдено объявление', 'url'=>'user/favorite/remove', 'params'=>'{"ad":0}']
            ,['caption'=>'Удаление из избранного, нет в избранном', 'url'=>'user/favorite/remove', 'params'=>'{"ad":555}']
            ,['caption'=>'Удаление из избранного', 'url'=>'user/favorite/remove', 'params'=>'{"ad":2}']

            ,['caption'=>'Избранное', 'url'=>'user/favorite/list', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Избранное, параметры постранички', 'url'=>'user/favorite/list', 'params'=>'max=1&count=1', 'method'=>'GET']
            ,['caption'=>'Количество в избранном', 'url'=>'user/favorite/count', 'params'=>'', 'method'=>'GET']

            ,['caption'=>'Поиск пользователей, (по Никнейму)', 'url'=>'user/search', 'params'=>'q=pink', 'method'=>'GET']
            ,['caption'=>'Поиск пользователей, только общее количество', 'url'=>'user/search', 'params'=>'q=pink&type=count', 'method'=>'GET']
            ,['caption'=>'Поиск пользователей, короткий запрос', 'url'=>'user/search', 'params'=>'q=pi', 'method'=>'GET']
            ,['caption'=>'Поиск пользователей, параметры постранички', 'url'=>'user/search', 'params'=>'q=pink&max=1193&count=1', 'method'=>'GET']

            ,['caption'=>'Добавление сообщения для службы поддержки', 'url'=>'user/support', 'params'=>'{"message":"Не могу создать объявление"}']
            ,['caption'=>'Чат со службой поддержки', 'url'=>'user/supportchat', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Чат со службой поддержки, постраничка', 'url'=>'user/supportchat', 'params'=>'max=1315&count=3', 'method'=>'GET']

            ,['caption'=>'Чат со службой поддержки', 'url'=>'faq', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Чат со службой поддержки', 'url'=>'options', 'params'=>'', 'method'=>'GET']

            ,['caption'=>'Состояния (товара)', 'url'=>'catalog/condition', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Цвета', 'url'=>'catalog/color', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Разделы каталога', 'url'=>'catalog/section', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Размеры, все', 'url'=>'catalog/size', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Размеры, для указанного раздела', 'url'=>'catalog/size/16', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Размеры, несуществующий раздел', 'url'=>'catalog/size/1', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Размеры, раздел без размеров', 'url'=>'catalog/size/13', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Способы оплаты', 'url'=>'catalog/payment', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Способы отправки', 'url'=>'catalog/delivery', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Пол', 'url'=>'catalog/gender', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Бренды', 'url'=>'catalog/brand', 'params'=>'', 'method'=>'GET']
            ,['caption'=>'Добавление бренда', 'url'=>'catalog/addbrand', 'params'=>'{"name":"Новый бренд"}']
            ,['caption'=>'Добавление бренда, не задано название', 'url'=>'catalog/addbrand', 'params'=>'{"name":""}']
            ,['caption'=>'Добавление бренда, уже есть', 'url'=>'catalog/addbrand', 'params'=>'{"name":"Versace"}']

            ,['caption'=>'Добавление объявления, ошибки в данных', 'url'=>'ad/add', 'params'=>'{"section":0,"brand":0,"gender":"x","condition":0,"color":0,"size":0,"material":"","features":"","purchase":0,"price":0,"payment":[""],"delivery":{}}']
            ,['caption'=>'Добавление объявления, + файл', 'url'=>'ad/add', 'params'=>'{"section":17,"brand":474,"gender":"w","condition":13,"color":15,"size":65,"material":"кожа","features":"2 раза носил","purchase":20000,"price":10000,"payment":["agreement","application"],"delivery":{"personal":0,"courier":500,"post":400}}']
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