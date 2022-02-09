<style type="text/css">
body { margin: 0; padding: 0;}
.choose_box {position: relative; height:400px; width: 100%;}
.box_size_change {height:900px;}
.choose_box p{padding:13px; line-height:20px;}
.choose_box h3{margin-top: 50px; text-align: center; font-size: 24px;}
.choose_box img{width:100%;}
.choose_box ul li {margin: 20px; list-style: none;}
.choose_box ul li a:hover {background-color: #fff; color: #2791d4; -webkit-transition: 0.7s; -moz-transition: 0.7s; -o-transition: 0.7s; transition: 0.7s;}
.fit {position: absolute; left: 0px; width: 100%; height:100%;}
.q8_p{font-size: 14px; line-height: 20px; margin-top: 35px;}
#q_06 span,#q_07 span {display: block; width:100%;}
#q_06 a,#q_07 a {display: block; width:100%; text-align: center;}
</style>

<div class="choose_box">
<header class="product__cmnhead mt0">
      <h2 class="product__cmntitle txtl">カラー診断</h2>
</header>
	<div id="q_01" class="fit">
	 <h3>＼Question 01／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#q_02" id="ans1_A"><img src="<!--{$TPL_DIR}-->img/q1_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#q_02" id="ans1_B"><img src="<!--{$TPL_DIR}-->img/q1_b.png" alt="肌は赤みが強い"></a></li>
	</ul>
	</div>
	 <!-- 最初の -->
	<div id="q_02" style="display: none;">
	<h3>＼Question 02／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#q_03" id="ans2_A"><img src="<!--{$TPL_DIR}-->img/q2_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#q_03" id="ans2_B"><img src="<!--{$TPL_DIR}-->img/q2_b.png" alt="肌は黄味が強い"></a></li>
	</ul>
	</div>
	<!-- 次の -->
	<div id="q_03" style="display: none;">
	<h3>＼Question 03／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#q_04" id="ans3_A"><img src="<!--{$TPL_DIR}-->img/q3_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#q_04" id="ans3_B"><img src="<!--{$TPL_DIR}-->img/q3_b.png" alt="肌は黄味が強い"></a></li>
	</ul>
	</div>
	<!-- 次の -->
	<div id="q_04" style="display: none;">
	<h3>＼Question 04／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#q_05" id="ans4_A"><img src="<!--{$TPL_DIR}-->img/q4_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#q_05" id="ans4_B"><img src="<!--{$TPL_DIR}-->img/q4_b.png" alt="肌は黄味が強い"></a></li>
	</ul>
	</div>
	<!-- 次の -->
	<div id="q_05" style="display: none;">
	<h3>＼Question 05／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#q_06" id="ans5_A"><img src="<!--{$TPL_DIR}-->img/q5_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#q_06" id="ans5_B"><img src="<!--{$TPL_DIR}-->img/q5_b.png" alt="肌は黄味が強い"></a></li>
	</ul>
	</div>
	<!-- 次の -->
	<div id="q_06" style="display: none;">
	<h3>＼Question 06／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#q_07" id="ans6_A"><img src="<!--{$TPL_DIR}-->img/q6_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#q_07" id="ans6_B"><img src="<!--{$TPL_DIR}-->img/q6_b.png" alt="肌は黄味が強い"></a></li>
	</ul>
	</div>
	<!-- 次の -->
	<div id="q_07" style="display: none;">
	<h3>＼Question 07／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#q_08" id="ans7_A"><img src="<!--{$TPL_DIR}-->img/q7_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#q_08" id="ans7_B"><img src="<!--{$TPL_DIR}-->img/q7_b.png" alt="肌は黄味が強い"></a></li>
	</ul>
	</div>
	<!-- 次の -->
	<div id="q_08" style="display: none;">
	<h3>＼Question 08／</h3>
	<ul class="flexbox fw">
	<li class="even"><a class="color_q_btn" href="#end_01" id="ans8_A"><img src="<!--{$TPL_DIR}-->img/q8_a.png" alt="肌は黄味が強い"></a></li>
	<li class="odd"><a class="color_q_btn" href="#end_01" id="ans8_B"><img src="<!--{$TPL_DIR}-->img/q8_b.png" alt="肌は黄味が強い"></a></li>
	</ul>
	<p class="q8_p">※ お手持ちのストールなどを鏡の前で顔の下に持ってくると、どちらが馴染むか見分けられます</p>
	</div>
	<!-- 答え① -->
	 <div id="end_01" style="display: none;">
	 	<div id="end_01_text"></div>
	 </div>
</div>

<script>
var spring = 0;
var summer = 0;
var autumn = 0;
var winter = 0;

var res = 0;
var ans_text = '';


$(function () {
    $(".color_q_btn").on("click", function () {
        $(this).closest("div").css("display", "none");
        id = $(this).attr("href");
        $(id).addClass("fit").show("slow");

		var ans_id = $(this).prop('id');

        /* 選択肢によって該当それぞれの季節にプラス1 */
        if(ans_id == 'ans1_A'){ spring += 1; autumn += 1;}
        if(ans_id == 'ans1_B'){ summer += 1; winter += 1;}
        
        if(ans_id == 'ans2_A'){ winter += 1; autumn += 1;}
        if(ans_id == 'ans2_B'){ summer += 1; spring += 1;}

        if(ans_id == 'ans3_A'){ summer += 1; winter += 1;}
        if(ans_id == 'ans3_B'){ spring += 1; autumn += 1;}

        if(ans_id == 'ans4_A'){ spring += 1; autumn += 1;}
        if(ans_id == 'ans4_B'){ summer += 1; winter += 1;}

        if(ans_id == 'ans5_A'){ spring += 1; summer += 1;}
        if(ans_id == 'ans5_B'){ autumn += 1; winter += 1;}
        
        if(ans_id == 'ans6_A'){ summer += 1; spring += 1;}
        if(ans_id == 'ans6_B'){ winter += 1; autumn += 1;}

        if(ans_id == 'ans7_A'){ spring += 1; autumn += 1;}
        if(ans_id == 'ans7_B'){ summer += 1; winter += 1;}

    	if(ans_id == 'ans8_A'){
    		spring += 1; autumn += 1;
    		test();

    	}else if(ans_id == 'ans8_B'){
    		summer += 1; winter += 1;
    		test();
    	}
//res = Math.max(spring, summer, autumn, winter);
//alert('最大値は、' +  res);
//alert('春->' + spring + ', 夏->' + summer + ', 秋->' + autumn + ', 冬->' + winter);

    });
});

function test(){
    	var season_array = { 1:spring, 2:summer, 3:autumn, 4:winter }
    	res = Math.max(spring, summer, autumn, winter);
    //結果のページだけページサイズ変更
	$('.choose_box').addClass('box_size_change');

    	for(var i=1; i<5; i++){
    		if(season_array[i] == res){
    			switch(i){
    				case 1: ans_text = '<img src="<!--{$TPL_DIR}-->img/spring_title.png" alt="spring"><img src="<!--{$TPL_DIR}-->img/spring_bg.png" alt="spring"><p>春に咲く花のような、オレンジやイエロー、グリーンなど華やかでぱっと明るいビタミンカラーがぴったり。<br>ネイビーやブラウンなどの落ち着いた色味のドレスを着たいなら、濃い色味のものよりも黄みのある明るめの色を選ぶのがおすすめ。<br>明るい色に挑戦するのが苦手な方なら、ネックレスやイヤリングをキラキラと光るゴールドやパールで合わせると、お顔まわりを明るく見せてくれます。</p> <a class="color_q_btn" href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?category_id=dress&name=sp"><img src="<!--{$TPL_DIR}-->img/spring_btn.png" alt="winter"></a>'; break;

    				case 2: ans_text = '<img src="<!--{$TPL_DIR}-->img/summer_title.png" alt="summer"><img src="<!--{$TPL_DIR}-->img/summer_bg.png" alt="spring"><p>夏の爽やかなイメージの水色やラベンダー、ピンクなどソフトで柔らかな色がぴったり。<br>ブルー系なら水色からネイビーまで幅広く着こなすことができ、シルバーも◎。ベージュ系なら黄みの少ないピンク系のベージュを選ぶのがおすすめ。<br>淡い色を着こなすのが苦手な方は、お顔まわりにシルバーやパールのネックレス、イヤリングを合わせると透明感が出てきれいに見えます。</p> <a class="color_q_btn" href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?category_id=dress&name=su"><img src="<!--{$TPL_DIR}-->img/summer_btn.png" alt="winter"></a>'; break;

    				case 3: ans_text = '<img src="<!--{$TPL_DIR}-->img/autumn_title.png" alt="autumn"><img src="<!--{$TPL_DIR}-->img/autumn_bg.png" alt="spring"><p>秋の景色に溶け込むような、ワインレッドやマスタード、深みのあるグリーンなどこっくりとした色がぴったり。<br>ドレスの中で一番人気のあるネイビーは、4つのシーズンカラーの中でオータムが最も似合います。<br>明るいドレスに挑戦するなら、くすみのあるニュアンスカラーのドレス、アクセサリーは光沢を抑えたものがおすすめ。</p> <a class="color_q_btn" href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?category_id=dress&name=au"><img src="<!--{$TPL_DIR}-->img/autumn_btn.png" alt="winter"></a>'; break;

    				case 4: ans_text = '<img src="<!--{$TPL_DIR}-->img/winter_title.png" alt="winter"><img src="<!--{$TPL_DIR}-->img/winter_bg.png" alt="spring"><p>冬の空気を思わせるきりりとした青、赤、黒などビビット な原色がぴったり。特に「黒色」は4つのシーズンカラーの中でウインターが最も似合います。<br>原色のドレスが苦手ならシルバーのドレスがおすすめ。また淡い色のドレスを着るときは、ボレロやジャケットの色を黒で引き締めると良い。</p> <a class="color_q_btn" href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?category_id=dress&name=wi"><img src="<!--{$TPL_DIR}-->img/winter_btn.png" alt="winter"></a>'; break;
    			}
    		}
    	}
    	$('#end_01_text').append('<img src="<!--{$TPL_DIR}-->img/your_personal_coloris.png" alt="あなたのパーソナルカラーは..">' + ans_text);
}
</script>