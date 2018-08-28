
var obj={
    getWindowSize: function () {
        var win = { width: 0, height: 0 };
        var sindon_winDiv = document.getElementById("sindon_winDiv");
        if (sindon_winDiv === undefined || sindon_winDiv === null) {
            var d = document.createElement("div"),
                body = document.getElementsByTagName("body")[0];
            d.id = "sindon_winDiv";
            d.style.position = "fixed";
            d.style.zIndex = 0;
            d.style.width = "100%";
            d.style.height = "100%";
            d.style.left = "0px";
            d.style.top = "0px";
            body.appendChild(d);
            win.width = parseInt(d.clientWidth);
            win.height = parseInt(d.clientHeight);
            body.removeChild(d);
        }
        return win;
    },
    //设置pagerem
    setPageRem: function (winW, size) {
        var html = document.getElementsByTagName("html")[0];
        html.style.fontSize = winW * 100 / size + "px";
    }
};
    var winSize = obj.getWindowSize();
    // alert(winSize.width)
    obj.setPageRem(winSize.width,750);