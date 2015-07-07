<?php
    #Чтение RSS с помощью SimpleXML
    const FILE_NAME = "xml/news.xml";//Имя файла для сохранения на сервере.
    const RSS_URL = "http://news/rss/rss.xml";//Откуда читаем
    const RSS_TTL = 3600;//Время хранения файла (сек)

    function downloadRSS($url,$filename){
        $file = file_get_contents($url);//Читаем содержимое файла в строку
        if($file){
            file_put_contents($filename,$file);//сохраняем в файл полученные данные
        }
    }

    if(!is_file(FILE_NAME)){
        downloadRSS(RSS_URL,FILE_NAME);
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Новостная лента</title>
	<meta charset="utf-8" />
</head>
<body>

<h1>Последние новости</h1>
<?php
    $xml = simplexml_load_file(FILE_NAME);//Зачитываем наш файл
    foreach($xml->channel->item as $item){
        echo <<<RSS
            <h3>{$item->title}</h3>
            <p>
                {$item->description}<br/>
                <b>Категория: {$item->category}</b><br/>
                <i>Опубликовано: {$item->publicDate}</i>
            </p>
            <p align = "right">
                <a href="{$item->link}">Подробнее..</a>
            </p>
RSS;
    }
    if(time()>filemtime(FILE_NAME)+RSS_TTL){//Если время хранения истекло - обновляем
        downloadRSS(RSS_URL,FILE_NAME);
    }
?>
</body>
</html>