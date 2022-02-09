(function($) {
	$.fn.mailcomplete = function(options) {

		var elements = this;
		var defaults = {
			source: domainData,
			minDomain: 1
		};
		var setting = $.extend(defaults, options);

		var domains = []; // @より前を含めたドメインリストを格納
		var currentVal; // 入力している内容を取得
		var atindex = 0; // @より前の文字数取得
		var mailname; // @より前の文字列取得
		var autocompleteFlag = false; // autocompleteが構築されているかの管理

		elements.keyup(function() {
			currentVal = elements.val();
			// @が含まれている場合
			if(currentVal.match(/@/)) {
				// @を追加してすぐの場合
				if(!autocompleteFlag) {
					autocompleteFlag = true;
					// autocompleteの構築
					autocompleteSet(elements);

				// @が追加されて2回目以降の場合
				} else {
					// @より前半が変更された場合
					if(currentVal.indexOf('@') != atindex) {
						// autocompleteを一旦破棄して再構築
						elements.autocomplete('destroy');
						autocompleteSet(elements);
					}
				}

			// @が含まれていない場合
			} else {
				// @が削除されてすぐのとき
				if(autocompleteFlag) {
					autocompleteFlag = false;
					// autocompleteの破棄
					elements.autocomplete('destroy');
				}
			}
		});

		function autocompleteSet(target) {
			// @より前の文字数と文字列取得
			atindex = currentVal.indexOf('@');
			mailname = currentVal.slice(0, atindex);
			// @より前を含めたドメインリストの作成
			for (var i = 0; i < setting.source.length; i++) {
				domains[i] = mailname + '@' + setting.source[i];
			};

			// autocompleteの構築
			target.autocomplete({
				source: domains,
				minLength: atindex + 1 + setting.minDomain
			});
		}
	}
})(jQuery);
