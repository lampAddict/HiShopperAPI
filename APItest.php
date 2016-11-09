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
        function sendRequest(req, indx){

            var  _data   = $('#data'+indx).val()
                ,headers = {}
                ,auth    = $('body').data('auth');

            if( auth )
                headers = {
                    'x-auth': auth
                };

            $.ajax({
                url: req,
                method: 'POST',
                headers: headers,
                dataType: 'json',
                contentType: 'application/json; charset=utf-8',
                data: _data,
                success: function(resp){
                    console.log(resp);
                    if( resp ){
                        //show server response
                        $('#response' + indx).text(JSON.stringify(resp));

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

    <?php
        $req = [
             ['caption'=>'Ошибка в URL', 'url'=>'oeritoer', 'params'=>'']
            ,['caption'=>'Авторизация по телефону, пользователь заблокирован', 'url'=>'auth/phone', 'params'=>'{"phone":"79171111111"}']
            ,['caption'=>'Авторизация по телефону, не задан телефон', 'url'=>'auth/phone', 'params'=>'{}']
            ,['caption'=>'Авторизация по телефону, ошибка в формате', 'url'=>'auth/phone', 'params'=>'{"phone":"9170010203"}']
            ,['caption'=>'Авторизация по телефону', 'url'=>'auth/phone', 'params'=>'{"phone":"79170010203"}']
            ,['caption'=>'Ввод кода из sms, неправильный код', 'url'=>'auth/verify', 'params'=>'{"phone":"79170010203","code":"0001","user":2,"device":{"uuid":"0a89df6v7df6sv7r6s07f","pt":"df79b6sd8fbg6","x":320,"y":480}}']
            ,['caption'=>'Ввод кода из sms, неправильный id пользователя', 'url'=>'auth/verify', 'params'=>'{"phone":"79170010203","code":"0001","user":0,"device":{"uuid":"0a89df6v7df6sv7r6s07f","pt":"df79b6sd8fbg6","x":320,"y":480}}']
            ,['caption'=>'Ввод кода из sms', 'url'=>'auth/verify', 'params'=>'{"phone":"79170010203","code":"4145","user":2,"device":{"uuid":"0a89df6v7df6sv7r6s07f","pt":"df79b6sd8fbg6","x":320,"y":480}}']
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
                                                <input type="button" value="Отправить запрос" onclick="sendRequest(\'/hiShopperAPI/'.$rq['url'].'\', '.$c.')"/>
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