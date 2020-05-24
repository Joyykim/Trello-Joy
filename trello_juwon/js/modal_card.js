//모달
var modal = document.getElementsByClassName("myModal");
var cards = document.getElementsByClassName("modal-card");
var span = document.getElementsByClassName("myclose")[0];

for (var i = 0; i < cards.length; i++) {
    cards[i].onclick = function (i) {
        return function (ind){
            value = $(this).find('.cid').val();
            document.getElementById("modal_con").innerHTML = "cid:"+value;
            // document.getElementById("modal_list").innerHTML = ;
            modal[ind].style.display = "block";
        }(i);

    };
}

// X 클릭
span.onclick = function () {
    modal.style.display = "none";
};
// 모달 바깥 아무곳이나 클릭
window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};
