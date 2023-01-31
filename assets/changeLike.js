document.getElementById('likeLogoId').addEventListener('click', changeLike);

function changeLike() {

    if (document.getElementById('likeLogoId').classList.contains('like')) {
        document.getElementById('likeLogoId').classList.remove('like');
        document.getElementById('likeLogoId').classList.remove('fa-thumbs-up');
        document.getElementById('likeLogoId').classList.add('dislike');
        document.getElementById('likeLogoId').classList.add('fa-thumbs-down');
    }
    else {
        document.getElementById('likeLogoId').classList.remove('dislike');
        document.getElementById('likeLogoId').classList.remove('fa-thumbs-down');
        document.getElementById('likeLogoId').classList.add('like');
        document.getElementById('likeLogoId').classList.add('fa-thumbs-up');
    }

    let element = document.getElementsByClassName('hiddenlikecheck');
    if (element) {
        if (element[0].value == "1") {
            element[0].value = "0";
        }
        else {
            element[0].value = "1";
        }
    }
}
