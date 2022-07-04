
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Chat Message Module</title>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<link rel="stylesheet" href="https://mdbcdn.b-cdn.net/wp-content/themes/mdbootstrap4/docs-app/css/compiled-4.20.0.min.css">
</head>

<body>
  <div class="container spark-screen">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Chat Message Module<button type="button" id="connect_status" class="btn btn-danger btn-sm">未連線</button ><button type="button" class="btn btn-info btn-sm" onclick="location.reload();refreshForMessageArea();">重新整理</button ></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-8" >
                            <div id="messages" style="border: 1px solid #121212; margin-bottom: 0px; height: 250px; padding: 10px; overflow: scroll;"></div>
                        </div>
                        <div class="col-lg-8" >
                            <form action="sendmessage" method="POST">
                                <input type="hidden" name="user" value="" >
                                <textarea class="form-control message" placeholder="輸入文字"></textarea>
                                <div style="float:left;">
                                    <input type="button" value="發送留言" style="border-radius: 10px; font-size:14px;" class="btn btn-primary btn-sm" id="send-message" >
                                </div>
                                <div style="float:left;padding-top:16px;">
                                    <div id="error" style="color:red;"></div>
                                </div>
                                <div style="clear:both;"></div>
                            </form>
                            (CTRL+ENTER直接送出)
                            <!-- Default checked -->
                            <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" name="is_insert" id="is_insert">
                            <label class="custom-control-label" for="is_insert"><span id="is_insert_status">不寫入資料庫(重整即消失)</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/cons.js')}}"></script>
<script src="{{ asset('js/func.js')}}"></script><script src="{{ asset('js/func.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.1.2/socket.io.js"></script>
<script src="https://getbootstrap.com/docs/5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

// 建立 WebSocket (本例 server 端於本地運行)
var ws = new WebSocket(websocketUrl)
var txtHtml = '';
var _is_insert = '';
var today = new Date();

// 監聽連線開啟
ws.onopen = () => {
    $("#connect_status").removeClass('btn btn-danger btn-sm').addClass('btn btn-success btn-sm').text('已連線');
    getMsgAjax();
}

// 監聽連線關閉
ws.onclose = () => {
    $("#connect_status").text('未連線');
}

//接收 Server 發送的訊息
ws.onmessage = event => {
    let txt = event.data;

    //顯示訊息
    displayMessageArea(txt);

    //更新顯示文字框
    refreshForMessageArea();
}

// 單選切換
$("#is_insert").click(function() {
    _is_insert = $("#is_insert").prop('checked');
    if (_is_insert == true) {
        $("#is_insert_status").text('寫入資料庫');
    } else {
        $("#is_insert_status").text('不寫入資料庫(重整即消失)');
    }
});


// 按 CTRL + ENTER
$(document).keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '10'){
        sendMsg();
    }
});

// 按鈕點擊
$("#send-message").click(function(e){
    e.preventDefault();

    $(this).attr('disabled', true);
    sendMsg();
});
</script>

</body>

</html>