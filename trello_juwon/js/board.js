$(doc());
let i = 1;
function doc() {
    let dropdown = $(".dropdown");

    let bid = $('#bid').val();

    // 리스트 생성 create
    $('#add_list').on('click',function () {
        let add_list = $(this);
        let list_title_input = $('#list_title_input');
        let create_list_btn = $('#create_list');
        let cancel_btn = $('#create_list_cancel');

        add_list.css('display','none');
        list_title_input.css('display','');
        create_list_btn.css('display','');
        cancel_btn.css('display','');


        //엔터키로 리스트 생성
        list_title_input.keyup(function (event) {
            if (event.keyCode === 13){
                let list_title = list_title_input.val();
                make_list(bid,list_title);
            }
        });

        //클릭으로 생성
        create_list_btn.on('click',function () {
            let list_title = list_title_input.val();
            make_list(bid,list_title);
        });

        //생성 취소
        cancel_btn.on('click',function () {
            list_input_refresh()
        });

        function list_input_refresh() {
            add_list.css('display','');
            create_list_btn.css('display','none');
            cancel_btn.css('display','none');
            list_title_input.css('display','none');
            list_title_input.val('');
            create_list_btn.off();
            cancel_btn.off();
        }

        function make_list(bid,list_title){
            $.ajax({
                url: '../board/create_list.php',
                method: "POST",
                async: false,
                data: {
                    bid:bid,
                    list_title:list_title,
                    isPost: true
                },
                success: function (data) {
                    switch (data) {
                        case 'success':
                            refresh_board();
                            break;
                        case 'session_error':
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            return;
                        case 'content_error':
                            break;
                        default:
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            });
            list_input_refresh();
        }
    });




    //리스트 수정,삭제 메뉴
    $(document).off('click', '.dropdown_list').on('click', '.dropdown_list', function () {

        let list = $(this).closest('.lid');
        lid = list.attr('id');
        let list_update_btn = $(this);
        let card_update_area = $(this);
        let this_toggle = $(this).parent(".dropdown");
        let list_title_edit_area = list.find('.list_title_edit_area');
        let list_title = list.find('.list_title');
        let list_cnt = list.find('.list_cnt');

        dropdown.not(this_toggle).removeClass("open");
        this_toggle.toggleClass("open");

        //리스트 수정 edit
        $('.list_edit_btn').on('click', function () {

            dropdown.removeClass("open");
            list_title_edit_area.css('display','');
            list_title.css('display','none');
            list_cnt.css('display','none');
            list_title_edit_area.val(list_title.text());

            return;

            //리스트 수정후 저장 update
            list_update_btn.off().on('click', function () {
                $(this).parent().removeClass("dropdown-menu");
                let edited_title = list_title_edit_area.val();
                $.ajax({
                    url: '../board/update_list.php',
                    method: 'POST',
                    data: {list_id: lid, edited_title: edited_title, isPost: true},
                    success: function (data) {
                        switch (data) {
                            case 'success':
                                refresh_board();
                                break;
                            case 'session_error':
                                alert('로그인 세션이 만료되었습니다');
                                window.location.href = '../main/index.php';
                                break;
                            case 'content_error':
                                break;
                            default:
                                alert(data);
                        }
                        $(document).on('click', '.card_inList', open_modal);
                    },
                    error: function (request, status, error) {
                        alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                        $(document).on('click', '.card_inList', open_modal);
                    }
                });
            });
        });

        //리스트 삭제 delete
        $('.list_delete_btn').off().on('click', function () {
            //리스트 새로고침
            $.ajax({
                url: '../board/delete_list.php',
                method: 'POST',
                data: {list_id: lid, isPost: true},
                success: function (data) {
                    switch (data) {
                        case 'success':
                            refresh_board();
                            break;
                        case 'session_error':
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            break;
                        case 'content_error':
                            break;
                        default:
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    $(document).on('click', '.card_inList', open_modal);
                }
            });
        });
    });

    // 드롭다운 닫기
    $(window).click(function () {
        $(".dropdown").removeClass("open");
    });

    //카드 페이징
    $('.list_my').scroll(function () {
        let listIndex = $(this).parent().index();
        let maxHeight = document.getElementsByClassName('list_my')[listIndex].scrollHeight;
        let currentScroll = $(this).scrollTop() + document.getElementsByClassName('list_my')[listIndex].clientHeight;
        let list_place = $(this).find('.list_place');
        let lid = $(this).parent().attr('id');
        let cardIndex = list_place.children().length;
        if (maxHeight <= currentScroll) {
            $.ajax({
                url: '../board/loadCard.php',
                method: "POST",
                async:false,
                data: {
                    lid:lid,
                    index:cardIndex,
                    isPost: true
                },
                success: function (data) {
                    if (data === 'session_error'){
                        alert('로그인 세션이 만료되었습니다');
                        window.location.href = '../main/index.php';
                        return;
                    }
                    list_place.append(data);
                    // alert(data);
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            });
            doc();
        }
    });


    let sort_lid; //위치되는 list_id
    let next_sort_id;
    let isSend = false;

    //카드 sortable
    $('.list_place').sortable({
        delay: 200,
        connectWith: '.list_place',
        //다른 리스트에서 온 카드를 받을때
        receive: function (event, ui) {
            isSend = true;
            sort_lid = $(this).closest('.lid').attr('id'); //위치되는 list_id

            let sortIndex = ui.item.index(); //위치되는 곳의 인덱스
            next_sort_id = $(this).find(".card_inList").eq(sortIndex + 1).find('.card_sort_id').val(); //위치된 다음카드 cid
        },

        //모든 정렬이 끝날때
        stop: function (event, ui) {
            let sortIndex = ui.item.index(); //위치되는 곳의 인덱스
            let drag_cid = ui.item.find('.card_inList').attr('id'); //드래그 된 카드 sort_id ok


            //다른 리스트에서 왔는지 검사
            if (!isSend) {
                sort_lid = $(this).closest('.lid').attr('id');
                next_sort_id = $(this).find(".card_inList").eq(sortIndex + 1).find('.card_sort_id').val(); //위치된 다음카드 cid
            }
            isSend = false;

            $.ajax({
                url: '../board/switch_card.php',
                method: "POST",
                data: {
                    bid:bid,
                    sorting_cid: drag_cid,
                    next_sort_id: next_sort_id,
                    sort_lid: sort_lid,
                    isPost: true
                },
                success: function (data) {
                    switch (data) {
                        case 'success':
                            refresh_board();
                            break;
                        case 'session_error':
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            return;
                        default:
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            });
            $('#row').sortable("option", "disabled", false);
        }
    }).disableSelection();

    //리스트 sortable
    $('#row').sortable({
        delay: 200,
        distance: 20,
        stop: function (event, ui) {
            let drag_lid = ui.item.attr('id'); //lid ok
            let next_sort_id = $('.lid').eq(ui.item.index() + 1).find('.list_sort').val(); //다음리스트 lid
            let bid = $('#bid').val();

            // alert('drag_lid=' + drag_lid);
            // alert('next_sort_id=' + next_sort_id);

            $.ajax({
                url: '../board/switch_list.php',
                method: "POST",
                data: {
                    bid:bid,
                    sorting_lid: drag_lid,
                    next_sort_id: next_sort_id,
                    isPost: true
                },
                success: function (data) {
                    switch (data) {
                        case 'success':
                            refresh_board();
                            // location.reload();
                            break;
                        case 'session_error':
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            return;
                        default:
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            })
        }
    }).disableSelection();

    let cid;
    let card_title;
    let card_info;
    let modal_created;
    let card_maker;
    let summernote_act_create = $('#summernote_act_create');
    let create_act_btn;



    //모달 새로고침 함수
    function refresh_modal() {

        $('#modal_cid').html('card id : ' + cid);
        $.ajax({
            url: "../board/show_card.php",
            method: "POST",
            data: {cid: cid, card_title: card_title, isPost: true},
            success: function (data) {
                if (data === 'session_error') {
                    alert('로그인 세션이 만료되었습니다');
                    window.location.href = '../main/index.php';
                    return;
                }
                $('#modal_list').html(data);
                $('#modal_card_title').html(card_title);
                $('#myModal').modal("show");
                $('#activity_submit').val(''); //액티비티 내용 비우기
            },
            error: function (request, status, error) {
                alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });
    }

    //리스트 새로고침 함수
    function refresh_list(lid, listPlace) {
        //리스트 새로고침
        $.ajax({
            url: "../board/show_list.php",
            method: "POST",
            data: {lid: lid, isPost: true},
            success: function (data) {
                if (data === 'session_error') {
                    alert('로그인 세션이 만료되었습니다');
                    window.location.href = '../main/index.php';
                    return;
                }
                listPlace.html(data);
            },
            error: function (request, status, error) {
                alert("refresh_list = code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });
    }

    //보드 새로고침
    function refresh_board() {
        $.ajax({
            url: '../board/show_board.php',
            method: 'POST',
            async:true,
            data: {bid: bid, isPost: true},
            success: function (data) {
                if (data === 'session_error') {
                    alert('로그인 세션이 만료되었습니다');
                    window.location.href = '../main/index.php';
                    return;
                }
                $('#row').html(data);
                doc();
            },
            error: function (request, status, error) {
                alert("refresh_board = code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });
    }

    //이미지 파일 저장 함수
    function sendFile(file) {
        let data = new FormData();
        data.append("file", file);
        $.ajax({
            data: data,
            type: "POST",
            // 이미지 업로드하는 파일 path
            url: '../board/save_img.php',
            cache: false,
            contentType: false,
            processData: false,
            success: function (url) {
                summernote_act_create.summernote('editor.insertImage', url);
            },
            error: function (request, status, error) {
                alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });
    }

    //카드모달 열기
    $(document).on('click', '.card_inList', open_modal);

    //비동기 url 변경
    function changeUrl_cid(cid) {
        let url = window.location.href;

        //cid 있음
        if (url.includes('&cid')){
            url = (url.split('&cid'))[0];
        }
        // let data = { 'page': 1, 'id': 5 };
        let data = null;
        let title = 'Hello';
        let newURL = url+'&cid='+cid;
        history.pushState(data, title, newURL);
    }

    $('#myModal').on('hidden.bs.modal', function () {
        let url = window.location.href;
        if (url.includes('&cid')){
            url = (url.split('&cid'))[0];
            let data = { 'page': 1, 'id': 5 };
            let title = 'Hello';
            history.pushState(data, title, url);
        }
    });

    function open_modal() {

        cid = $(this).attr('id');
        card_title = $(this).find('.modal_card_title1').val();
        card_info = $(this).find('.card_info').val();
        modal_created = $(this).find('.modal_created').val();
        card_maker = $(this).find('.card_maker').val();
        create_act_btn = $('#create_act_btn');

        //비동기 url 변경
        changeUrl_cid(cid);
        
        //에디터로 만들고 이미지 콜백함수 정의
        summernote_act_create.summernote({
            focus: true,
            callbacks: {
                onImageUpload: function (files) {
                    for (var i = files.length - 1; i >= 0; i--) {
                        sendFile(files[i]);
                    }
                }
            }
        });

        summernote_act_create.summernote('code', ''); //텍스트창 비우기

        //카드 모달 show
        // $('#modal_cid').html('card id : ' + cid); //카드 아이디 테스트
        $('#card_info').html(card_info);
        $('#modal_created').html(modal_created);
        $('#card_maker').html(card_maker);
        $.ajax({
            url: "../board/show_card.php",
            method: "POST",
            //위에서 클릭한 cid 데이터 전송
            data: {cid: cid, card_title: card_title, isPost: true},
            success: function (data) {
                if (data === 'session_error') {
                    alert('로그인 세션이 만료되었습니다');
                    window.location.href = '../main/index.php';
                    return;
                }
                $('#modal_list').html(data);
                $('#modal_card_title').html(card_title);

                let summernote_update_act = $('.summernote_update_act');
                summernote_update_act.css("display", "none"); //안보이게

                $('#myModal').modal("show");

                //액티비티 생성 create
                create_act_btn.off().on('click', function () {
                    let summernote_create_content = summernote_act_create.summernote('code');
                    $.ajax({
                        url: '../board/create_activity.php',
                        method: 'POST',
                        data: {cid: cid, card_maker: card_maker, activity_submit: summernote_create_content, isPost: true},
                        success: function (data) {
                            switch (data) {
                                case "success":
                                    //에디터 텍스트창 비우기
                                    summernote_act_create.summernote('code', '');
                                    //모달 새로고침
                                    refresh_modal();
                                    break;
                                case "session_error":
                                    alert('로그인 세션이 만료되었습니다');
                                    window.location.href = '../main/index.php';
                                    break;
                                case 'content_error':
                                    alert('잘못된 내용');
                                    summernote_act_create.summernote('code', '');
                                    break;
                                case 'cid_error':
                                    alert('이미 삭제된 게시물입니다');
                                    break;
                                default:
                                    alert(data);
                            }
                        },
                        error: function (request, status, error) {
                            alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                        }
                    });
                }); //액티비티 생성 create 끝
            },
            error: function (request, status, error) {
                alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });


        //액티비티 수정 update
        $(document).on('click', '.edit_act', function () {


            let edit_act = $(this);                                 //수정 버튼 (this)
            let update_act = $(this).parent().find('.update_act');  //저장 버튼
            let delete_act = $(this).parent().find('.delete_act');  //삭제 버튼
            let container = $(this).parent().find('.modal-card');   //modal-card : 에디터, 기존 내용
            let activity_content_place = container.find('.activity_content_place'); //기존 내용 위치
            let summernote_update_act = container.find('.summernote_update_act'); //textarea 태그 : 에디터
            let content = activity_content_place.html();            //액티비티 내용
            let act_id = container.attr('id');                      //액티비티 아이디

            activity_content_place.css('display', 'none'); //기존 내용 숨기기
            edit_act.css('display', 'none'); //수정 버튼 숨기기
            delete_act.css('display', 'none'); //삭제 버튼 숨기기
            summernote_update_act.css('display', ''); //에디터 보이기
            update_act.css('display', ''); //저장버튼 보이기
            summernote_update_act.css('display', 'none'); //에디터 숨기기 @@@@@@@@@@@@@@

            summernote_update_act.summernote({focus: true}); //에디터로 만들기
            summernote_update_act.summernote('code', content); //에디터에 내용 넣기

            //액티비티 수정후 저장
            update_act.on('click', function () {
                let edited_content = summernote_update_act.summernote('code'); //에디터 내용 가져오기

                $.ajax({
                    url: '../board/update_activity.php',
                    method: 'POST',
                    data: {
                        cid: cid,
                        edited_content: edited_content,
                        act_id: act_id,
                        isPost: true
                    },
                    success: function (data) {
                        switch (data) {
                            case 'success':
                                activity_content_place.css('display', ''); //기존 내용 보이기
                                summernote_update_act.summernote('destroy'); //에디터 지우기
                                summernote_update_act.css('display', 'none'); //에디터 숨기기
                                edit_act.css('display', ''); //수정 버튼 보이기
                                delete_act.css('display', ''); //삭제 버튼 보이기
                                update_act.css('display', 'none'); //저장버튼 숨기기

                                //모달 새로고침
                                refresh_modal();
                                break;
                            case 'session_error':
                                alert('로그인 세션이 만료되었습니다');
                                window.location.href = '../main/index.php';
                                break;
                            case 'content_error':
                                break;
                            default :
                                alert('수정에러=' + data);
                        }
                    },
                    error: function (request, status, error) {
                        alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    }
                })
            });
        });

        //액티비티 삭제 delete
        $(document).off('click', '.delete_act').on('click', '.delete_act', function () {
            let act_id = $(this).prev().prev().attr('id');
            $.ajax({
                url: '../board/delete_activity.php',
                method: 'POST',
                data: {cid: cid, act_id: act_id, isPost: true},
                success: function (data) {
                    switch (data) {
                        case 'success':
                            alert('삭제');
                            refresh_modal();
                            break;
                        case 'session_error':
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            break;
                        case 'content_error':
                            break;
                        default:
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            });
        });
    }

    let lid;
    let list;
    let listPlace;
    let summernote_card_create;
    let update_card_btn;
    let un_update_card_btn;
    let create_card_btn;


    //카드 생성 create
    $(document).on('click', '.create_card_btn', function () {

        list = $(this).parent();
        lid = $(this).closest('.lid').attr('id');
        summernote_card_create = list.find('.summernote_card_create');
        update_card_btn = list.find('.update_card_btn');
        un_update_card_btn = list.find('.un_update_card_btn');
        create_card_btn = $(this);

        $(this).css('display', 'none'); //생성 버튼 숨기기
        update_card_btn.css('display', ''); //저장 버튼 보이기
        un_update_card_btn.css('display', ''); //저장 버튼 보이기
        summernote_card_create.css('display', ''); //에디터 보이기

        //카드 저장
        update_card_btn.off().on('click', function () {
            let card_create_title = summernote_card_create.val(); //카드제목
            $.ajax({
                url: '../board/create_card.php',
                method: 'POST',
                data: {
                    lid: lid,
                    card_create_title: card_create_title,
                    isPost: true
                },

                success: function (data) {
                    switch (data) {
                        case 'success':
                            create_card_btn.css('display', ''); //생성 버튼 보이기
                            update_card_btn.css('display', 'none'); //저장 버튼 숨기기
                            un_update_card_btn.css('display', 'none'); //취소 버튼 숨기기
                            summernote_card_create.css('display', 'none'); //에디터 숨기기
                            listPlace = list.find('.list_place');
                            //리스트 새로고침
                            refresh_list(lid, listPlace);
                            summernote_card_create.val(''); //에디터 비우기
                            break;
                        case 'session_error':
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            break;
                        case 'content_error':
                            break;
                        default :
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                }
            })
        });
        un_update_card_btn.off().on('click', function () {
            un_update_card_btn.css('display', 'none');  //취소 버튼 숨기기
            create_card_btn.css('display', ''); //생성 버튼 보이기
            update_card_btn.css('display', 'none'); //저장 버튼 숨기기
            summernote_card_create.val(''); //에디터 비우기
            summernote_card_create.css('display', 'none'); //에디터 숨기기
        })
    });

    //카드 수정,삭제 메뉴
    $(document).off('click', '.dropdown_card').on('click', '.dropdown_card', function () {

        lid = $(this).closest('.lid').attr('id');
        listPlace = $(this).closest('.lid').find('.list_place');

        let parent = $(this).parent(".dropdown");
        $('.dropdown').not(parent).removeClass("open");
        parent.toggleClass("open");

        // let card_edit_btn = $(this).next().find('.card_edit_btn');
        // let card_delete_btn = $(this).next().find('.card_delete_btn');

        $(document).off('click', '.card_inList'); //모달 생성 방지

        //카드 수정 edit
        $(document).off('click', '.card_edit_btn').on('click', '.card_edit_btn', function () {

            parent.css('display','none');
            $(".dropdown").removeClass("open");
            let card = $(this).closest('.card_inList');

            let card_id = card.attr('id');
            let card_update_area = card.find('.card_update_area');
            let card_update_btn = card.find('.card_update_btn');
            let ex_card_title = card.find('.modal_card_title1').val();
            let card_title = card.find('.card_title');

            card_update_area.val(ex_card_title);
            card_update_area.css('display', '');
            card_update_btn.css('display', '');
            card_title.css('display', 'none');

            //카드 수정후 저장 update
            card_update_btn.off().on('click', function () {
                $(this).parent().removeClass("dropdown-menu");
                let edited_title = card_update_area.val();
                $.ajax({
                    url: '../board/update_card.php',
                    method: 'POST',
                    data: {card_id: card_id, edited_title: edited_title, isPost: true},
                    success: function (data) {
                        switch (data) {
                            case 'success':
                                // alert('수정성공');
                                //리스트 새로고침
                                refresh_list(lid, listPlace);
                                // location.reload();
                                break;
                            case 'session_error':
                                alert('로그인 세션이 만료되었습니다');
                                window.location.href = '../main/index.php';
                                break;
                            case 'content_error':
                                break;
                            default:
                                alert(data);
                        }
                        $(document).off('click', '.card_inList', open_modal).on('click', '.card_inList', open_modal);
                    },
                    error: function (request, status, error) {
                        alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                        $(document).on('click', '.card_inList', open_modal);
                    }
                });
                parent.css('display','');
            });
        });

        //카드 삭제 delete
        $('.card_delete_btn').off().on('click', function () {
            //수정중인 카드는 클릭해도 모달 생성안함
            $(document).off('click', '.card_inList');
            $(".dropdown").removeClass("open");

            let card_id = $(this).closest('.card_inList').attr('id');

            //리스트 새로고침
            $.ajax({
                url: '../board/delete_card.php',
                method: 'POST',
                data: {card_id: card_id, isPost: true},
                success: function (data) {
                    switch (data) {
                        case 'success':
                            //리스트 새로고침
                            refresh_list(lid, listPlace);
                            $(document).on('click', '.card_inList', open_modal);
                            break;
                        case 'session_error':
                            alert('로그인 세션이 만료되었습니다');
                            window.location.href = '../main/index.php';
                            break;
                        case 'content_error':
                            break;
                        default:
                            alert(data);
                    }
                },
                error: function (request, status, error) {
                    alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
                    $(document).on('click', '.card_inList', open_modal);
                }
            });
        });

    });

    //멤버 초대 invite
    $('#invite_btn').on('click', function () {
        let invite_id = $('#invite_content').val();
        let bid = $('#bid').val();

        $.ajax({
            url: '../user/memberInvite.php',
            method: 'POST',
            data: {
                invite_id: invite_id,
                bid: bid,
                isPost: true
            },
            success: function (data) {
                switch (data) {
                    case 'success':
                        alert(invite_id + '님을 초대했습니다');
                        location.reload();
                        break;
                    case 'session_error':
                        alert('로그인 세션이 만료되었습니다');
                        window.location.href = '../main/index.php';
                        break;
                    default:
                        alert(data);
                }
            },
            error: function (request, status, error) {
                alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        })
    });

    //입장시 cid 체크후 모달 트리거
    let url = window.location.href;
    if (url.includes('&cid')){
        let cid = (url.split('&cid='))[1];
        cid = cid * 1;

        //카드가 존재하는지 검사
        $.ajax({
            url: '../board/check_cid.php',
            method: 'POST',
            data: {
                cid: cid,
                isPost: true
            },
            success: function (data) {
                switch (data) {
                    case 'success':
                        //카드가 있다면 클릭이벤트 트리거
                        $('.card_inList').filter('#'+cid).trigger('click');
                        break;
                    case 'fail':
                        //카드 존재하지 않음
                        let url = (window.location.href.split('&cid'))[0];
                        history.pushState(null, 'Hello', url);
                        alert('삭제되었거나 존재하지 않는 카드입니다');
                        break;
                    case 'session_error':
                        alert('로그인 세션이 만료되었습니다');
                        window.location.href = '../main/index.php';
                        break;
                    default:
                        alert(data);
                }
            },
            error: function (request, status, error) {
                alert("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }
        });
    }
}