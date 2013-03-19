<div class="contents">

<?php  
	if ( isset ($_GET)) { 
		foreach ($_GET as $key=>$val) { 
			$$key=$val; 
		} 
	}

//преобразуем результат в строку JSON, заодно заменяя четырёхбайтные символы кириллицы читаемыми
$JsonString = preg_replace_callback(
    '/\\\u([0-9a-fA-F]{4})/',
    create_function('$match', 'return mb_convert_encoding("&#" . intval($match[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),
    json_encode($_POST)
);

/*
echo "адрес эл. почты:" .$_POST["email"]."</br>";
echo "телефон:" .$_POST["phone"]."</br>";
echo "скайп:" .$_POST["skype"]."</br>";
echo "сайт:" .$_POST["homepage"]."</br>";
echo "координаты gps: " .$_POST["gps"]."</br>";
*/

$JsonSecondString = file_get_contents("res/zones.json");
$path_array = json_decode($JsonSecondString);

$root_path= 'Crimea/';
if (!is_dir($root_path)){
		mkdir("$root_path");
	}
	foreach ($path_array->Contents as $Contents) {
		if ($Contents->zoneID == $_POST["zoneID"]) {
			$path = $root_path.$Contents->zoneNameEN."/";
			echo "path: ".$path."</br>";
			if (!is_dir($path )){
				mkdir("$path");
			}
			foreach ($Contents->zoneTown as $zoneTown) {
				if ($zoneTown->townID == $_POST["TownSelect"]) {
					$path = $path.$zoneTown->townNameEN."/";
					echo "spath: ".$path."</br>";
					if (!is_dir($path )){
						mkdir("$path");
					}
				}
			}
		}
	}

	$path = 'image/';
	if (!is_dir($path )){
		mkdir('image/');
	}
	$types = array('image/gif', 'image/png', 'image/jpeg');


 	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
 		// Проверяем тип файла
 		if (!in_array($_FILES['picture']['type'], $types)){
 			die("Запрещённый тип файла: ".$_FILES['picture1']['name']);
 		}
		if (!@copy($_FILES['picture1']['tmp_name'], $path . $_FILES['picture1']['name'])){
			echo 'Что-то пошло не так';
		}
		else {
			echo "Загрузка файла ".$_FILES['picture1']['name']." завершена удачно.";
		}
	}

?>

</div>