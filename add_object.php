<div class="contents">

<?php  
	if ( isset ($_GET)) { 
		foreach ($_GET as $key=>$val) { 
			$$key=$val; 
		} 
	}

//Добавление нового населённого пункта.
if ($_POST['type'] == "town") {

	$json_index_string = file_get_contents($_GET['dirname']."index.json");
	$index_array = json_decode($json_index_string);
	$new_town = array("id" => $_POST['id'], "name" => $_POST['Name'], "url" => $_POST['url']."/");
	
//создаём новую директорию
	$new_dir_path = $_GET['dirname'].$_POST['url']."/";
	//echo "$new_dir_path";
	if (!is_dir($new_dir_path)) {
		mkdir("$new_dir_path");
		echo "Директория ".$new_dir_path." создана успешно <br>\n";
	}
	else {
		echo "Директория ".$new_dir_path." существует, ничего создавать не нужно. <br>\n";
	}
//дописываем в массив значений данные о новом объекте
	array_push($index_array->data[0]->towns, $new_town);
	$index_array = json_encode($index_array);
//	print_r($index_array);
	
//Открываем файл index.json соответствующей директории и перезаписываем его содержимое с учётом добавления нового объекта.
	$index_file = fopen($_GET['dirname']."index.json", 'w');
	fwrite($index_file, $index_array);
	fclose($index_file);
	echo "Запись в индексный файл внесена успешно <br>";

	
//Открываем файл-шаблон JSON.
	$new_json = file_get_contents("Crimea/template.json");
	$new_json = json_decode($new_json);
//Запихиваем реальные значения.
	$new_json->DirName = $_POST['Name'];
	$new_json->settings[0]->name = $_POST['Name'];
	$new_json->settings[0]->nameEN = $_POST['url'];
	$new_json->settings[0]->url = $new_dir_path;
	$new_json->settings[0]->parent[0]->name = $_GET['parentname'];
	$new_json->settings[0]->parent[0]->url = $_GET['dirname'];
	$new_json = json_encode($new_json);
//Записываем получившуюся строку в файл с необходимым названием и местоположением.
	$new_index_file = fopen($new_dir_path."index.json", 'x');
	fwrite($new_index_file, $new_json);
	fclose($new_index_file);

	echo "Новый объект создан успешно.";


}
//Добавление нового отеля.
elseif ($_POST['type'] == "hotel") {
	# code...
}

/*
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
*/

?>

</div>