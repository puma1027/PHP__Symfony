/*------------------------------------------
お気に入りを登録する
------------------------------------------*/
function fnAddFavoriteSphone(favoriteProductId) {
    $.mobile.showPageLoadingMsg();
    //送信データを準備
    var postData = {};
    $("#form1").find(':input').each(function(){  
        postData[$(this).attr('name')] = $(this).val();  
    });
    postData["mode"] = "add_favorite_sphone";
    postData["favorite_product_id"] = favoriteProductId;

    $.ajax({
           type: "POST",
           url: $("#form1").attr('action'),
           data: postData,
           cache: false,
           dataType: "text",
           error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(textStatus);
            $.mobile.hidePageLoadingMsg();
           },
           success: function(result){
              if (result == "true") {
                  alert("お気に入りに登録しました");
                  $(".favorite").html('<a href="javascript:void(0);fnDelFavoriteSphone('+favoriteProductId+' ); "><img src="../user_data/packages/sphone/img/favorite_on.png" alt="お気に入り削除" width="70px"/></a>');
              } else {
                  alert("お気に入りの登録に失敗しました");
              }
              $.mobile.hidePageLoadingMsg();
           }
    });
}

function fnDelFavoriteSphone(favoriteProductId) {
    $.mobile.showPageLoadingMsg();
    //送信データを準備
    var postData = {};

    $("#form1").find(':input').each(function(){
        postData[$(this).attr('name')] = $(this).val();
    });
    postData["mode"] = "del_favorite_sphone";
    postData["favorite_product_id"] = favoriteProductId;

    $.ajax({
           type: "POST",
           url: $("#form1").attr('action'),
           data: postData,
           cache: false,
           dataType: "text",
           error: function(XMLHttpRequest, textStatus, errorThrown){
            alert(textStatus);
            $.mobile.hidePageLoadingMsg();
           },
           success: function(result){
              if (result == "true") {
                  alert("お気に入りを削除しました");
                  $(".favorite").html('<a href="javascript:void(0);fnAddFavoriteSphone('+favoriteProductId+' ); "><img src="../user_data/packages/sphone/img/favorite_off.png" alt="お気に入り" width="70px"/></a>');
			} else {
                  alert("お気に入りの削除に失敗しました");
              }
              $.mobile.hidePageLoadingMsg();
           }
    });
}
