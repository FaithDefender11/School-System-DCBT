document.onreadystatechange = function () {
    if (document.readyState !== "complete") {
        document.querySelector("body").style.visibility = "hidden";
        document.getElementById("loading_indicator").style.visibility = "visible";
    } else {
        setTimeout(() => {
            document.getElementById("loading_indicator").style.display = "none";
            document.querySelector("body").style.visibility = "visible";
        }, 3000)
    }
};