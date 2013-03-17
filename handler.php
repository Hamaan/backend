<?php
	if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'){
		$subcategory = array(0 => array('BMW', 'Mercedes-Benz', 'Audi', 'Subaru', 'Mazda', 'ВАЗ', 'ЗАЗ', 'ГАЗ'),1 => array('PHP', 'Java', 'Javascript', 'Python', 'C++', 'C', 'C#'),);
		if (is_numeric ($_REQUEST['id']) && is_array ($subcategory[$_REQUEST['id']])){
			foreach ($subcategory[$_REQUEST['id']] as $id => $value){
				echo '<option value="' . $id . '">' . $value . '</option>';
			}
		}
	} 
	else {
		echo 'Bad request!';
		exit;
	} 
?>