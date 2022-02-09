<script type="text/javascript" src="<!--{$TPL_DIR}-->js/201303/detailtab.js"></script>
<script src="<!--{$TPL_URLPATH}-->js/jquery.facebox/facebox.js"></script>
<script defer src="<!--{$smarty.const.ROOT_URLPATH}-->js/detail.js"></script>

<link rel="stylesheet" type="text/css" href="<!--{$TPL_DIR}-->css/onayami.css">
<div id="wrapper">
  <header>
  	<nav>
      <ul class="clearfix">
        <li><a href="<!--{$smarty.const.USER_URL}-->selection_assistance.php"><div class="btn_selectlink_on"><label>体型・レビュー検索</label></div></a></li>
        <li><a href="<!--{$smarty.const.USER_URL}-->wedding_feature.php"><div class="btn_selectlink_off"><label>結婚式特集</label></div></a></li>
        <li><a href="<!--{$smarty.const.USER_URL}-->usually_feature.php"><div class="btn_selectlink_off"><label>普段使い特集</label></div></a></li>
      </ul>
    </nav>
    <div></div><!-- 商品コード検索 -->
  </header>
  <section>
    <h2>レビューから商品を検索する</h2>
    <div class="sectionInner guide2">
      <div class="guideNav">
        <a class="right" href="<!--{$smarty.const.ROOT_URLPATH}-->products/review_search.php">
          <div class="btn_link">
            レビューから商品を検索する
          </div>
        </a>
        <div class="left">
          これまで皆様にご回答いただきました、<label style="color:#FF0000;font-size:120%;font-weight:bold;"><!--{$arrProductCount.women_review_count}-->件</label>のレビューから、商品を検索致します。
          ご自身の用途と年代や体型から、似たようなご利用者様のレビューを参考にして、商品選びにご活用ください。
        </div>
      </div>
    </div>
    <h2>着用スタッフの体型で商品を検索する</h2>
    <!--{section name=cnt loop=$arrModel}-->
    <!--{if $arrModel[$smarty.section.cnt.index].type == 1}-->
    <h3 class="model_h3">モデルの「<!--{$arrModel[$smarty.section.cnt.index].name}-->」</h3>
    <!--{else}-->
    <h3 class="staff_h3">スタッフの「<!--{$arrModel[$smarty.section.cnt.index].name}-->」</h3>
    <!--{/if}-->
    <div class="sectionInner guide2">
      <div class="guideNav">
        <a style="padding:5px;color:#FFFFFF;" rel="external" href="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php?staff1_id=<!--{$arrModel[$smarty.section.cnt.index].model_id}-->">
          <!--{if $arrModel[$smarty.section.cnt.index].type == 1}--></h3>
          <div class="btn_link">モデルの「<!--{$arrModel[$smarty.section.cnt.index].name}-->」が着用した商品一覧</div>
          <!--{else}-->
          <div class="btn_link">スタッフの「<!--{$arrModel[$smarty.section.cnt.index].name}-->」の着用コメントがある商品一覧</div>
          <!--{/if}-->
        </a>
        <div class="left">
          <ul style="margin-top:-3%;">
            <li>サイズ：<!--{$arrModel[cnt].size}--></li>
            <li>身長：<!--{$arrModel[cnt].height}-->cm</li>
            <li>バストカップ：<!--{$arrModel[cnt].under_cup}--></li>
            <li>バスト：<!--{$arrModel[cnt].bust}-->cm</li>
            <li>ウエスト：<!--{$arrModel[cnt].waist}-->cm</li>
            <li>ヒップ：<!--{$arrModel[cnt].hip}-->cm</li>
            <li>アンダー：<!--{$arrModel[cnt].under}-->cm</li>
          </ul>
        </div>
      </div>
    </div>
    <!--{/section}-->

    <h2>ブランドから商品を検索する</h2>
    <div class="sectionInner guide2">
        <ul class="brand_list">
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=23区" ><li>23区</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Apuweiser-riche" ><li>Apuweiser-riche</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=AnteNatuA" ><li>AnteNatuA</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ASHILL" ><li>ASHILL</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=BANANA REPUBLIC" ><li>BANANA REPUBLIC</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=BCBG MAXAZRIA" ><li>BCBG MAXAZRIA</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=BEGUM" ><li>BEGUM</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=BETSEY JOHNSON" ><li>BETSEY JOHNSON</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=BK.EMOTION" ><li>BK.EMOTION</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=BLIGITTE" ><li>BLIGITTE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Brilliantstage" ><li>Brilliantstage</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=BURBERRY" ><li>BURBERRY</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=C DE C" ><li>C DE C</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=C’EST LAVIE" ><li>C’EST LAVIE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=CECIL McBEE" ><li>CECIL McBEE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=CLE des ZONES" ><li>CLE des ZONES</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=CLEAR IMPRESSION" ><li>CLEAR IMPRESSION</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=CROLLA" ><li>CROLLA</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=cute's wit" ><li>cute's wit</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Cynthia Rowley" ><li>Cynthia Rowley</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Dear Princess" ><li>Dear Princess</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Designed in paris" ><li>Designed in paris</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=DORRY" ><li>DORRY</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=dressdeco" ><li>dressdeco</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ef-de" ><li>ef-de</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ELLE" ><li>ELLE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ELLE PLANETE" ><li>ELLE PLANETE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ESTNATION" ><li>ESTNATION</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Fabulous" ><li>Fabulous</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Feroux" ><li>Feroux</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=FLANDRE" ><li>FLANDRE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Genet Vivien" ><li>Genet Vivien</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Grace Class" ><li>Grace Class</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Grand Dress" ><li>Grand Dress</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=GRAND TABLE" ><li>GRAND TABLE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=hirota" ><li>hirota</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=IMAGE" ><li>IMAGE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=INDIVI" ><li>INDIVI</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=INED" ><li>INED</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=INGNI" ><li>INGNI</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=JAYRO" ><li>JAYRO</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=JILL STUART" ><li>JILL STUART</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=JOINTURE" ><li>JOINTURE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=JUSGLITTY" ><li>JUSGLITTY</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Jusqu'a" ><li>Jusqu'a</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=KarL Park Lane" ><li>KarL Park Lane</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ketty" ><li>ketty</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=KLEIN D OEIL" ><li>KLEIN D OEIL</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=La scene privee" ><li>La scene privee</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=LABORATORY WORK" ><li>LABORATORY WORK</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=LAISSE PASSE" ><li>LAISSE PASSE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=LE A NOURE" ><li>LE A NOURE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=LE SOUK" ><li>LE SOUK</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=LENTILLE" ><li>LENTILLE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=L'EST ROSE" ><li>L'EST ROSE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=LIVIN' ALONE" ><li>LIVIN' ALONE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Lui Chantant" ><li>Lui Chantant</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Luxjewel" ><li>Luxjewel</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=MAITRESSE" ><li>MAITRESSE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=MARIE CLAIRE" ><li>MARIE CLAIRE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=MICHEL KLEIN" ><li>MICHEL KLEIN</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=MORGAN" ><li>MORGAN</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=NATURAL BEAUTY" ><li>NATURAL BEAUTY</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=nicole" ><li>nicole</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=NOLLEY'S" ><li>NOLLEY'S</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=NORD SUD" ><li>NORD SUD</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ONWARD KASHIYAMA" ><li>ONWARD KASHIYAMA</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Par Avion" ><li>Par Avion</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Petit Poudre" ><li>Petit Poudre</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=PEYTON PLACE" ><li>PEYTON PLACE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Pinky&Dianne" ><li>Pinky&Dianne</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=pour la frime" ><li>pour la frime</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=PREFERIR" ><li>PREFERIR</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Premium By LAST SCENE" ><li>Premium By LAST SCENE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=prideglide" ><li>prideglide</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Queen Claret" ><li>Queen Claret</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=QUEENS COURT" ><li>QUEENS COURT</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=R・F" ><li>R・F</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=RALPH LAUREN" ><li>RALPH LAUREN</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=REBECCA TAYLOR" ><li>REBECCA TAYLOR</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Reflect" ><li>Reflect</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=REPLETE" ><li>REPLETE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Riccimie NEW YORK" ><li>Riccimie NEW YORK</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ROPE" ><li>ROPE</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ru" ><li>ru</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=ru LUXURY" ><li>ru LUXURY</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Rue de B" ><li>Rue de B</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=SCOT CLUB" ><li>SCOT CLUB</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=She's" ><li>She's</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=SOUP" ><li>SOUP</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=STRAWBERRY-FIELDS" ><li>STRAWBERRY-FIELDS</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Suna Una" ><li>Suna Una</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=SUNVITALIA" ><li>SUNVITALIA</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=TOCCA" ><li>TOCCA</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=traumerei" ><li>traumerei</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=UNITED ARROWS" ><li>UNITED ARROWS</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=UNTITLED" ><li>UNTITLED</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=vanilla confusion" ><li>vanilla confusion</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=Xmiss" ><li>Xmiss</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=組曲" ><li>組曲</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=組曲anyFAM" ><li>組曲anyFAM</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=組曲anySiS" ><li>組曲anySiS</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=組曲PRIER" ><li>組曲PRIER</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=組曲SiS" ><li>組曲SiS</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=東京SOIR" ><li>東京SOIR</li></a>
          <a href="<!--{$smarty.const.HTTPS_URL}-->products/list.php?kind_all=all&name=東京スタイル" ><li>東京スタイル</li></a>
        </ul>
    </div>
  </section>
</div>
