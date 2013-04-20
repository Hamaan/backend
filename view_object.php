<?php
//print_r($LinksArray);

<<<<<<< HEAD
echo "<!-- br>\n</p>\n<p>\n<h3>Просмотр информации об объекте \"".$LinksArray->settings[0]->name."\":</h3> <br -->";
?>

<table width="100%" border="0" cellspacing="2" cellpadding="2" class="viewer">
=======

echo "<br>\n</p>\n<p>\n<h3>Просмотр информации об объекте \"".$LinksArray->settings[0]->name."\":</h3> <br>";
?>

<table width="100%" border="0" cellspacing="2" cellpadding="2">
>>>>>>> cbb124aec00de5f0b8e7a3b0c6b7c7d539c5067b

<?php
foreach ($LinksArray->settings[0] as $k => $v) {
	echo "<tr>\n";
	if (is_array($v)) {
<<<<<<< HEAD
		echo "<td colspan=\"2\" class=\"spanner\">".$k.":</td>\n</tr>\n";
		foreach ($v[0] as $x => $y) {
				if (preg_match('/.jpg/i', $y)) {
					echo "<tr>\n<td></td>\n<td><img src=\"".$dirname.$hotels->url."_Data/".$y."\" width=\"200\" heidth=\"150\"></td>\n</tr>\n";
=======
		echo "<td colspan=\"2\" class=\"light\">".$k.":</td>\n</tr>\n";
		foreach ($v[0] as $x => $y) {
				if (preg_match('/.jpg/i', $y)) {
					echo "<tr>\n<td></td>\n<td><img src=\"".$dirname.$hotels->url."_Data/".$y."\" width=\"200\" heith=\"150\"></td>\n</tr>\n";
>>>>>>> cbb124aec00de5f0b8e7a3b0c6b7c7d539c5067b
				}
				else {
					echo "<tr>\n<td>".$x.": </td>\n<td>".$y."</td>\n</tr>\n";
				}
			}

	}
	else {
		echo "<td width=\"20%\">".$k.": </td>\n<td>".$v."</td>\n</tr>\n";
	}
}

foreach ($LinksArray->data[0] as $key => $val) {
<<<<<<< HEAD
	echo "<td colspan=\"2\" class=\"spanner\">".$key.":</td>\n</tr>\n";
=======
	echo "<td colspan=\"2\" class=\"light\">".$key.":</td>\n</tr>\n";
>>>>>>> cbb124aec00de5f0b8e7a3b0c6b7c7d539c5067b
	foreach ($val as $k) {
		echo "<tr>\n<td></td>\n<td>".$k->name."</td>\n</tr>\n";
	}
}

echo "</table>\n";
<<<<<<< HEAD
echo "<!-- p>\n<a href=\"".$_SERVER['HTTP_REFERER']."\">Вернуться на предыдущую стр�
=======
echo "<p>\n<a href=\"".$_SERVER['HTTP_REFERER']."\">Вернуться на предыдущую страницу</a>\n</p>\n";

?>
>>>>>>> cbb124aec00de5f0b8e7a3b0c6b7c7d539c5067b
