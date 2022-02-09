<!--{*
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
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
        姓 <input id="firstname" type="text" name="<!--{$key1}-->"  value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" required autofocus />
        名 <input id="lastname" type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->"  class="boxHarf text data-role-none" required autofocus />
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
          姓 <input id="firstnamekana" type="text" name="<!--{$key1}-->"  value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" required autofocus />
          名 <input id="lastnamekana" type="text" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.STEXT_LEN}-->" class="boxHarf text data-role-none" required autofocus />
        </li>
      </ul>





      <ul>
        <!--{assign var=key1 value="`$prefix`zip01"}-->
        <!--{assign var=key2 value="`$prefix`zip02"}-->
        <!--{assign var=key3 value="`$prefix`pref"}-->
        <!--{assign var=key4 value="`$prefix`addr01"}-->
        <!--{assign var=key5 value="`$prefix`addr02"}-->
      <li>
      <input id="zip1" type="hidden" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.ZIP01_LEN}-->" class="boxShort text data-role-none halfcharacter" /><input id="zip2" type="hidden" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.ZIP02_LEN}-->" class="boxShort text data-role-none halfcharacter" />
      </li>
      </ul>
      <ul>
      <li>
        <input id="address1" type="hidden" name="<!--{$key3}-->" value="<!--{$arrForm[$key3]|h}-->" class="boxHarf text data-role-none" style="width:95%"  required autofocus />
        <input id="address2" type="hidden" name="<!--{$key4}-->" value="<!--{$arrForm[$key4]|h}-->" class="boxHarf text data-role-none" style="width:95%"  required autofocus />
        <input id="address3" type="hidden" name="<!--{$key5}-->" value="<!--{$arrForm[$key5]|h}-->" class="boxLong text data-role-none" style="width:95%" required autofocus />
      </li>
      </ul>





      <ul>  
      <h3 class="inputmember_title">3. 電話番号</h3>
      <li>
        <!--{assign var=key1 value="`$prefix`tel01"}-->
        <!--{assign var=key2 value="`$prefix`tel02"}-->
        <!--{assign var=key3 value="`$prefix`tel03"}-->
        <!--{if $arrErr[$key1] || $arrErr[$key2] || $arrErr[$key3]}-->
            <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--><!--{$arrErr[$key3]}--></div>
        <!--{/if}-->
        <input type="tel" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none halfcharacter" maxlength="6" autofocus />－<input type="tel" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none halfcharacter"   maxlength="6" autofocus />－<input type="tel" name="<!--{$key3}-->" value="<!--{$arrForm[$key3]|h}-->" maxlength="<!--{$smarty.const.TEL_ITEM_LEN}-->" class="boxShort text data-role-none halfcharacter"  maxlength="6" autofocus />
      
      </li>
      </ul>

<!--{if $flgFields > 1}-->
      <ul>
      <h3 class="inputmember_title">4. メールアドレス</h3>
      <li>
            <!--{assign var=key1 value="`$prefix`email"}-->
            <!--{assign var=key2 value="`$prefix`email02"}-->
            <!--{if $arrErr[$key1] || $arrErr[$key2]}-->
                <div class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></div>
            <!--{/if}-->
            <input type="text" id="email" name="<!--{$key1}-->" value="<!--{$arrForm[$key1]|h}-->" class="boxLong text top data-role-none" style="width:95%" required autofocus />
            <input type="email" id="email" name="<!--{$key2}-->" value="<!--{$arrForm[$key2]|default:$arrForm[$key1]|h}-->" class="boxLong text data-role-none"  style="width:95%" required autofocus />   
        <p>確認のため2度入力してください。<br/>
        ご登録いただいたアドレスが「＠i.softbank.jp」の場合、受信が出来ない事例が多々発生しております。 他のメールアドレスをお持ちの場合、違うアドレスでのご登録をおすすめしております。</p>
      </li>
      </ul>

    <!--{*性別は全部女性として登録されます*}-->
    <!--{assign var=key1 value="`$prefix`sex"}-->
    <span style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
      <!--{if $arrErr[$key1]}-->
        <div class="attention"><!--{$arrErr[$key1]}--></div>
      <!--{/if}-->
      <input type="hidden"  id="female" name="<!--{$key1}-->" value="2" checked="checked">
    </span>

  <ul class="sp-birthday">
  <!--{assign var=errBirth value="`$arrErr.year``$arrErr.month``$arrErr.day`"}-->
  <h3 class="inputmember_title">5. 生年月日</h3>
  <li>
        <!--{assign var=errBirth value="`$arrErr.year``$arrErr.month``$arrErr.day`"}-->
        <!--{if $errBirth}-->
            <div class="attention"><!--{$errBirth}--></div>
        <!--{/if}-->
      <select name="year" style="<!--{$errBirth|sfGetErrorColor}-->"  class="top data-role-none" >
            <!--{html_options options=$arrYear selected=$arrForm.year|default:''}-->
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
      <h3 class="inputmember_title">6. 希望するパスワード</h3>
      <li>
                <!--{if $arrErr.password || $arrErr.password02}-->
                    <div class="attention"><!--{$arrErr.password}--><!--{$arrErr.password02}--></div>
                <!--{/if}-->
        <input type="password" name="password" value="<!--{$arrForm.password|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="width:95%; <!--{$arrErr.password|sfGetErrorColor}-->" class="boxLong text data-role-none" required autofocus  />
        <p>半角英数字4～10文字でお願いします。（記号不可）</p>
        <input type="password" name="password02" value="<!--{$arrForm.password02|h}-->" maxlength="<!--{$smarty.const.PASSWORD_MAX_LEN}-->" style="width:95%; <!--{$arrErr.password|cat:$arrErr.password02|sfGetErrorColor}-->"  class="boxLong text data-role-none" required autofocus  />
        <p>確認のため2度入力してください。</p>
      </li>
      </ul>
      <!-- <!--{html_options options=$arrReminder selected=$arrForm.reminder}--> -->
    <!--{/if}-->
<!--{/if}-->
