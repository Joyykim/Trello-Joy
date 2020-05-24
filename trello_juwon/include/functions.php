<?php
require_once "../include/dbConnect.php";

function makeList($lid, $list_sort, $list_title, $cards, $listCnt) {
    return "
        <div class='items lid bg-gray-200' id='{$lid}'>
            <input type='hidden' class='list_sort' value='{$list_sort}'>
            <span class='m-1 list_title'>{$list_title}</span>
            <span class='m-1 list_cnt'>{$listCnt}</span>
            <input type='text' class='m-1 list_title_edit_area' style='display: none'>
            <span class='dropdown' style='border: 1px solid transparent;'>
                <button class='btn dropdown-toggle dropdown_list' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false''>&#9776;</button>
                <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                    <a class='dropdown-item list_edit_btn btn'>리스트 제목 수정</a>
                    <a class='dropdown-item list_delete_btn btn'>리스트 삭제</a>
                </div>
            </span>

            <ul class='list_my'>
                <div class='list_place'>{$cards}</div>
            </ul>
                <input type='text' class='summernote_card_create form-control mt-2' style='display: none'>
                <button type='button' class='create_card_btn btn btn-primary m-1'><i class='fas fa-plus fa-sm fa-fw mr-2 text-gray-400'></i>카드 추가</button>
                <span type='button' class='update_card_btn btn btn-primary m-1' style='display: none'>저장</span>
                <span type='button' class='un_update_card_btn btn btn-primary m-1' style='display: none'>취소</span>
        </div>
        ";
}

function makeCards($lid, $dbConnect, $index = 0) {

    if ($index == 0) {
        //최초 로딩
        $sql_card = "SELECT * FROM card WHERE lid = {$lid} ORDER BY sort_id LIMIT 10";
    } else {
        //스크롤 로딩
        $sql_card = "SELECT * FROM card WHERE lid = {$lid} ORDER BY sort_id LIMIT {$index},10";
    }
//    $sql_card = "SELECT * FROM card WHERE lid = {$lid} ORDER BY sort_id";

    //카드목록 fetch
    $res_card = $dbConnect->query($sql_card);
    $cards = '';
    while ($row_card = mysqli_fetch_array($res_card)) {

        //카드 제목,ID
        $real_cTitle = $row_card['card_title'];

        //카드 이름 말줄임(...)
        if (mb_strlen($real_cTitle) > 10) {
            $card_title = mb_substr($real_cTitle, 0, 9) . '...';
        } else {
            $card_title = $real_cTitle;
        }

        $cid = $row_card['card_id'];
        $card_info = $row_card['card_info'];
        $created_at = $row_card['created_at'];
        $card_maker = $row_card['uid'];

        $sort_id = $row_card['sort_id'];

        //카드당 액티비티 개수 fetch
        $sql_act = "SELECT * FROM activity WHERE cid = {$cid}";
        $res_act = $dbConnect->query($sql_act);
        $actNum = $res_act->num_rows;

//        cid={$cid}
        $cards .= "
            <li class='m-1'>
                <div class='card card_inList' id='{$cid}'>
                    <input type='hidden' class='card_sort_id' value='{$sort_id}'>
                    <span class='m-0 font-weight-bold text-primary card_title' style='font-size: x-large'>{$card_title}</span>
                    <textarea class='card_update_area' style='display: none'></textarea>
                    <button class='card_update_btn btn' style='display: none'>저장</button>
                    
                    <span class='dropdown' style='border: 1px solid transparent;  position: absolute;right: 0;top: 10px'>
                        <button class='btn dropdown-toggle dropdown_card' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false''>&#9776;</button>
                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                            <a class='dropdown-item card_edit_btn btn'>카드 제목 수정</a>
                            <a class='dropdown-item card_delete_btn btn'>카드 삭제</a>
                        </div>
                    </span>
                    
                    <h6><i class='fas fa-comments text-gray-300'></i>{$actNum}</h6>
                    <input type='hidden' class='cid' value='{$cid}'>
                    <input type='hidden' class='card_info' value='{$card_info}'>
                    <input type='hidden' class='modal_created' value='{$created_at}'>
                    <input type='hidden' class='card_maker' value='{$card_maker}'>
                    <input class='modal_card_title1' type='hidden' value='{$real_cTitle}'>
                </div>
            </li>
            ";
    }
    return $cards;
}

function make_board($bid, $dbConnect)
{
    //리스트 fetch
    $sql_list = "SELECT * FROM list WHERE bid = {$bid} order by sort_id";
    $res_list = $dbConnect->query($sql_list);
    if (!$res_list) {
        echo 'board_list';
        mysqli_error($dbConnect);
    }
    $lists = '';
    while ($row_list = mysqli_fetch_array($res_list)) {
        $lid = $row_list['list_id'];
        $list_sort = $row_list['sort_id'];
        $list_title = $row_list['list_title'];

        // 리스트 내용물 갯수
        $sql_listCnt = "SELECT COUNT(*) as cnt FROM card WHERE lid = {$lid}";
        $listCnt = mysqli_fetch_array($dbConnect->query($sql_listCnt))['cnt'];
        $listCnt = $listCnt > 9999 ? '9999+' : $listCnt;

        $cards = makeCards($lid, $dbConnect);
        $lists .= makeList($lid, $list_sort, $list_title, $cards,$listCnt);
    }
    return $lists;
}