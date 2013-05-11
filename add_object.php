<?php
	if (!$_GET['action']) {
		$top = implode ( "", file ( "top.html" ) ); 
		print $top;
		print "\n";
	}
?>

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
	# ограничиваем размер изображениях в пикселах.
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

	# Определяем ширину и высоту изображения
	$src_width = imagesx($source);
	$src_height = imagesy($source);

	# если ширина загруженного изображения больше максимально допустимого размера, то делаем ресайз
	if ($src_width > $max_width_size) {
		# вычисляем пропорции
		$ratio = $src_width/$max_width_size;
		$new_width = round($src_width/$ratio);
		$new_height = round($src_height/$ratio);
		# создаём пустую картинку
		$dest_image = imagecreatetruecolor($new_width, $new_height);
		# Копируем старое изображение в новое с изменением параметров
		imagecopyresampled($dest_image, $source, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);
		# Вывод картинки и очистка памяти
		imagejpeg($dest_image, $tmp_path . $file_name, $quality);
		imagedestroy($dest_image);
		imagedestroy($source);
		return $file_name;
	}
/*	elseif ($src_width < $max_width_size) {
		echo "Файл ".$file_name." слишком маленький. Поищите лучшего качества.<br>\n";
	} */
}

function data_filling ($item_type, $post_item, $new_json_string){
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

//Задание массива, определяющего размер обрезаемых изображений.
$crop1 = array(640, 340);
$crop2 = array(123, 85);
$crop3 = array(640, 960);
$crop_array = array('crop1'=>$crop1, 'crop2'=>$crop2, 'crop3'=>$crop3);



if ($_GET['change'] == "true") {
	if ($_POST['type'] == "hotel") {
		$json_index_string = file_get_contents($_POST['parenturl']."index.json");
		$index_array = json_decode($json_index_string);
		$name_en = translit($_POST['Name']); # Переводим введённое название на латиницу.
		//print_r($index_array);
		foreach ($index_array->data[0]->hotels as $hotels) {
			if ($hotels->id == $_POST['id']) {
				$hotels->name = $_POST['Name'];
			}
		}
		$index_array = json_encode($index_array);
		$index_file = fopen($_POST['parenturl']."index.json", 'w');
		fwrite($index_file, $index_array);
		fclose($index_file);
		echo "Запись в индексном файле изменена успешно <br>\n";


	}
	//echo "<p> Bingo!!!\n<br>";
	//print_r($_POST);
	//echo "\n</p>\n";
}
else {
# Добавление нового населённого пункта.
	if ($_POST['type'] == "town") {
		$json_index_string = file_get_contents($_GET['dirname']."index.json");
		$index_array = json_decode($json_index_string);
		$name_en = translit($_POST['Name']); # Переводим введённое название на латиницу.
		$new_dir_path = $_GET['dirname'].str_replace(" ", "_", $name_en)."/"; # Заменяем пробелы в названии на подчёркивания, создаем имя директории.
		$new_town = array("id" => $_POST['id'], "name" => $_POST['Name'], "url" => str_replace(" ", "_", $name_en)."/");

# создаём новую директорию
		$hotel_path = $new_dir_path."_Hotels/";
		$data_path = $new_dir_path."_Data/";
		dir_create($new_dir_path); # Директория населеннного пункта.
		dir_create($hotel_path); # вложенная директория  для отелей.
		dir_create($data_path); # вложенная директория для данных.

# дописываем в массив значений данные о новом объекте
		array_push($index_array->data[0]->towns, $new_town);
		$index_array = json_encode($index_array);
	
# Открываем файл index.json соответствующей директории и перезаписываем его содержимое с учётом добавления нового объекта.
		$index_file = fopen($_GET['dirname']."index.json", 'w');
		fwrite($index_file, $index_array);
		fclose($index_file);
		echo "Запись в индексный файл внесена успешно <br>\n";

	
# Открываем файл-шаблон JSON.
		$new_json = file_get_contents("Crimea/template.json");
		$new_json = json_decode($new_json);
# Запихиваем реальные значения.
		$new_json->DirName = $_POST['Name'];
		$new_json->settings[0]->name = $_POST['Name'];
		$new_json->settings[0]->nameEN = $name_en;
		$new_json->settings[0]->url = $new_dir_path;
		$new_json->settings[0]->parent[0]->name = $_GET['parentname'];
		$new_json->settings[0]->parent[0]->url = $_GET['dirname'];
		$new_json = json_encode($new_json);
# Записываем получившуюся строку в файл с необходимым названием и местоположением.
		$new_index_file = fopen($new_dir_path."index.json", 'x');
		fwrite($new_index_file, $new_json);
		fclose($new_index_file);

		echo "Новый объект создан успешно.";

	}


# Добавление нового отеля.
	elseif ($_POST['type'] == "hotel") {
		$new_type = array();
		
		$new_json = file_get_contents("Crimea/template_hotel.json");
		$new_json = json_decode($new_json);
		$hotel_settings_json = file_get_contents("Crimea/hotel_type.json");
		$hotel_settings_json = json_decode($hotel_settings_json);
		$template_settings = $hotel_settings_json->data[0];


# Создаём директории.
		$name_en = translit($_POST['Name']); # Переводим введённое название на латиницу.
		$new_dir_path = $_GET['dirname']."_Hotels/".str_replace(" ", "_", $name_en)."/"; # Заменяем пробелы в названии на подчёркивания, создаем имя директории.
		$parent_dir = $_GET['dirname'];
		$data_path = $new_dir_path."_Data/";


# заносим информацию о новом отеле в индексный файл родительской директории
		$json_index_string = file_get_contents($_GET['dirname']."index.json");
		$index_array = json_decode($json_index_string);
		$new_hotel = array("id" => $_POST['id'], "name" => $_POST['Name'], "url" => "_Hotels/".str_replace(" ", "_", $name_en)."/");

		foreach ($index_array->data[0]->hotels as $key ) {
			if ($key->id == $_POST['id']) {
				die("Объект с таким ID уже существует в базе");
			}
		}

		dir_create($new_dir_path); # Создаём директории для нового отеля.
		dir_create($data_path);

# дописываем в массив значений данные о новом объекте
		$index_array->data[0]->hotels[] = $new_hotel;
		$index_array = json_encode($index_array);

	
# Открываем файл index.json соответствующей директории и перезаписываем его содержимое с учётом добавления нового объекта.
		$index_file = fopen($_GET['dirname']."index.json", 'w');
		fwrite($index_file, $index_array);
		fclose($index_file);
		echo "Запись в индексный файл внесена успешно <br>";

# Заполняем массив значений, чтобы сформировать индексный файл отеля.
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
	

# Обработка загруженных изображений.	
		$types = array('image/gif', 'image/png', 'image/jpeg');
		$max_file_size = 153600000;
		$tmp_path = "res/";

	 	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
 			# Проверяем тип файла
 			foreach ($_FILES['picture']['name'] as $k => $v) {
 				if (isset($v)) {
 					if (!in_array($_FILES['picture']['type'][$k], $types)){
 						echo "Запрещённый тип файла: ".$v.". Файл не загружен.<br>\n";
	 				}
 					else {
 						if ($_FILES['picture']['size'][$k] > $max_file_size) {
 							echo "Размер файла ".$v." больше предельно допустимых ".$max_file_size	." байт. Файл не загружен.<br>\n";
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
								$new_pic = array("id" => $k, "name" => $_FILES['picture']['name'][$k]);
								$new_json->settings[0]->pics[] = $new_pic;
								echo "Загрузка файла ".$v." завершена удачно.<br>\n";
								# Удаляем временный файл
								unlink($tmp_path . $pic_name);
								$max_id = 0;
								foreach ($new_json->settings[0]->pics as $pics) {
									if ($pics->id > $max_id) {
										$max_id	= $pics->id;
									}
								}

								foreach ($crop_array as $key => $value) {
									$max_id++;
									$new_pic = array("id" => $max_id, "name" => $key.$_FILES['picture']['name'][$k]);
									echo "<p>\n <img id=\"".$key."\" src=\"".$data_path.$v."\" alt=\"\" title=\"\" style=\"margin: 0 0 0 10px;\" />\n </p>\n";
								}
									echo "
									<form action=\"add_object.php\" method=\"post\">";
								foreach ($crop_array as $key => $value) {
									echo "
   									<input type=\"hidden\" name=\"x1".$key."\" value=\"\" />
   									<input type=\"hidden\" name=\"y1".$key."\" value=\"\" />
   									<input type=\"hidden\" name=\"x2".$key."\" value=\"\" />
   									<input type=\"hidden\" name=\"y2".$key."\" value=\"\" />
   									<input type=\"hidden\" name=\"w".$key."\" value=\"\" />
   									<input type=\"hidden\" name=\"h".$key."\" value=\"\" />
	   								<input type=\"hidden\" name=\"".$key."\" value=\"".$v."\" />
	   								";
	   							}
	   							echo "
	   								<input type=\"hidden\" name=\"dir\" value=\"".$data_path."\" />
	   								<input type=\"hidden\" name=\"parent_dir\" value=\"".$parent_dir."\" />
   									<input type=\"submit\" value=\"Обрезать\" />
									</form>
								";
								echo "<script type=\"text/javascript\"> \n";
								foreach ($crop_array as $key => $value) {
									echo "
									function preview(img, selection) {
									    var scaleX = ".$value[0]." / (selection.width || 1);
									    var scaleY = ".$value[1]." / (selection.height || 1);
								    	$('#".$key." + div > img').css({
									    	    width: Math.round(scaleX * 600) + 'px',
									        	height: Math.round(scaleY * 400) + 'px',
										        marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
								        marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
									    });
									} 

									$(document).ready(function () {

	
									    $('#".$key."').imgAreaSelect({
									        aspectRatio: '".$value[0].":".$value[1]."',
									        handles: true,
									        onSelectChange: preview,
									        onSelectEnd: function ( image, selection ) {
									            $('input[name=x1".$key."]').val(selection.x1);
									            $('input[name=y1".$key."]').val(selection.y1);
									            $('input[name=x2".$key."]').val(selection.x2);
								    	        $('input[name=y2".$key."]').val(selection.y2);
								        	    $('input[name=w".$key."]').val(selection.width);
								            	$('input[name=h".$key."]').val(selection.height);
									        }
									    });
									}); ";
								}
									
								echo "</script>";
							}
						}
					}
 				}
	 		}
		}
# Записываем получившуюся строку в файл с необходимым названием и местоположением.
		//print_r($new_json);
		$new_json = json_encode($new_json);
		$new_index_file = fopen($new_dir_path."index.json", 'x');
		fwrite($new_index_file, $new_json);
		fclose($new_index_file);
		echo "Новый объект создан успешно.";
	}
	foreach ($crop_array as $key => $value) {
		if (isset($_POST[$key])) {

			// Original image
			$filename = $_POST[$key];
			$dir = $_POST['dir'];
			$new_filename = $key.$filename;

			// Get dimensions of the original image
			list($current_width, $current_height) = getimagesize($dir.$filename);

			// The x and y coordinates on the original image where we
			// will begin cropping the image, taken from the form
			$x1    = $_POST['x1'.$key];
			$y1    = $_POST['y1'.$key];
			$x2    = $_POST['x2'.$key];
			$y2    = $_POST['y2'.$key];
			$w    = $_POST['w'.$key];
			$h    = $_POST['h'.$key];     

			//die(print_r($_POST));

			// This will be the final size of the image
			$crop_width = $value[0];
			$crop_height = $value[1];

			// Create our small image
			$new = imagecreatetruecolor($crop_width, $crop_height);
			// Create original image
			$current_image = imagecreatefromjpeg($dir.$filename);
			// resamling (actual cropping)
			imagecopyresampled($new, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height, $w, $h);
			// creating our new image
			imagejpeg($new, $dir.$new_filename, 75);
		}
	}
}

echo "<p> <a href=\"admin_index.php?action=edit&dirname=".$_POST['parent_dir']."\">Вернуться в родительский раздел</a>";
?>

</div>