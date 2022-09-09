<?php
    error_reporting(0);

    if(array_key_exists('HTTP_X_REAL_IP', $_SERVER))$Ip=$_SERVER['HTTP_X_REAL_IP'];
    else $Ip='Unknown';
    $Ip = str_replace(".","_",$Ip);

    $Curl = curl_init();
    curl_setopt_array($Curl, array(
        CURLOPT_URL => 'https://api.vizzi.tk/1.1/classes/game_name',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => "where=%7B%22Ip%22%3A%22$Ip%22%7D&count=1",
        CURLOPT_HTTPHEADER => array(
            'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
            'X-LC-Key: Prvhqg4AhwNoaSa6vmdNam0H,master',
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));
    $Result = json_decode(curl_exec($Curl));
    if($Result -> count == 0){
        curl_setopt_array($Curl, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"{\"Ip\":\"$Ip\",\"Name\":\"匿名\"}",
            CURLOPT_HTTPHEADER => array(
                'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
                'X-LC-Key: Prvhqg4AhwNoaSa6vmdNam0H,master',
                'Content-Type: application/json'
            ),
        ));
        curl_exec($Curl);
        $UserName = "匿名";
    }
    else{
        $UserName = $Result->results[0]->Name;
    }

    if(array_key_exists('HTTP_GAMESCORE', $_SERVER)){
        $Score=$_SERVER['HTTP_GAMESCORE'];
        curl_setopt_array($Curl, array(
            CURLOPT_URL => "https://api.vizzi.tk/1.1/leaderboard/entities/$Ip/statistics",
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"[{\"statisticName\": \"game_rank\", \"statisticValue\": $Score}]",
            CURLOPT_HTTPHEADER => array(
                'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
                'X-LC-Key: Prvhqg4AhwNoaSa6vmdNam0H,master',
                'Content-Type: application/json'
            ),
        ));
        curl_exec($Curl);
        curl_close($Curl);
        echo "Upload score $Score";
        exit();
    }

    if(array_key_exists("Update-Name", $_REQUEST)){
        $Curl_Name = $_REQUEST["Update-Name"];
        if(strlen($Curl_Name)>15){
            $Curl_Name = mb_substr($Curl_Name, 0, 15).'...';
        }
        $UserName = $Curl_Name;
        curl_setopt_array($Curl, array(
            CURLOPT_URL => 'https://api.vizzi.tk/1.1/classes/game_name',
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => "where=%7B%22Ip%22%3A%22$Ip%22%7D&count=1",
            CURLOPT_HTTPHEADER => array(
                'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
                'X-LC-Key: Prvhqg4AhwNoaSa6vmdNam0H,master',
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $ObjectId = json_decode(curl_exec($Curl))->results[0]->objectId;
        curl_setopt_array($Curl, array(
            CURLOPT_URL => "https://api.vizzi.tk/1.1/classes/game_name/$ObjectId",
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS =>"{\"Ip\":\"$Ip\",\"Name\":\"$Curl_Name\"}",
            CURLOPT_HTTPHEADER => array(
                'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
                'X-LC-Key: Prvhqg4AhwNoaSa6vmdNam0H,master',
                'Content-Type: application/json'
            ),
        ));
        curl_exec($Curl);
    }
?>
<!doctype html>
<html lang="zh">
<head>
    <script>window.history.replaceState(null, null, window.location.href);</script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>排行榜</title>
    <style>
    body{
        background: #f9f7f6;
    }
    table#rank { 
        margin: 0 auto;
        border-collapse: collapse;
        font-weight: 100; 
        background: #333; color: #fff;
        text-rendering: optimizeLegibility;
        border-radius: 5px; 
        text-align: center;
        overflow: hidden;
    }
    table#rank caption { 
        font-size: 2rem; color: #444;
        margin: 1rem;
        background-size: contain;
        background-repeat: no-repeat;
    }
    table#rank thead th { 
        font-weight: 600;
        padding: .8rem;
        font-size: 1.4rem;
    }
    table#rank tbody td { 
        padding: .8rem;
        font-size: 1.4rem;
        color: #444;
        background: #eee;
    }
    table#rank tbody tr { 
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;  
    }
    #about{
        margin: 0 auto;
        border-collapse: collapse;
        font-weight: 100; 
        background: #333; color: #fff;
        text-rendering: optimizeLegibility;
        border-radius: 5px; 
        text-align: center;
        overflow: hidden;
        font-size: 2rem;
        margin: 1rem;
        background-size: contain;
        background-repeat: no-repeat;
    }
    input{
        margin: 0 auto;
        font-weight: 100; 
        border-radius: 5px;
        text-align: center;
        overflow: hidden;
        padding: .8rem;
        font-size: 1.4rem;
        color: #444;
        background: #eee;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        border-left: 1px solid #ddd;
        border-right: 1px solid #ddd;
    }
    </style>
</head>
<body>
    <div id="about">
        <?php
            curl_setopt_array($Curl, array(
                CURLOPT_URL => "https://api.vizzi.tk/1.1/leaderboard/entities/$Ip/statistics",
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => 'statistics=game_rank',
                CURLOPT_HTTPHEADER => array(
                    'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
                    'X-LC-Key: AKh4xzIssOtxKzKIpprgGrNX',
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));
            $UserScore = json_decode(curl_exec($Curl))->results[0]->statisticValue;
            if($UserScore == "")$UserScore = "未参加";
            $RequestUrl = $_SERVER["REQUEST_URI"];
            echo "<form action=\"$RequestUrl\" method=\"post\">\n";
            echo "我的昵称: <input type=\"text\" name=\"Update-Name\" value=\"$UserName\">\n";
            echo "<input type=\"submit\" value=\"更改\">\n";
            echo "</form>\n";
            echo "我的成绩: $UserScore\n";
        ?>
    </div>
    <div>
        <table id="rank">
            <caption>排行榜</caption>
            <thead>
            <tr><th>名次<th>昵称<th>分数
            <tbody>
            <?php
                curl_setopt_array($Curl, array(
                    CURLOPT_URL => 'https://api.vizzi.tk/1.1/leaderboard/leaderboards/user/game_rank/ranks',
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_POSTFIELDS => 'maxResultsCount=1000',
                    CURLOPT_HTTPHEADER => array(
                        'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
                        'X-LC-Key: AKh4xzIssOtxKzKIpprgGrNX',
                        'Content-Type: application/x-www-form-urlencoded'
                    ),
                ));
                $Data = json_decode(curl_exec($Curl)) -> results;

                curl_setopt_array($Curl, array(
                    CURLOPT_URL => 'https://api.vizzi.tk/1.1/classes/game_name',
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'X-LC-Id: Pg0mOWcu8x8WVp9qRp83EeKK-MdYXbMMI',
                        'X-LC-Key: Prvhqg4AhwNoaSa6vmdNam0H,master',
                        'Content-Type: application/x-www-form-urlencoded'
                    ),
                ));
                foreach($Data as $Record){
                    $t1 = $Record -> rank +1;
                    $t2 = $Record -> entity;
                    $t3 = $Record -> statisticValue;
                    curl_setopt($Curl,CURLOPT_POSTFIELDS, "where=%7B%22Ip%22%3A%22$t2%22%7D&count=1");
                    $t2 = json_decode(curl_exec($Curl))->results[0]->Name;
                    echo "<tr>\n<td>$t1\n<td>$t2\n<td>$t3\n";
                }
                curl_close($Curl);
            ?>
        </table>
    </div>
</body>
</html>
