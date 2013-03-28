<div class="contents">

<?php  
	if ( isset ($_GET)) { 
		foreach ($_GET as $key=>$val) { 
			$$key=$val; 
		} 
	}

# функция транслитерации кириллического названия.
function translit($str){
    $alphavit = array(
    /* строчные буквы */
    "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d","е"=>"e",
    "ё"=>"yo","ж"=>"zh","з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l", "м"=>"m",
    "н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t",
    "у"=>"u","ф"=>"f","х"=>"h","ц"=>"ts","ч"=>"ch", "ш"=>"sh","щ"=>"sch",
    "ы"=>"i","э"=>"e","ю"=>"yu","я"=>"ya",
    /* заглавные буквы */
    "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D","Е"=>"E", "Ё"=>"Yo",
    "Ж"=>"Zh","З"=>"Z","И"=>"I","Й"=>"Y","К"=>"K", "Л"=>"L","М"=>"M",
    "Н"=>"N","О"=>"O","П"=>"P", "Р"=>"R","С"=>"S","Т"=>"T","У"=>"U",
    "Ф"=>"F", "Х"=>"H","Ц"=>"Ts","Ч"=>"Ch","Ш"=>"Sh","Щ"=>"Sch",
    "Ы"=>"I","Э"=>"E","Ю"=>"Yu","Я"=>"Ya",
    "ь"=>"","Ь"=>"","ъ"=>"","Ъ"=>""
    );
    return strtr($str, $alphavit);
}

# функция создания директории
function dir_create ($dir_path){
	if (!is_dir($dir_path)) {
		mkdir("$dir_path");
		echo "Директория ".$dir_path." создана успешно <br>\n";
	}
	else {
		echo "Директория ".$dir_path." существует, ничего создавать не нужно. <br>\n";
	}
}

# функция изменения размера загружаемых изображений.
function image_resize($file_name, $file_type, $tmp_name, $quality = null) {
	global $tmp_path;
	//ограничиваем размер изображениях в пикселах.
	$max_width_size = 800;
	$max_preview_size = 240;

	if ($quality == null){
		$quality = 75;
	}
	if ($file_type == 'image/jpeg'){
		$source = imagecreatefromjpeg($tmp_name);
	}
	elseif ($file_type == 'image/png'){
		$source = imagecreatefrompng($tmp_name);
	}
	elseif ($file_type == 'image/gif'){
		$source = imagecreatefromgif($tmp_name);
	}
	else {
		return false;
	}

	// Определяем ширину и высоту изображения
	$src_width = imagesx($source);
	$src_height = imagesy($source);

	//если ширина загруженного изображения больше максимально допустимого размера, то делаем ресайз
	if ($src_width > $max_width_size) {
		// вычисляем пропорции
		$ratio = $src_width/$max_width_size;
		$new_width = round($src_width/$ratio);
		$new_height = round($src_height/$ratio);
		// создаём пустую картинку
		$dest_image = imagecreatetruecolor($new_width, $new_height);
		// Копируем старое изображение в новое с изменением параметров
		imagecopyresampled($dest_image, $source, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);
		// Вывод картинки и очистка памяти
		imagejpeg($dest_image, $tmp_path . $file_name, $quality);
		imagedestroy($dest_image);
		imagedestroy($source);
		return $file_name;
	}
	elseif ($src_width < $max_width_size) {
		echo "Файл ".$file_name." слишком маленький. Поищите лучшего качества.<br>\n";
	}
}

//Добавление нового населённого пункта.
if ($_POST['type'] == "town") {
	$json_index_string = file_get_contents($_GET['dirname']."index.json");
	$index_array = json_decode($json_index_string);
	$name_en = translit($_POST['Name']); //Переводим введённое название на латиницу.
	$new_dir_path = $_GET['dirname'].str_replace(" ", "_", $name_en)."/"; //Заменяем пробелы в названии на подчёркивания, создаем имя директории.
	$new_town = array("id" => $_POST['id'], "name" => $_POST['Name'], "url" => str_replace(" ", "_", $name_en)."/");

//создаём новую директорию
	$hotel_path = $new_dir_path."Hotels/";
	$data_path = $new_dir_path."data/";
	dir_create($new_dir_path); //Директория населеннного пункта.
	dir_create($hotel_path); //вложенная директория  для отелей.
	dir_create($data_path); //вложенная директория для данных.

//дописываем в массив значений данные о новом объекте
	array_push($index_array->data[0]->towns, $new_town);
	$index_array = json_encode($index_array);
	
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
	$new_json->settings[0]->nameEN = $name_en;
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
	$new_type = array();
	function data_filling ($item_type, $post_item, $new_json_string)
	{
		global $new_type;
		$new_type = array();
		$hotel_settings_json = file_get_contents("Crimea/hotel_type.json");
		$hotel_settings_json = json_decode($hotel_settings_json);
		$template_settings = $hotel_settings_json->data[0];

		if (is_array($post_item)) {
			$i = 0;
			foreach ($post_item as $value) {
				foreach ($template_settings->$item_type as $type) {
					if ($type->id == $value) {
						$new_type[] = array("id" => $value, "name" => $type->name);
					}
				}
			}
		}
		else {
			foreach ($template_settings->$item_type as $type) {
				if ($type->id == $post_item) {
					$new_type[] = array("id" => $post_item, "name" => $type->name);
				}
			}
		}
		
	}

	$new_json = file_get_contents("Crimea/template_hotel.json");
	$new_json = json_decode($new_json);


	$hotel_settings_json = file_get_contents("Crimea/hotel_type.json");
	$hotel_settings_json = json_decode($hotel_settings_json);
	$template_settings = $hotel_settings_json->data[0];

//Создаём директории.
	$name_en = translit($_POST['Name']); //Переводим введённое название на латиницу.
	$new_dir_path = $_GET['dirname']."Hotels/".str_replace(" ", "_", $name_en)."/"; //Заменяем пробелы в названии на подчёркивания, создаем имя директории.
	$data_path = $new_dir_path."data/";
//	dir_create($new_dir_path); // Создаём директории для нового отеля.
//	dir_create($data_path);

//заносим информацию о новом отеле в индексный файл родительской директории
	$json_index_string = file_get_contents($_GET['dirname']."index.json");
	$index_array = json_decode($json_index_string);
	$new_hotel = array("id" => $_POST['id'], "name" => $_POST['Name'], "url" => "Hotels/".str_replace(" ", "_", $name_en)."/");

//дописываем в массив значений данные о новом объекте
	$index_array->data[0]->hotels[] = $new_hotel;
	$index_array = json_encode($index_array);

	
//Открываем файл index.json соответствующей директории и перезаписываем его содержимое с учётом добавления нового объекта.
//	$index_file = fopen($_GET['dirname']."index.json", 'w');
//	fwrite($index_file, $index_array);
//	fclose($index_file);
//	echo "Запись в индексный файл внесена успешно <br>";

//Заполняем массив значений, чтобы свормировать индексный файл отеля.
	data_filling ("hotel_type", $_POST['hotel_type'], $new_json->data[0]->hotel_type);
	$new_json->data[0]->hotel_type = $new_type;
	data_filling ("beach_type", $_POST['beach_type'], $new_json->data[0]->beach_type);
	$new_json->data[0]->beach_type = $new_type;
	data_filling ("room_type", $_POST['room_type'], $new_json->data[0]->room_type);
	$new_json->data[0]->room_type = $new_type;
	data_filling ("meal_type", $_POST['meal_type'], $new_json->data[0]->meal_type);
	$new_json->data[0]->meal_type = $new_type;
	data_filling ("infra_type", $_POST['infra_type'], $new_json->data[0]->infra_type);
	$new_json->data[0]->infra_type = $new_type;
	data_filling ("month", $_POST['month'], $new_json->data[0]->month);
	$new_json->data[0]->month = $new_type;

	$new_json->DirName = $_POST['Name'];
	$new_json->settings[0]->name = $_POST['Name'];
	$new_json->settings[0]->nameEN = $name_en;
	$new_json->settings[0]->url = $new_dir_path;
	$new_json->settings[0]->location = $_POST['location'];
	$new_json->settings[0]->address = $_POST['address'];
	$new_json->settings[0]->email = $_POST['email'];
	$new_json->settings[0]->phone = $_POST['phone'];
	$new_json->settings[0]->skype = $_POST['skype'];
	$new_json->settings[0]->homepage = $_POST['homepage'];
	$new_json->settings[0]->gps = $_POST['gps'];
	$new_json->settings[0]->comment = $_POST['comment'];
	$new_json->settings[0]->parent[0]->name = $_GET['parentname'];
	$new_json->settings[0]->parent[0]->url = $_GET['dirname'];
	
	$new_json = json_encode($new_json);

//Записываем получившуюся строку в файл с необходимым названием и местоположением.
//	$new_index_file = fopen($new_dir_path."index.json", 'x');
//	fwrite($new_index_file, $new_json);
//	fclose($new_index_file);

//	echo "Новый объект создан успешно.";

//Обработка загруженных изображений.	
	$types = array('image/gif', 'image/png', 'image/jpeg');
	$max_file_size = 1536000;
	$tmp_path = "res/";
	//print_r($_FILES);

 	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
 		// Проверяем тип файла
 		foreach ($_FILES['picture']['name'] as $k => $v) {
 			if (isset($v)) {
 				if (!in_array($_FILES['picture']['type'][$k], $types)){
 					echo "Запрещённый тип файла: ".$v.". Файл не загружен.<br>\n";
 				}
 				else {
 					if ($_FILES['picture']['size'][$k] > $max_file_size) {
 						echo "Размер файла ".$v." больше предельно допустимых ".$max_file_size." байт. Файл не загружен.<br>\n";
 					}
 					else{
 						echo "Тип файла ".$v." годный, можно загружать.<br>\n";
 						$pic_name = image_resize($_FILES['picture']['name'][$k] , $_FILES['picture']['type'][$k] , $_FILES['picture']['tmp_name'][$k], 60);

	 					if (!is_dir($data_path)) {
 							dir_create($data_path);
 						}
 						if (!@copy($tmp_path.$pic_name, $data_path.$pic_name)){
							echo "Не удалось загрузить файл ".$pic_name." по указанному пути: ".$data_path."<br>\n";
						}
						else {
							echo "Загрузка файла ".$v." завершена удачно.<br>\n";
						}
					}
 				}
 			}
 		}
	/*	if (!@copy($_FILES['picture1']['tmp_name'], $data_path . $_FILES['picture1']['name'])){
			echo 'Что-то пошло не так';
		}
		else {
			echo "Загрузка файла ".$_FILES['picture1']['name']." завершена удачно.";
		} */
	}
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
	echo "<p> <a href=\"".$_SERVER['PHP_SELF']."?action=edit&dirname=".$_GET['dirname']."\">Вернуться в родительский раздел</a>";
?>

</div>