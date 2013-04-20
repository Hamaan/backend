<?php
//print_r($LinksArray);

echo "<!-- br>\n</p>\n<p>\n<h3>Просмотр информации об объекте \"".$LinksArray->settings[0]->name."\":</h3> <br -->";
?>

<table width="100%" border="0" cellspacing="2" cellpadding="2" class="viewer">

<?php
foreach ($LinksArray->settings[0] as $k => $v) {
	echo "<tr>\n";
	if (is_array($v)) {
		echo "<td colspan=\"2\" class=\"spanner\">".$k.":</td>\n</tr>\n";
		foreach ($v[0] as $x => $y) {
				if (preg_match('/.jpg/i', $y)) {
					echo "<tr>\n<td></td>\n<td><img src=\"".$dirname.$hotels->url."_Data/".$y."\" width=\"200\" heidth=\"150\"></td>\n</tr>\n";
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
	echo "<td colspan=\"2\" class=\"spanner\">".$key.":</td>\n</tr>\n";
	foreach ($val as $k) {
		echo "<tr>\n<td></td>\n<td>".$k->name."</td>\n</tr>\n";
	}
}

echo "</table>\n";
echo "<!-- p>\n<a href=\"".$_SERVER['HTTP_REFERER']."\">Вернуться на предыдущую стр�