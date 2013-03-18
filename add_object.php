<div class="contents">

<?php  
	if ( isset ($_GET)) { 
		foreach ($_GET as $key=>$val) { 
			$$key=$val; 
		} 
	}

//$JsonString = json_encode($_POST);
//echo $JsonString;

$JsonString = preg_replace_callback(
    '/\\\u([0-9a-fA-F]{4})/',
    create_function('$match', 'return mb_convert_encoding("&#" . intval($match[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),
    json_encode($_POST)
);

echo $JsonString;

echo "адрес эл. почты:" .$_POST["email"]."</br>";
echo "телефон:" .$_POST["phone"]."</br>";
echo "скайп:" .$_POST["skype"]."</br>";
echo "сайт:" .$_POST["homepage"]."</br>";
echo "координаты gps:" .$_POST["gps"]."</br>";

$root_path= 'Crimea/';
if (!is_dir($root_path)){
		mkdir("$root_path");
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