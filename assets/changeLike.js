document.getElementById('likeLogoId').addEventListener('click', changeLike);

function changeLike() {
    let element = document.getElementsByClassName('likeCheck');

    if (element) {
        if (element[0].value == "1") {
            element[0].value = "0";
            document.getElementById('likeLogoId').src = "/build/images/thumbs-down-regular.png";
        }
        else {
            element[0].value = "1";
            document.getElementById('likeLogoId').src = "/build/images/thumbs-up-regular.png";
        }
    }
}
