$(function(){

    //아이디 중복 확인 소스
    var memberIdCheck = $('.memberIdCheck');
    var memberId = $('.memberId');
    var memberPw = $('#registerPW');
    var memberPw2 = $('#registerRepeatPassword');
    var memberPw2Comment = $('.memberPw2Comment');
    var memberNickName = $('.memberNickName');
    var memberNickNameComment = $('.memberNickNameComment');
    var memberEmailAddress = $('.memberEmailAddress');
    var memberEmailAddressComment = $('.memberEmailAddressComment');
    var idCheck = $('.idCheck');
    var pwCheck2 = $('.pwCheck2');
    var eMailCheck = $('.eMailCheck');

    memberIdCheck.click(function(){
        alert(memberId.val());

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: './memberIdCheck.php',
            data: {memberId: memberId.val()},

            success: function (json) {
                if(json.res === 'good') {
                    console.log(json.res);
                    alert('사용가능한 아이디 입니다.');
                    idCheck.val('1');
                }else{
                    alert('다른 아이디를 입력해 주세요.');
                    memberId.focus();
                }
            },

            error: function(){
            }
        })
    });

    //비밀번호 동일 한지 체크
    $('#registerPW,#registerRepeatPassword').blur(function(){
        if(memberPw.val() === memberPw2.val()){
            memberPw2Comment.text('비밀번호가 일치합니다.');
            pwCheck2.val('1');
        }else{
            memberPw2Comment.text('비밀번호가 일치하지 않습니다.');
        }
    });

    //이메일 유효성 검사
    memberEmailAddress.blur(function(){
        var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
        if(regex.test(memberEmailAddress.val()) === false){
            memberEmailAddressComment.text('이메일이 유효성에 맞지 않습니다.');
            eMailCheck.val('1');
        }else{
            memberEmailAddressComment.text('올바른 이메일 입니다.');
        }
    });

});

function checkSubmit(){
    var idCheck = $('#registerID');
    var pwCheck2 = $('#registerPW');
    var eMailCheck = $('#registerEmail');
    var memberNickName = $('#registerNickname');

    res = idCheck.val() === '1';
    res = pwCheck2.val() === '1';
    res = eMailCheck.val() === '1';
    res = memberNickName.val() !== '';

    if (res === false) alert('회원가입 폼을 정확히 채워 주세요.');

    return res;
}