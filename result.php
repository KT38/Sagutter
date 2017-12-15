
<!DOCTYPE html>
<html lang = 'ja'>
<html>
    <head>
        <title>Sagutter</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="form1">
                <a href='index.html'><img src='img/LogoB.png' height = '50px' align='left'></a>
                <form method = 'post' action = 'result.php'>
                    <input type = 'text' name = 'keyword' value = "<?php echo $_POST['keyword']; ?>" class='txt'>
                    <input type = 'submit' value = '検索' class='send'>
                </form>
            </div>
        </header>

        <article>
            <?php

                require_once("./config.php");
                ini_set('display_errors', 0);

                //フォームの受け取り
                $key = $_POST['keyword'];

                //特殊文字のエスケープ
                $search = array('<','>');
                $replace = array('&lt;','&gt;');
                str_replace($search,$replace,$key);

                //ライブラリの読み込み
                require "vendor/autoload.php";
                use Abraham\TwitterOAuth\TwitterOAuth;

                //認証
                $consumerKey       = $key['consumerKey'];
                $consumerSecret    = $key['consumerSecret'];
                $accessToken       = $key['accessToken'];
                $accessTokenSecret = $key['accessTokenSecret'];
                $connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
                //検索と表示
                $tweets = $connection->get('search/tweets', array('q' => $key . "filter:images -RT", 'count' =>'100', 'result_type' => 'recent' ,'include_entities'=>true));
                $icon = "<i class='fa fa-download' aria-hidden='true'></i>";
                if($tweets->statuses)
                {
                    foreach($tweets->statuses as $result)
                    {
                        $url = $result->extended_entities->media[0]->expanded_url;
                        $image = $result->entities->media[0]->media_url;
                        if($url)
                        {
                            echo
                                "<figure>
                                    <a href=$url target='_blank'><img src = $image height='150' alt = '' ></a>
                                    <figcaption>
                                        <a href='$image' download = $image title = '保存'>$icon</a>
                                    </figcaption>
                                </figure>"
                            ;
                        }
                    }
                }
                else
                {
                    echo '「'.$key.'」と一致する結果が見つかりませんでした。他のキーワードをお試しください。';
                }
            ?>
        </article>
    </body>
</html>
