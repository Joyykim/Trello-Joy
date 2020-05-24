<?php
$client_id = "ijMC5oLDieOuk2rXfb9B";
$client_secret = "wWDiXfM9zA";

$encText = urlencode("모바일"); //검색할 Text를 url인코딩

/*요청 변수(query, display, start, sort... 네이버 참고)
  json ver: https://openapi.naver.com/v1/search/book.json?...
  xml ver : https://openapi,naver.com/v1/search/book.xml?...
*/
$url = "https://openapi.naver.com/v1/search/news.json?query=" . $encText;

$is_post = false;

/*
curl = 원하는 url에 값을 넣고 리턴되는 값을 받아오는 함수
*/
$ch = curl_init(); //세션 초기화

/*curl_setopt : curl옵션 세팅
  CURLOPT_URL : 접속할 url
  CURLOPT_POST : 전송 메소드 Post
  CURLOPT_RETURNTRANSFER : 리턴 값을 받음
*/
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = array();
$headers[] = "X-Naver-Client-Id: " . $client_id;
$headers[] = "X-Naver-Client-Secret: " . $client_secret;

/*
  CURLOPT_HTTPHEADER : 헤더 지정 (네이버 api를 사용하려면 필요하다)
  CURLOPT_SSL_VERIFYPEER : 인증서 검사x
  https 접속시 필요
*/

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

/*
  curl_exec : 실행
  curl_getinfo : 전송에 대한 정보
  CURLINFO_HTTP_CODE : 마지막 HTTP 코드 수신
*/
$response = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo "status code: " . $status_code . "";

curl_close($ch);
if ($status_code == 200) { //정상
//    echo $response;

    // 다차원 배열 반복처리
    $R = new RecursiveIteratorIterator(
        new RecursiveArrayIterator(json_decode($response, TRUE)),
        RecursiveIteratorIterator::SELF_FIRST);
// $R : array data
// json_decode : JSON 문자열을 PHP 배열로 바꾼다
// json_decode 함수의 두번째 인자를 true 로 설정하면 무조건 array로 변환된다.

    foreach ($R as $key => $val) {
        if (is_array($val)) { // val 이 배열이면
//            echo "$key:<br/>";
            //echo $key.' (key), value : (array)<br />';
        } else { // 배열이 아니면
            echo "$key => $val <br />";
        }
    }


} else {
    echo "Error 내용: " . $response;
}
?>