<?php // Необратимое улучшение
      error_reporting(0);
	 /* session_start();*/
?>
<!DOCTYPE html >
<html>
<head>
<meta charset="utf-8">

<title>Техдемка, рабочая версия 0.0.4a</title>
<link href="styles.css" rel="stylesheet"/>
<script src="jquery.js"></script>
<script src="index.js"></script>
</head>
<body>

<div id="container">
<div id="main-block">
&nbsp; &nbsp; <a href = "exit.php">Перезапустить </a>
<table>
		<COLGROUP>
			<COL width = 700px>
			<COL width = 300px>
		</COLGROUP>
<tr>
<td>
	<div class="box_table">
	<table border="0">
	<?php  //визуализация; в будущем переписать через вызов функции!
        require_once('generation.php');
		for($j = 1; $j <= $Height; $j++) {
			echo '<tr>';
			for($i = 1; $i <= $Width; $i++) {
			if($map[$i][$j][1] == 3 AND ($x!=$i OR $y!=$j) AND $bool == true/*($i != $monster_position[$num][0] OR $j != $monster_position[$num][1])*/){
				echo '<td class=\'ore\'>';
				}
				else {
				    echo '<td>';
				}    // поправимо улучшенно.
				$bool = true;
				for($n = 1; $n <= $max; $n++)
					if($i!= $monster_position[$n][0] OR $j != $monster_position[$n][1]) $bool = true;
					else {
							$bool = false;
							break;
						 }
				if(($x!=$i OR $y!=$j) AND $map[$i][$j][0] != 2 AND $map[$i][$j][1] == 0 AND $bool == true) echo ''; //прорисовка поля
				if($map[$i][$j][0] == 2) echo '<div class="wall">#</div>'; //прорисовка стены
				if($x==$i AND $y==$j) if($health > 0) echo '<div class="hero">@</div>'; else echo '<font color="black">@</font>';//прорисовка героя
				/*if($map[$i][$j][1] == 3 AND ($x!=$i OR $y!=$j) AND $i == $monster_position[$num][0] AND $j == $monster_position[$num][1]) {
					$monster_backpack[] = 3;
					$map[$i][$j][1] = 0;
					$_SESSION['monster_backpack'] = $monster_backpack;
					$_SESSION['map'] = $map;
				}*/
				//if($map[$i][$j][1] == 3 AND ($x!=$i OR $y!=$j) AND $bool == true/*($i != $monster_position[$num][0] OR $j != $monster_position[$num][1])*/) echo '<div class="ore"></div>'; //прорисовка вещи
				if($map[$i][$j][1] == 4 AND ($x!=$i OR $y!=$j) AND $bool == true/*($i != $monster_position[$num][0] OR $j != $monster_position[$num][1])*/) echo '<div class="chain_sword_red"></div>'; //прорисовка оружия
				for($n = 1; $n <= $max; $n++) if($i == $monster_position[$n][0] AND $j == $monster_position[$n][1]) if($monster_health[$n] > 0) echo '<div class="orc">T</font>'; else echo '<div class="orc wound">T</div>'; //прорисовка монстра
				echo '</td>';
			}
			echo '</tr>';
		}
	?>
	</table>
	</div>
</td>
<td valign="top">
	<table>
		<tr>
			<td>
				<div class="drive">
					<?php
						if($health != 0) {
							echo '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a id="moveup" href = "index.php?direct=8">&uarr; </a><br> <br>';
							echo '<a id="moveleft" href = "index.php?direct=4">&larr; </a>&nbsp; &nbsp; &nbsp;';
							echo '<a href = "index.php?direct=5">stop </a>&nbsp; &nbsp; &nbsp;';
							echo '<a id="moveright" href = "index.php?direct=6"> &rarr;</a>&nbsp; &nbsp; &nbsp <br><br>';
							echo '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a id="movedown" href = "index.php?direct=2"> &darr;</a>';
						}
					?>
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<div class="message"> <!-- В данном блоке выводятся сообщения о текущих событиях-->
					<?php
							if($map[$x][$y][0] == 0) $messageCollision = $messageCollision . ' Вы стоите в дверном проеме';
							if($map[$x][$y][0] == 10) $messageCollision = $messageCollision . ' Вы в комнате №1';
							if($map[$x][$y][0] == 20) $messageCollision = $messageCollision . ' Вы в коридоре';
							if($map[$x][$y][0] == 30) $messageCollision = $messageCollision . ' Вы в комнате №3';
							if($map[$x][$y][1] == 3) $messageCollision = $messageCollision . ' Тут лежит Вещь. <a id="pickup" href="index.php?up=1">Подобрать?</a>';
							if($map[$x][$y][1] == 4) $messageCollision = $messageCollision . ' Тут лежит оружие. <a id="pickup" href="index.php?up=2">Подобрать?</a>';
						echo $messageCollision;
					?>
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<div class="backpack"> <!-- В данном блоке выводятся сообщения о содержимом рюкзака-->
					У вас в рюкзаке<br>
						<?php
							for($i = 0; $i < count($backpack); $i++) {
								if($backpack[$i] == 3) {    //определяем, есть ли в рюкзаке руда
									echo 'Кусок синей руды  ';
									if($map[$x][$y][1] == 0) echo '<a href="index.php?down=' . $i . '">выбросить</a><br>';
									if($map[$x][$y][1] != 0) echo '<br>';
								}
								if($backpack[$i] == 4) { //определяем, есть ли в рюкзаке оружие
									echo 'Оружие  ';
									if($map[$x][$y][1] == 0) echo '<a href="index.php?down=' . $i . '">выбросить</a> <a href="index.php?equip=' . $i . '"> надеть</a><br>';
									if($map[$x][$y][1] != 0) echo '<br>';
								}
							}
						?>
				</div>
			</td>
		</tr>
		<tr>
			<td valign="top">
				<div class="equip"> <!-- В данном блоке выводятся сообщения о содержимом рюкзака-->
					Экипировка<br>
						<?php
							/*for($i = 0; $i < count($backpack); $i++) {
								if($backpack[$i] == 3) {    //определяем, есть ли в рюкзаке руда
									echo 'Кусок синей руды  ';
									if($map[$x][$y][1] == 0) echo '<a href="index.php?down=' . $i . '">выбросить</a><br>';
									if($map[$x][$y][1] != 0) echo '<br>';
								}
								if($backpack[$i] == 4) { //определяем, есть ли в рюкзаке оружие
									echo 'Оружие  ';
									if($map[$x][$y][1] == 0) echo '<a href="index.php?down=' . $i . '">выбросить</a><br>';
									if($map[$x][$y][1] != 0) echo '<br>';
								}
							}*/
						?>	
				</div>
			</td>
		</tr>				
	</table>
</td>
</tr>
</table>	
</div>
<div class="clearfloat"></div>
<div class="empty"></div>
</div>
<div id="footer">&copy 2012 Webler LLC &nbsp;
<?php
    $all_data = array('Monster' => $monster_position, 'Height' => $Height, 'Width' => $Width);
            $all_data = json_encode($all_data);
            echo '<div id="json" style="display:none">'. $all_data .'</div>';
?>
</div>
<script>
                  var json = $('#json').text();
                  var global_obj = $.parseJSON(json);
                  console.log(global_obj);
                  for (var i = 0; i<global_obj.Width; i++){
                          jQuery('#tab1').append('<tr class='+i+'>');
                  }
                  jQuery('#tab1 tr').each(function(){
                     for (var i = 0; i<global_obj.Height; i++){
                           jQuery(this).append('<td class='+i+'>');
                      }
                  });
</script>
</div>	
</body>
</html>