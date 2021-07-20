<?php

function convertLink($str)
{
    $pattern = '/\[\[(.*?)\]\]/';
    $replace = "<a href=\"/questions?answer=$1&question=1&like=1\">$1</a>";
    $converted = preg_replace($pattern, $replace, $str);
    return $converted;
}