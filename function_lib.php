<?php
function replaceSpecialChar($strParam)
{ //过滤特殊字符
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\s|\.|\/|\;|\'|\`|\=|\\\|\|/";
    return preg_replace($regex, "", $strParam);
}

function getIP()
{
    return isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : (isset($_SERVER["HTTP_CLIENT_IP"]) ? $_SERVER["HTTP_CLIENT_IP"] : $_SERVER["REMOTE_ADDR"]);
}

