<?php
//print_r($LinksArray);


echo "<br>\n</p>\n<p>\n<h3>Просмотр информации об объекте \"".$LinksArray->settings[0]->name."\":</h3> <br>";
//echo "<p>\n";
foreach ($LinksArray->settings[0] as $k => $v) {
	if (is_array($v)) {
		echo "<span>".$k.":</span><br />\n";
		foreach ($v[0] as $x => $y) {
				if (preg_match('/.jpg/i', $y)) {
					echo "<span><img src=\"".$dirname.$hotels->url."data/".$y."\"></span>";
				}
				else {
					echo "<span>".$x.": ".$y."</span>\n";
				}
			}
		echo "<br />";
	}
	else {
		echo "<span>".$k.": ".$v."</span><br />\n";
	}
}

//foreach ($LinksArray->settings[0]->pics[0] as $pics) {
//	echo "<p><img src=\"".$dirname.$hotels->url."data/".$pics->name."></p>\n";
//	
//}
foreach ($LinksArray->data[0] as $key => $val) {
	echo "<span>".$key.": <br />\n<ul>\n";
	foreach ($val as $k) {
		echo "<li>".$k->name."</li><br />\n";
	}
	echo "</ul>\n</span>\n";
}
//echo "</p>\n";
?>