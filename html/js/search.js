$(function(){
    $(".halfcharacter").keypress(function(e){
        if( e.which == 13){
            charChange($(this));
        }
    });

    charChange = function(e){
        var val = e.val();
        var str = val.replace(/[Ａ-Ｚａ-ｚ０-９]/g,function(s){return String.fromCharCode(s.charCodeAt(0)-0xFEE0)}).replace(/[‐－―ー?]/g, '-');

        if(val.match(/[Ａ-Ｚａ-ｚ０-９‐－―ー?]/g)){
            $(e).val(str);
        }
    }
});
