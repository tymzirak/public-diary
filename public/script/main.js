const medias = document.querySelectorAll(".media");
if (medias)
    for (let media of medias) {
        let mediaCont      = media.querySelector(".media-cont");
        let mediaContText  = media.querySelector(".see-more-text");
        let seeMoreBar     = media.querySelector(".see-more");
        let mediaText      = media.querySelector(".media-text");
        let likeIcon       = media.querySelector(".like-icon");

        useCheck(
            getPair(likeIcon.className, "check"),
            () => likeIcon["src"] = LIKE_IMG["checked"],
            () => likeIcon["src"] = LIKE_IMG["unchecked"]
        );

        if (mediaText)
            mediaText.innerText = mediaText.innerText.replace(new RegExp(NEWLINER, "g"), "\n");

        if (mediaCont) seeMore("mousedown", seeMoreBar, mediaCont, mediaContText);
    }

const posts           = document.querySelectorAll(".post");
const postLoveForm    = document.querySelector(".post-like-form");
const postDeleteForm  = document.querySelector(".post-delete-form");
if (posts && postDeleteForm && postLoveForm) {
    sendPostFormRequest(postLoveForm, "post/like_post.php");
    sendPostFormRequest(postDeleteForm, "post/delete_post.php");
    for (let post of posts) {
        let postDeleteBtn  = post.querySelector(".post-del-btn");
        let likeBtn        = post.querySelector(".like-btn");
        let likeIcon       = post.querySelector(".like-icon");
        let likeAmt        = post.querySelector(".like-amt");

        likeBtn.addEventListener("click", () =>
            likeIcon.className = switchCheck(
                likeIcon.className,
                getPair(likeIcon.className, "check"),
                () => likeAction(post, likeIcon, likeAmt, postLoveForm),
                () => likeAction(post, likeIcon, likeAmt, postLoveForm, false)
            )
        );

        if (postDeleteBtn)
            postDeleteBtn.addEventListener("click", () => deleteAction(post, postDeleteForm));
    }
}

const comments           = document.querySelectorAll(".comment");
const commentLoveForm    = document.querySelector(".comment-like-form");
const commentDeleteForm  = document.querySelector(".comment-delete-form");
if (comments && commentLoveForm && commentDeleteForm) {
    sendPostFormRequest(commentLoveForm, "comment/like_comment.php");
    sendPostFormRequest(commentDeleteForm, "comment/delete_comment.php");
    for (let comment of comments) {
        let commentDeleteBtn  = comment.querySelector(".comment-del-btn");
        let likeBtn           = comment.querySelector(".like-btn");
        let likeIcon          = comment.querySelector(".like-icon");
        let likeAmt           = comment.querySelector(".like-amt");

        likeBtn.addEventListener("click", () =>
            likeIcon.className = switchCheck(
                likeIcon.className,
                getPair(likeIcon.className, "check"),
                () => likeAction(comment, likeIcon, likeAmt, commentLoveForm),
                () => likeAction(comment, likeIcon, likeAmt, commentLoveForm, false)
            )
        );

        if (commentDeleteBtn)
            commentDeleteBtn.addEventListener("click", () => deleteAction(comment, commentDeleteForm));
    }
}

const dates = document.querySelectorAll(".date");
for (let date of dates) date.innerText = formatDate(new Date(date.innerText));

const searchForm = document.querySelector(".form-search");
if (searchForm)
    searchForm.addEventListener("submit", (evt) => {
        evt.preventDefault();
        addUriParam("search", searchForm.querySelector(".main-input").value);
    });

const paginArrows = document.querySelectorAll(".pagin-arrow");
if (paginArrows)
    for (let paginArrow of paginArrows) addPagin("click", paginArrow);

const dropdownUriParams = document.querySelectorAll(".dropdown-uriparam");
if (dropdownUriParams)
    for (let dropdownUriParam of dropdownUriParams)
        dropdownUriParam.addEventListener("click", (evt) => {
            if (!getPair(evt.target.className, "uriparam")) return;

            let uriParamPair = parsePair(getPair(evt.target.className, "uriparam"));
            addUriParam(uriParamPair[0], uriParamPair[1]);
        });

const errorUriParam = getUriParam("error");
if (errorUriParam) alert(parseUriMessage(errorUriParam));
