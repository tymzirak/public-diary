const mediaFormAction = (mediaId, mediaForm) => {
    mediaForm.querySelector("input[name='media-id']").value = mediaId;
    mediaForm.querySelector("button[name='submit']").click();
};

const deleteAction = (media, deleteForm, message="Do you really want to delete it?") => {
    if (confirm(message)) {
        media.style.display = "none";
        mediaFormAction(media.id, deleteForm);
    }
};

const likeAction = (media, likeIcon, likeAmt, loveForm, state=true) => {
    if (state) {
        likeIcon["src"] = LIKE_IMG["checked"];
        likeAmt.innerText = parseInt(likeAmt.innerText) + 1;
    } else {
        likeIcon["src"] = LIKE_IMG["unchecked"];
        likeAmt.innerText = parseInt(likeAmt.innerText) - 1;
    }
    mediaFormAction(media.id, loveForm);
};

const sendPostFormRequest = (form, actionPage) => {
    form.addEventListener("submit", (evt) => {
        evt.preventDefault();
        sendFormRequest(
            "http://localhost/index.php?page=" + actionPage,
            form,
            "POST"
        );
    });
};

const sendFormRequest = (url, data, method) => {
    let fd = new FormData(data);
    let options = {
        method: method,
        body: fd
    };
    fetch(url, options).catch(error => console.log(error))
};

const addUriParam = (key, valueArg) => {
    let value  = valueArg.toString();
    let uri    = window.location.href;
    let delim  = uri.indexOf("?") == -1 ? "?" : "&";
    let pair   = key + "=" + value.trim().replace(/\s+/g, "+");
    if (uri.indexOf(key + "=") == -1) window.location.href = uri + delim + pair;
    else {
        let regex = new RegExp(key + "=([^&]*)?");
        window.location.href = uri.replace(regex, pair);
    }
};

const getUriParam = (key) => {
    let uri = window.location.href;
    return (uri.indexOf(key + "=") == -1) ? "" : uri.split(key + "=")[1].split("&")[0];
};

const formatDate = (date) => {
    const months = [
        "January", "February", "March",
        "April", "May", "June", "July",
        "August", "September", "October",
        "November", "December"
    ];
    let day         = date.getDate();
    let monthIndex  = date.getMonth();
    let year        = date.getFullYear();

    return months[monthIndex] + " " + day + ", " + year;
};

const addPagin = (evt, paginElem) => {
    let step = parseInt(getUriParam("step"));
    if (paginElem.className.indexOf("pagin-next") != -1) {
        if (!step || step < 0) step = 0;
        paginElem.addEventListener(evt, () => addUriParam("step", ++step));
    } else {
        if (!step || step < 0) step = 1;
        paginElem.addEventListener(evt, () => addUriParam("step", --step));
    }
}

const parseUriMessage = (uriMessage) => {
    return uriMessage.replace("-", " ").toUpperCase();
}

const seeMore = (evt, seeMoreBar, cont, contText, maxHeight="10000px") => {
    let contHeight      = parseInt(getComputedStyle(cont)["height"]);
    let contTextHeight  = parseInt(getComputedStyle(contText)["height"]);
    if (contHeight < contTextHeight) seeMoreBar.style.display = "block";
    seeMoreBar.addEventListener(evt, () => {
        seeMoreBar.style.display = "none";
        cont.style.maxHeight = maxHeight;
    });
}

const getPair = (text, pairName) => {
    let pattern = pairName + "{[a-zA-Z0-9-_]+:[a-zA-Z0-9-_]+}";
    let regex = new RegExp(pattern, "g");
    let result = text.match(regex);
    return result ? result[0] : "";
};

const parsePair = (pair) => {
    let regex = /[a-zA-Z0-9-_]+:[a-zA-Z0-9-_]+/;
    return pair.match(regex)[0].split(":");
};

const setTextPairValue = (text, pair, newPairValue) => {
    let regex = /:[a-zA-Z0-9-_]+/;
    let newPair = pair.replace(regex, ":" + newPairValue);
    return text.replace(pair, newPair);
};

const isChecked = (pair) => parsePair(pair)[1] == 1 ? true : false;

const useCheck = (pair, func_checked, func_unchecked) =>
    isChecked(pair) ? func_checked() : func_unchecked();

const switchCheck = (text, pair, func_checked=()=>{}, func_unchecked=()=>{}) => {
    if (isChecked(pair)) {
        func_unchecked();
        return setTextPairValue(text, pair, 0);
    } else {
        func_checked();
        return setTextPairValue(text, pair, 1);
    }
};
