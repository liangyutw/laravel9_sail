
/**
 * 顯示框往上滑
 */
function refreshForMessageArea(){
    $('#messages').scrollTop($('#messages').height());
}

/**
 * ajax取得訊息
 */
function getMsgAjax(){
    $.ajax({
        type: "GET",
        url: getMsgUrl,
        dataType: "json",
        beforeSend: function( xhr ) {
            $('#messages').text("資料讀取中...");
        },
        success:function(data) {

            if (data == '') {
                $('#messages').text("查無資料");
                return;
            }

            $('#messages').text("");
            ws.send(JSON.stringify(data));
        }
    });
}

/**
 * ajax發到後端api
 * @param {mix} message
 */
function sendMsgAjax(message){
    $.ajax({
        type: "POST",
        url: sendMsgUrl,
        dataType: "json",
        data: {
            'message': message
        },
        success:function(data) {
            if (data == false) {
                $("#success").text('');
                $("#error").text('訊息發送失敗!');
                return;
            }

            $(".message").val('');
            $("#error").text('');

            ws.send(JSON.stringify([data]));
        }
    });
}

/**
 * 倒數秒數
 * @param {int} sec
 */
function countDown(sec){
    var timer = null;
    timer = setInterval(function () {
        if (sec > 0) {
            sec--;
            $('#send-message').val('發送留言 ('+sec+')');
            $("#error").html('<b>錯誤! 請輸入文字 ('+sec+')</b>');
        } else {
            clearInterval(timer);
            $("#error").html('');
            $('#send-message').val('發送留言');
            $('#send-message').attr('disabled', false);
        }
    }, 1000);
}

/**
 * 輸入空白顧示訊息
 */
function blankMsg(){
    $("#error").html('<b>錯誤! 請輸入文字</b>');
    $('#send-message').attr('disabled', true);
}

/**
 * 編碼
 * @param {string} s
 * @returns
 */
function htmlencode(s){
    var div = document.createElement('div');
    div.appendChild(document.createTextNode(s));
    return div.innerHTML;
}

/**
 * 解碼
 * @param {string} s
 * @returns
 */
function htmldecode(s){
    var div = document.createElement('div');
    div.innerHTML = s;
    return div.innerText || div.textContent;
}

/**
 * 左邊補0
 * @param {string} str 字串
 * @param {int} lengh 總數
 * @returns
 */
function padLeft(str, lengh){
    if(str.toString().length < lengh) {
        return padLeft("0" +str, lengh);
    } else {
        return str;
    }
}

/**
 * 輸入框的文字
 * @param {string} message
 */
function notInertDB(message){
    var date = today.getFullYear()+'-'+padLeft(today.getMonth()+1, 2)+'-'+padLeft(today.getDate(), 2)+' '+padLeft(today.getHours(), 2)+':'+padLeft(today.getMinutes(), 2)+':'+padLeft(today.getSeconds(), 2);

    ws.send(JSON.stringify([{
        'content': message,
        'created_at': date
    }]));
}

/**
 * 解析json，顯示排列樣式
 * @param {json} txt
 */
function displayMessageArea(txt){
    if ($('#messages').text() == '查無資料')
    {
        $("#messages").text('');
    }
    var jsonData = JSON.parse(txt)

    for (i in jsonData) {
        var messageHtml = '<div style="background-color:#eaeaea; border-radius: 10px; padding:10px; margin:3px; min-width:150px; float:left;">'+decodeURI(jsonData[i].content)+'</div>';
        var dateHtml = '<div style="font-size:14px; padding-top:15px; min-width:90px; float:left;">-'+jsonData[i].created_at+'</div>';
        var clearHtml = '<div style="clear:both;"></div>';
        $("#messages").append(messageHtml+dateHtml+clearHtml);
    }

    $('#send-message').attr('disabled', false);
}

/**
 * 發送訊息
 * @returns
 */
function sendMsg(){
    var message = $(".message").val();
    var isHtml = /<\/?[a-z][\s\S]*>/i.test(message);

    message = (isHtml) ? htmlencode(message) : message.replace(/\n/g,"<br>");

    // 不可提交空白
    if(message == ''){
        blankMsg();
        return countDown(3);
    }

    $(".message").val('');
    $("#error").text('');

    // 寫入資料庫
    if (_is_insert == true) {
        return sendMsgAjax(message);
    }

    // 不寫入資料庫
    return notInertDB(message);
}