$(document).ready(function(){
　　path = location.pathname
　　	if(path.match("/index.php")){
	$("#pw_nav li.home a img").attr("src", "https://onepiece-rental.net/test/html/user_data/packages/wanpi/img/top/navi01_on.gif");
　　	}
	if(path.match("/products/")){
	$("#pw_nav li.items a img").attr("src", "https://onepiece-rental.net/test/html/user_data/packages/wanpi/img/top/navi02_on.gif");
　　	}
	if(path.match("/user_data/concept.php")){
	$("#pw_nav li.info a img").attr("src", "https://onepiece-rental.net/test/html/user_data/packages/wanpi/img/top/navi03_on.gif");
　　	}
	if(path.match("/user_data/rental.php")){
	$("#pw_nav li.guide a img").attr("src", "https://onepiece-rental.net/test/html/user_data/packages/wanpi/img/top/navi04_on.gif");
　　	}
	if(path.match("/user_data/faq.php")){
	$("#pw_nav li.faq a img").attr("src", "https://onepiece-rental.net/test/html/user_data/packages/wanpi/img/top/navi05_on.gif");
　　	}
	if(path.match("/user_data/guide9.php")){
	$("#pw_nav li.contact a img").attr("src", "https://onepiece-rental.net/test/html/user_data/packages/wanpi/img/top/navi06_on.gif");
　　	}
})