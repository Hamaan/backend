<?php
	if ( isset ($_GET)) { foreach ($_GET as $key=>$val) { $$key=$val; } } 
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
		
		$jsonString = file_get_contents("res/zones.json");
		$TownSelect = json_decode($jsonString);
		$line = count($select_array->Contents[7]->zoneTown);
		$k = $_REQUEST['id'] +1;
		//$TownSelect = array($select_array->Contents[$_REQUEST['id']]->zoneTown->townName);
		//$TownSelect = array(0 => array('BMW', 'Mercedes-Benz', 'Audi', 'Subaru', 'Mazda', 'ВАЗ', 'ЗАЗ', 'ГАЗ'),1 => array('PHP', 'Java', 'Javascript', 'Python', 'C++', 'C', 'C#'),);
		//if (is_numeric ($_REQUEST['id']) && is_array ($TownSelect[$_REQUEST['id']])){
		for ($i=0; $i < $line; $i++) { 
			echo "<option value='".$i."'>".$select_array->Contents[$k]->zoneTown[$i]->$item_name . "</option>\n";
			//echo $menu_array->Contents[$i]->$item_name . "<br>";
			}
			

		//if (is_numeric ($_REQUEST['id']) && is_array ($TownSelect->Contents[$_REQUEST['id']])){
		//	foreach ($TownSelect->Contents[$_REQUEST['id']]->zoneTown as $id => $value){
		//		echo '<option value="' . $id . '">' . $value . '</option>';
		//	}
		}
	} 
	else {
		echo 'Bad request!';
		exit;
	} 
?>