<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *

 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 */
*}-->

    <ul>
        <!--{assign var=key1 value="`$prefix`name01"}-->
        <!--{assign var=key2 value="`$prefix`name02"}-->
      <h3 class="inputmember_title">1. お名前</h3>
      <li>
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
        姓 <input id="firstname" type="text" name="<!--{$key1}-->"  value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" required />
        名 <input id="lastname" type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->"  class="boxHarf text data-role-none" required />
        </li>
      </ul>
      <ul>
        <!--{assign var=key1 value="`$prefix`kana01"}-->
        <!--{assign var=key2 value="`$prefix`kana02"}-->
        <h3 class="inputmember_title">2. お名前(フリガナ)</h3>
        <li>
          <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
          <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
          <!--{/if}-->
          姓 <input id="firstnamekana" type="text" name="<!--{$key1}-->"  value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" required />
          名 <input id="lastnamekana" type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" required />
        </li>
      </ul>
      <ul>
        <!--{assign var=key1 value="`$prefix`zip01"}-->
        <!--{assign var=key2 value="`$prefix`zip02"}-->
        <!--{assign var=key3 value="`$prefix`pref"}-->
        <!--{assign var=key4 value="`$prefix`addr01"}-->
        <!--{assign var=key5 value="`$prefix`addr02"}-->
      <h3 class="inputmember_title">3. 郵便番号</h3><a href="https://www.post.japanpost.jp/smt-zipcode/" class="post_num_s" target="_blank">> 郵便番号検索</a>
      <li>
        <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
        <!--{/if}-->
      〒 <input id="zip1" type="tel" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.ZIP01_LEN}-->" class="boxShort text data-role-none halfcharacter" required /> - <input id="zip2" type="tel" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.ZIP02_LEN}-->" class="boxShort text data-role-none halfcharacter" required />
        <div id="autoZip" class="mt20"><a href="javascript:fnCallAddress('<!--{$smarty.const.INPUT_ZIP_URLPATH}-->', '<!--{$key1}-->', '<!--{$key2}-->', '<!--{$key3}-->', '<!--{$key4}-->');">住所自動入力</a></div>
      </li>
      </ul>
      <p class="mt20 mb20">郵便番号を入力後、クリックしてください。</p>
      <ul>
      <h3 class="inputmember_title">4. 住所</h3>
      <li>
        <!--{if $arrErr[$key3] || $arrErr[$key4] || $arrErr[$key5]}-->
            <div class="attention"><!--{$arrErr[$key3]}--><!--{$arrErr[$key4]}--><!--{$arrErr[$key5]}--></div>
        <!--{/if}-->
        <select name="<!--{$key3}-->" style="<!--{$arrErr[$key3]|sfGetErrorColor}-->" class="top data-role-none" id="address1">
        <option value="" selected="selected">都道府県を選択</option>
            <!--{html_options options=$arrPref selected=$arrForm[$key3]}-->
        </select>
        <input id="address2" type="text" name="<!--{$key4}-->" value="<!--{$arrForm[$key4]|h}-->" class="boxHarf text data-role-none" style="width:95%"  required />
        <p>市区町村名（例：千代田区神田神保町）</p>
        <input id="address3" type="text" name="<!--{$key5}-->" value="<!--{$arrForm[$key5]|h}-->" class="boxLong text data-role-none" style="width:95%" required />
        <p>番地・ビル名（例：1-3-5）</p>
        <p>住所は2つに分けてご記入いただけます。<br />マンション名は必ず記入してください。</p>
      </li>
      </ul>
      <ul>  
      <h3 class="inputmember_title">5. 電話番号</h3>
      <li>
        <!--{assign var=key1 value="`$prefix`tel01"}-->
        <!--{assign var=key2 value="`$prefix`tel02"}-->
        <!--{assign var=key3 value="`$prefix`tel03"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
        <!--{/if}-->
        <input type="tel" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none halfcharacter" maxlength="6" required />－<input type="tel" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none halfcharacter" maxlength="6" required />－<input type="tel" name="<!--{$key3}-->" value="<!--{$arrForm[$key3]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none halfcharacter"  maxlength="6" required />
      
      </li>
      </ul>

<!--{if $flgFields > 1}-->
      <ul>
      <h3 class="inputmember_title">6. メールアドレス</h3>
      <li>
            <!--{assign var=key1 value="`$prefix`email"}-->
            <!--{assign var=key2 value="`$prefix`email02"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
            <!--{/if}-->
            <input type="email" id="email" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" class="boxLong text top data-role-none" style="width:95%" required />
            <input type="email" id="email-confirm" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|default:$arrForm[$key1]|h}-->" class="boxLong text data-role-none"  style="width:95%" required />   
        <p>確認のため2度入力してください。<br/>
        ご登録いただいたアドレスが「＠i.softbank.jp」の場合、受信が出来ない事例が多々発生しております。 他のメールアドレスをお持ちの場合、違うアドレスでのご登録をおすすめしております。</p>
      </li>
      </ul>

  <ul class="sp-birthday">
  <!--{assign var=errBirth value="`$arrErr.year``$arrErr.month``$arrErr.day`"}-->
  <h3 class="inputmember_title">7. 生年月日</h3>
    <li>
          <!--{assign var=errBirth value="`$arrErr.year``$arrErr.month``$arrErr.day`"}-->
          <!--{if $errBirth}-->
              <div class="attention"><!--{$errBirth}--></div>
          <!--{/if}-->
        <select name="year" style="<!--{$errBirth|sfGetErrorColor}-->"  class="top data-role-none" >
              <!--{html_options options=$arrYear selected=$arrForm.year|default:'1990'}-->
        </select>
    </li>
    <li id="date-text"><span class="selectdate">年</span></li>
    <li>
        <select name="month" style="<!--{$errBirth|sfGetErrorColor}-->"  class=" top data-role-none" >
              <!--{html_options options=$arrMonth selected=$arrForm.month|default:''}-->
            </select>
    </li>
    <li id="date-text"><span class="selectdate">月</span></li>
    <li>
        <select name="day" style="<!--{$errBirth|sfGetErrorColor}-->"  class=" top data-role-none" >
              <!--{html_options options=$arrDay selected=$arrForm.day|default:''}-->
        </select>
    </li>
    <li id="date-text"><span class="selectdate">日</span></li>
  </ul>

    <!--{if $flgFields > 2}-->
  
      <ul>
      <h3 class="inputmember_title">8. 希望するパスワード</h3>
      <li>
        <!--{if $arrErr.password || $arrErr.password02}-->
            <div class="attention"><!--{$arrErr.password}--><!--{$arrErr.password02}--></div>
        <!--{/if}-->
        <input type="password" name="password" value="<!--{$arrForm.password|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="width:95%; <!--{$arrErr.password|sfGetErrorColor}-->" class="boxLong text data-role-none" required autocomplete="off" />
        <p>半角英数字8～10文字でお願いします。（記号不可）</p>
        <input type="password" name="password02" value="<!--{$arrForm.password02|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="width:95%; <!--{$arrErr.password|cat:$arrErr.password02|sfGetErrorColor}-->"  class="boxLong text data-role-none" required autocomplete="off" />
        <p>確認のため2度入力してください。</p>
      </li>
      </ul>

    <ul>
      <h3 class="inputmember_title">9. ご利用規約</h3>
      <li>
    <!--{assign var=key1 value="`$prefix`kiyaku"}-->
    <span style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
      <!--{if $arrErr[$key1]}-->
        <div class="attention"><!--{$arrErr[$key1]}--></div>
      <!--{/if}-->
    </span>

        <div class="information adjustp">
            <p>会員登録をされる前に、下記利用規約をお読みください。<br>
            「同意する」にチェックを入れると、本規約に同意いただた上でサービスをご利用いただくものとなります。</p>
        </div>
        <div id="kiyaku_text" class="inlineframe">
            <p><!--{$tpl_kiyaku_text|nl2br}--></p>
        </div>
          <p class="ta_c fs_16">同意する <input id="kiyaku_ok" type="checkbox" name="<!--{$key1}-->"  value="2" required /></p>
        </li>
      </ul>

      <!-- <!--{html_options options=$arrReminder selected=$arrForm.reminder}--> -->
    <!--{/if}-->
<!--{/if}-->
