<?php
require_once './Snoopy.class.php';

$snoopy = new Snoopy;

$mobile_url = 'https://news.naver.com/main/list.nhn?mode=LS2D&mid=shm&sid1=105&sid2=731';

$snoopy->fetch('https://media.daum.net/digital/');
echo $snoopy->results;

preg_match_all('/<ul class="type01">(.*?)<\/ul>/is', $snoopy->results, $text);

//이제 결과를 보면...?
//var_dump($text);