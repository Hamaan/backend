<?php
//	$user_agent = "DeadMorozzz";
//	if ( $_SERVER['HTTP_USER_AGENT'] != $user_agent ) {
//		die("Ooops...\n");
//	}
	//if ( isset ($_GET)) { foreach ($_GET as $key=>$val) { $$key=$val; } } 
 	$action = $_GET['action'];

    // если нет файла top.html, создаем его
	$top_html = "top.html";
		if (! @file_exists ($top_html)){
			@touch ($top_html);
			$fp = fopen ($top_html, "w");
			fwrite ($fp, '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
			<html>
				<HEAD>
					<TITLE>%title%</TITLE>
				</HEAD>
			<body>
			');
			fclose ($fp);
		}
	
	// если нет файла end.html, создаем его
	$end_html = "end.html";
		if (! @file_exists ($end_html)){
			@touch ($end_html);
			$fp = fopen ($end_html, "w");
			fwrite ($fp, '
			</body>
			</html>
			');
			fclose ($fp);
		}

   // загружаем верхний шаблон в переменную $top
	$top = implode ( "", file ( "top.html" ) ); 
    
    // говорим, что Title равно Php-web-дизайн.
	$title = "Adminisrtation module.";
    // меняем в верхнем шаблоне слово %title% на Php-web-дизайн.
	
	$top= str_replace ( "%title%", $title, $top );
    // печатаем верхний шаблон
	
	print $top;

	print "\n";


	// include ("menu.html");

	print "\n";

    // здесь вставляем центр странички
    if ($action == "add") {
    	include ("adding.html");
    }
    elseif ($action == "edit") {
    	include ("editing.html");
    }
    elseif ($action == "delete") {
    	include ("delete.html");
    }
    elseif ($action == "add_object"){
       	include ("add_object.php");
    }
    else {
    	include ("editing.html");
    }
    //print "<br> Значение переменной action:". $action;

   print "\n";

    // вставляем низ странички
	include ("end.html");

?>

