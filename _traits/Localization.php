<?php

namespace _traits;

trait Localization{

    private $ru = [
                     'color_turquoise'=>'морская волна'
                    ,'color_black'=>'черный'
                    ,'color_blue'=>'голубой'
                    ,'color_fuchsia'=>'фуксия'
                    ,'color_gray'=>'серый'
                    ,'color_green'=>'зеленый'
    ];

    private $en = [
                     'color_turquoise'=>'Aqua'
                    ,'color_black'=>'black'
                    ,'color_blue'=>'blue'
                    ,'color_fuchsia'=>'fuchsia'
                    ,'color_gray'=>'gray'
                    ,'color_green'=>'green'
    ];

    function getText($word, $lang='ru'){
        if( !isset($this->{$lang}) )return '';
        if( !isset($this->{$lang}[$word]) )return ' ';
        return $this->{$lang}[$word];
    }
}