<?php
    // Если запрос идёт не из Ajax
    if( $_SERVER['HTTP_X_REQUESTED_WITH'] != "XMLHttpRequest" )
    {
        exit("Access denied!");
    }
    
    // Тут подключаем файл соединения с БД
    //require_once('dbconnect.php');
    
    // Тип запрашиваемых данных
    $type = $_POST['type'];
    
    // Названия таблиц
    //$apartments = "apartments"; // Название таблицы с квартирами
    //$gilcy = "gilcy"; // Название таблицы с жильцами
    
    $jsonString = file_get_contents("res/zones.json");
    $select_array = json_decode($jsonString);    
    
    // Если возвратить нужно список жителей
    //if( $type == "TownSelect" )
    //{
        // Номер зоны
        $zoneID = (int) $_POST['zoneID'];
        $return[0] = '<option value="0">...</option>';
        foreach ($select_array->Contents as $Contents) {
            if ($Contents->zoneID == $zoneID) {
                foreach ($Contents->zoneTown as $zoneTown) {
                    //$values[] = $zoneTown->townName;
                    $return[0] .= '<option value="'.$zoneTown->townID.'">'.$zoneTown->townName.'</option>';
                }
            }
        }

    // Преобразуем массив в JSON и выводим
    echo json_encode( $return );
?>