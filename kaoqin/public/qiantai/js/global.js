/* 获取浏览器查询信息 */
function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}
function sendAjax(url, method, data, callback) {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: 'json',
        success: callback,
        error: callback
    });
}
//时间戳转时间
function timestampToTime(timestamp) {
    var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
    Y = date.getFullYear() + '-';
    M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
    D = (date.getDate() <10 ? '0'+date.getDate() : date.getDate()) + ' ';
    h = (date.getHours() <10 ? '0'+date.getHours() : date.getHours()) + ':';
    m = (date.getMinutes() <10 ? '0'+date.getMinutes() : date.getMinutes()) + ':';
    s = date.getSeconds() <10 ? '0'+date.getSeconds() : date.getSeconds();
    return M+D+h+m+s;
}