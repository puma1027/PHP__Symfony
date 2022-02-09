<div class="bg_pink" style="padding:0; font-size:0;">
	<div style="width: 960px; margin: 0 auto; padding: 10px 0 0;">
	<div class="item_num_pc">
		<div class="scheduleddatebox_pc pc_show"><span class="scheduleddatebox__label_pc">最短のお届け予定日：</span><span class="scheduleddatebox__date_pc"><a href="<!--{$smarty.const.URL_DIR}-->products/list.php?<!--{$search_link}-->"><!--{$schedule_lbl}--></a></span></div>
		<!-- 最新アイテム数 -->
		　	<!--{assign var=dress_num value="`$arrProductCount.onepiece_count`"}-->
		<div class="pc_show" style="width: 70%; display: block; margin: 0 auto;">
			<ul class="itemsnumber__grp pc_show">
				<li class="itemsnumber__item">
				<a href="<!--{$smarty.const.URL_DIR}-->products/list.php?category_id=1&kind1=1" style="color:black;">
					<img src="/user_data/packages/sphone/img/ico_onepiece.png" alt="ワンピース" class="itemsnumber__icon">
					<div class="itemsnumber__detail">
						<span class="itemsnumber__title">ワンピース</span>
						<div class="itemsnumber__num">
							<span class="itemsnumber__count"><!--{$dress_num}--></span>
							<span class="itemsnumber__unit">着</span>
						</div>
					</div>
				</a>
				</li>
				<li class="itemsnumber__item">
				<a href="<!--{$smarty.const.URL_DIR}-->products/list.php?category_id=dress" style="color:black;">
					<img src="/user_data/packages/sphone/img/ico_dress.png" alt="ドレス" class="itemsnumber__icon">
					<div class="itemsnumber__detail">
						<span class="itemsnumber__title">ドレス</span>
						<div class="itemsnumber__num">
							<span class="itemsnumber__count"><!--{$arrProductCount.dress_count}--></span>
							<span class="itemsnumber__unit">着</span>
						</div>
					</div>
				</a>
				</li>
				<li class="itemsnumber__item">
					<img src="/user_data/packages/sphone/img/ico_review.png" alt="レビュー" class="itemsnumber__icon">
					<div class="itemsnumber__detail">
						<span class="itemsnumber__title">レビュー</span>
						<div class="itemsnumber__num">
							<span class="itemsnumber__count"><!--{$arrProductCount.women_review_count}--></span>
							<span class="itemsnumber__unit">件</span>
						</div>
					</div>
				</li>
			</ul>
		</div>
		<!-- 最新アイテム数 -->
	</div>
	<div class="homemaintext item_num_pc pc_show">
	    <div class="btnarea  pc_show">
	        <ul>
	            <li class="btnbox btnbox--2col"><a href="/products/list.php?category_id=dress&kind2=44" class="btn btn--fullmin btn--h50 br30"><figure><img src="/user_data/packages/sphone/img/ico_dress_44px.png" alt="icon" class="dress_top_btn"></figure><span class="btn__label">ドレス一覧へ</span></a></li>
	            <!--<li class="btnbox btnbox--2col"><a href="#" class="btn btn--fullmin btn--h50  js-viewovlcategory"><span class="btn__label">商品を探す</span></a></li>-->
	            <li class="btnbox btnbox--2col"><a href="#" class="btn btn--fullmin btn--h50  js-viewcategory br30"><figure><img src="/user_data/packages/sphone/img/ico_search_44px.png" alt="icon" class="dress_top_btn"></figure>
	            	<span class="btn__label">商品を探す</span></a></li>
	        </ul>
	    </div>
	</div>
	</div>
</div>