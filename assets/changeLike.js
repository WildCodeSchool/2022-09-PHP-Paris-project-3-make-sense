document.getElementById('likeLogoId').addEventListener('click', changeLike);

function changeLike() {
    if (document.getElementById('likeLogoId').classList.contains('like')) {
        document.getElementById('likeLogoId').classList.remove('like');
        document.getElementById('likeLogoId').classList.add('dislike');
    }
    else {
        document.getElementById('likeLogoId').classList.remove('dislike');
        document.getElementById('likeLogoId').classList.add('like');
    }

    let element = document.getElementsByClassName('hiddenLikeCheck');
    if (element) {
        if (element[0].value == "1") {
            element[0].value = "0";
        }
        else {
            element[0].value = "1";
        }
    }
}
