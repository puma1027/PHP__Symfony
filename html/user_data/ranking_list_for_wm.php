<?php

    try {
        // データベースに接続
        $objQuery = new PDO(
            'pgsql:dbname=wanpi_db_201603;host=dbonepi.cwihh2qropry.ap-northeast-1.rds.amazonaws.com;port=5664;',
            'f01-rumw',
            'dnt4tkwd',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }catch (PDOException $e) {
        // エラーが発生した場合は「500 Internal Server Error」でテキストとして表示して終了する
        // - もし手抜きしたくない場合は普通にHTMLの表示を継続する
        // - ここではエラー内容を表示しているが， 実際の商用環境ではログファイルに記録して， Webブラウザには出さないほうが望ましい
        header('Content-Type: text/plain; charset=UTF-8', true, 500);
        exit($e->getMessage()); 

    }
        //---- 上位3位データ取得
        $result20 = $objQuery->query("SELECT * FROM vw_product_ranking_count where aetas=20 limit 3");
        $result30 = $objQuery->query("SELECT * FROM vw_product_ranking_count where aetas=30 limit 3");
        $result40 = $objQuery->query("SELECT * FROM vw_product_ranking_count where aetas=40 limit 3");
        $result50 = $objQuery->query("SELECT * FROM vw_product_ranking_count where aetas=50 limit 3");
        //$sql = "SELECT to_json(vw_product_ranking_count) from vw_product_ranking_count where aetas=? limit 15";

        $arrRet20 = $result20->fetchAll();
        $arrRet30 = $result30->fetchAll();
        $arrRet40 = $result40->fetchAll();
        $arrRet50 = $result50->fetchAll();

        //全データの配列をマージ
        $allArrRet = array_merge($arrRet20, $arrRet30, $arrRet40, $arrRet50);
        //jsonコードに変換
        $arrRettest = json_encode($allArrRet, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        //出力先ファイルを指定
        $filepath = "/var/www/html/onepiece-rental.net/html/rss/json/test.json";
        //jsonデータをファイルに書き出す
        file_put_contents($filepath , $arrRettest);
?>