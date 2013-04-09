<?php
	print_r($LinksArray);
	$config_file = "Crimea/hotel_type.json";

	function menu_change ($menu_type, $item_type, $file_name, $hint) 
	/*
	При указании в качестве переменной $hint любого числа, комментарий печататься не будет.
	$menu_type = checkbox - чекбокс
	$menu_type = rollout - выпадающий список
	$menu_type = radio - радиокнопка
	*/
		{
			global $LinksArray;
			$jsonString = file_get_contents($file_name);
			$menu_array = json_decode($jsonString);

			switch ($menu_type) {
				case "rollout" :
					$check = 0;
					echo "<p>\n<select name=\"$item_type".'[]'."\" id=\"$item_type\" size=\"1\">\n<option value='0'>...</option>\n";
					foreach ($menu_array->data[0]->$item_type as $menu_item_type) {
						foreach ($LinksArray->data[0]->$item_type as $value_item_type) {
							if ($menu_item_type->id == $value_item_type->id) {
								echo "<option value='".$menu_item_type->id."' selected>".$menu_item_type->name."</option>\n";
								$check = 1;
							}
						}
						if ($check == 0){
							echo "<option value='".$menu_item_type->id."'>".$menu_item_type->name."</option>\n";
						}
						else {
							$check = 0;
						}
					}
					echo "</select>\n";
					break;
				case "checkbox" :
					echo "<p>\n";
					$check = 0;
					foreach ($menu_array->data[0]->$item_type as $menu_item_type) {
						foreach ($LinksArray->data[0]->$item_type as $value_item_type) {
							if ($menu_item_type->id == $value_item_type->id) {
								echo "<input type=\"checkbox\" name='$item_type".'[]'."' value='".$menu_item_type->id."' checked=\"checked\">".$menu_item_type->name."<br>\n";
								$check = 1;
							}
						}
						if ($check == 0){
							echo "<input type=\"checkbox\" name='$item_type".'[]'."' value='".$menu_item_type->id."'>".$menu_item_type->name."<br>\n";
						}
						else {
							$check = 0;
						}
					}
					break;
				case "radio" :
					echo "<p>\n";
					$check = 0;
					foreach ($menu_array->data[0]->$item_type as $menu_item_type) {
						foreach ($LinksArray->data[0]->$item_type as $value_item_type) {
							if ($menu_item_type->id == $value_item_type->id) {						
								echo "<input type=\"radio\" name=\"$item_type\" checked=\"checked\" value=\"".$menu_item_type->id."\">".$menu_item_type->name."<br>\n";
								$check = 1;
							}
						}
						if ($check == 0){
							echo "<input type=\"radio\" name=\"$item_type\" value=\"".$menu_item_type->id."\">".$menu_item_type->name."<br>\n";
						}
						else {
							$check = 0;
						}
					}
					break;
				case "double_rollout" :
					foreach ($LinksArray->data[0]->$item_type as $value_item_type) {
						echo "<p>\n<select name=\"$item_type".'[]'."\" id=\"$item_type\" size=\"1\">\n<option value='0'>...</option>\n";
						foreach ($menu_array->data[0]->$item_type as $menu_item_type) {
							if ($menu_item_type->id == $value_item_type->id) {
								echo "<option value='".$menu_item_type->id."' selected>".$menu_item_type->name."</option>\n";
							}
							else {
								echo "<option value='".$menu_item_type->id."'>".$menu_item_type->name."</option>\n";
							}
						}
						echo "</select>\n";
					}
			}

			if (is_numeric($hint)) {
					echo "</p>\n\n";
			}
			else {
					echo "<small>$hint</small>\n";
					echo "</p>\n\n";
			}
						
		}


	echo "<br>\n</p>\n<p>\n<h3>Изменение информации об объекте \"".$LinksArray->settings[0]->name."\":</h3> <br>";

	echo "<form action=\"admin_index.php?action=add_object&change=true&dirname=".$dirname."&parentname=".$root_set[0]->name."\" enctype=\"multipart/form-data\" method=\"post\" name=\"new_hotel\">
			<input type=\"hidden\" name=\"type\" value=\"hotel\">
			<input type=\"hidden\" name=\"id\" value=\"".$_GET['object']."\">
			<small>ID ".$_GET['object']."</small>
			<p>\n
			<p>\n
			<input type=\"text\" name=\"Name\" value=\"".$LinksArray->settings[0]->name."\" size=\"25\" />
			<small> Название</small>
			</p>\n
			<p>\n
			<input type=\"text\" name=\"location\" id=\"location\" value=\"".$LinksArray->settings[0]->location."\" size=\"25\" />
			<small>Расположение  (например \"в центре города, недалеко от рынка\")</small>
			</p>";

			echo "<small> Период работы.</small><br>\n<small>C:</small>\n";
			//menu_change ("rollout","month", $config_file, "0");
			echo "<small>По:</small>\n";
			menu_change ("double_rollout","month", $config_file, "0");
			menu_change ("rollout","hotel_type", $config_file, "Тип отеля");
			echo "<small>Классы номеров (выбрать всё, что подходит):</small>\n";
			menu_change ("checkbox","room_type", $config_file, "0");
			echo "<small>Дополнительная инфраструктура (выбрать всё, что подходит):</small>\n";
			menu_change ("checkbox","infra_type", $config_file, "0");
			echo "<small>Питание на выбор (выбрать всё, что подходит):</small>\n";
			menu_change ("checkbox","meal_type", $config_file, "0");
			echo "<small>Тип пляжа:</small>\n";
			menu_change ("radio","beach_type", $config_file, "0");		

			echo "
			<p>\n
				<input type=\"text\" name=\"address\" id=\"address\" value=\"".$LinksArray->settings[0]->address."\" size=\"25\" />
				<small>Адрес</small>
			</p>\n
			<p>\n
				<input type=\"text\" name=\"email\" id=\"email\" value=\"".$LinksArray->settings[0]->email."\" size=\"25\" />
				<small>E-mail</small>
			</p>\n
			<p>\n
				<input type=\"text\" name=\"phone\" id=\"phone\" value=\"".$LinksArray->settings[0]->phone."\" size=\"25\" />
				<small>Телефон</small>
			</p>\n
			<p>\n
				<input type=\"text\" name=\"skype\" id=\"skype\" value=\"".$LinksArray->settings[0]->skype."\" size=\"15\" />
				<small>Skype</small>
			</p>\n
			<p>\n
				<input type=\"text\" name=\"homepage\" id=\"skype\" value=\"".$LinksArray->settings[0]->homepage."\" size=\"25\" />
				<small>Сайт</small>
			</p>\n
			<p>\n
				<input type=\"text\" name=\"gps\" id=\"gps\" value=\"".$LinksArray->settings[0]->gps."\" size=\"25\" />
				<small>GPS</small>
			</p>\n
			<p>\n
				<small>Краткое описание:</small> <br>
				<textarea name=\"comment\" id=\"comment\" cols=\"48\" rows=\"8\" value=\"\">".$LinksArray->settings[0]->comment."</textarea>
			</p>\n
			<p>\n
				<small>Фотографии объекта:</small> <br>
				<input name=\"picture[]\" type=\"file\" />
			</p>\n
			<p>\n
				<input name=\"picture[]\" type=\"file\" />
			</p>\n
			<p>\n
				<input name=\"picture[]\" type=\"file\" />
			</p>\n
			<p>\n
				<input type=\"submit\" value=\"Отправить\">
			</p>\n
			</form>";
?>