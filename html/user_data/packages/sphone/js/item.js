  var pageNo = 2;
  var url = "<!--{$smarty.const.P_DETAIL_URLPATH}-->";
  var imagePath = "<!--{$smarty.const.IMAGE_SAVE_URLPATH|sfTrimURL}-->/";
  var statusImagePath = "<!--{$TPL_URLPATH}-->";
	var tpl_date1 = "<!--{$tpl_date1}-->";
	var tpl_date2 = "<!--{$tpl_date2}-->";
	
    function getProducts(limit) {
        $.mobile.showPageLoadingMsg();
        var i = limit;
        //送信データを準備
        var postData = {};
        $('#form1').find(':input').each(function(){
            postData[$(this).attr('name')] = $(this).val();
        });
        postData["mode"] = "";
        postData["call_type"] = "json";
        postData["pageno"] = pageNo;

        $.ajax({
            type: "POST",
            data: postData,
            url: "<!--{$smarty.server.REQUEST_URI|escape}-->",
            cache: false,
            dataType: "json",
            error: function(XMLHttpRequest, textStatus, errorThrown){
                alert(textStatus);
                $.mobile.hidePageLoadingMsg();
            },
            success: function(result){
                var productStatus = result.productStatus;

                for (var product_id in result) {
                    if (isNaN(product_id)) continue;
                    var product = result[product_id];
                    var productHtml = "";
                    var maxCnt = $(".list_area").length - 1;
                    var productEl = $(".list_area").get(maxCnt);
                    productEl = $(productEl).clone(true).insertAfter(productEl);
                    maxCnt++;

                    //商品写真をセット
                    $($(".list_area .listphoto img").get(maxCnt)).attr({
                        src: "<!--{$smarty.const.ROOT_URLPATH}-->resize_image.php?image=" + product.main_list_image + '&width=80&height=80',
                        alt: product.name
                    });

                    // 商品ステータスをセット
                    var statusAreaEl = $($(".list_area div.statusArea").get(maxCnt));
                    // 商品ステータスの削除
                    statusAreaEl.empty();

                    if (productStatus[product.product_id] != null) {
                        var statusEl = '<ul class="status_icon">';
                        var statusCnt = productStatus[product.product_id].length;
                        for (var k = 0; k < statusCnt; k++) {
                            var status = productStatus[product.product_id][k];
                            var statusImgEl = '<li>' + status.status_name + '</li>' + "\n";
                            statusEl += statusImgEl;
                        }
                        statusEl += "</ul>";
                        statusAreaEl.append(statusEl);
                    }

                    //商品名をセット
                    $($(".list_area a.productName").get(maxCnt)).text(product.name);
                    $($(".list_area a.productName").get(maxCnt)).attr("href", url + product.product_id + "&date1=" + tpl_date1 + "&date2=" + tpl_date2);

                    //販売価格をセット
                    var price = $($(".list_area span.price").get(maxCnt));
                    //販売価格をクリア
                    price.empty();
                    var priceVale = "";
                    //販売価格が範囲か判定
                    /*if (product.price02_min == product.price02_max) {
                        priceVale = product.price02_min_tax_format + '円';
                    } else {
                        priceVale = product.price02_min_tax_format + '～' + product.price02_max_tax_format + '円';
                    }*/

                    /* N00083 Change 20131201 */
                    var word = product.product_code,
                        target = "01-";
                    var str = " " + word;
                    if (str.indexOf(" " + target) !== -1) {
                        priceVale = "3泊4日間 8,980円〜";
                    } else {
                    priceVale = "3泊4日間 " + product.price02_min_tax_format + '円';
                    }
                    price.append(priceVale);
                    /* N00083 end 20131201 */

                    //コメントをセット
                    $($(".list_area .listcomment").get(maxCnt)).text("商品コード：" + product.product_code);

                    // review set
                    var reviewArea = $($(".list_area div.pw_area02 div.text01").get(maxCnt));
                    reviewArea.empty();

                    var review_star = '<span class="yellow">';
                    for(var i = 0 ; i < product.womens_review_avg; i++){
                    	review_star += '<span class="star">★</span>';
                    }
                    for(var i=product.womens_review_avg;i<5; i++){
                    	review_star += '<span class="star_gray">★</span>';
                    }
                    review_star += '</span>';

                    var review_avg = '<label style="font-size: 12px;">' + product.womens_review_avg +'</label>';
                    var review_count = '<label style="font-size: 12px;">(' + product.womens_review_count +')</label>';

                    reviewArea.append(review_star);
                    reviewArea.append(review_avg);
                    reviewArea.append(review_count);
                }
                pageNo++;

                //すべての商品を表示したか判定
                if (parseInt($("#productscount").text()) <= $(".list_area").length) {
                    $("#btn_more_product").hide();
                }
                $.mobile.hidePageLoadingMsg();
            }
        });
    }