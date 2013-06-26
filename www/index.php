<?php
	require_once('func.php');
	require_once('class.php');
	global 	$Width, $Height;//, $max;
	//$max = 2;
	$messageCollision = '';
	mt_srand(make_seed()); //инициализация генератора случайных чисел
	session_start();
	if(isset($_SESSION['max'])) $max = $_SESSION['max'];
	//echo $max;	
	$turn = 0; $turn = $_SESSION['turn']; $turn++; $_SESSION['turn'] = $turn; //ход
	if(!isset($_SESSION['map'])) $map = generate_room(); else $map = $_SESSION['map'];//генерация либо считывание из сессии карты
	if(!isset($_SESSION['map'])) $backpack = generate_backpack(); else $backpack = $_SESSION['backpack'];//генерация либо считывание из сессии рюкзака
	if(!isset($_SESSION['map'])) $monster_backpack = generate_backpack(); else $monster_backpack = $_SESSION['monster_backpack'];//генерация либо считывание из сессии рюкзака	
	if(isset($_GET['direct'])) $direction = $_GET['direct']; else $direction = 0; //куда двигаемся?
	if ($turn == 1) $messageCollision = 'Подземелье. Темно и сыро.';
	if(isset($_GET['up'])) $up = $_GET['up']; else $up = 0; //поднимаем ли мы предмет?
	if(isset($_GET['down'])) $down = $_GET['down']; else $down = -1; //бросаем ли мы предмет?
	
	if(isset($_SESSION['object'])) $object = $_SESSION['object']; else $object = new Player; //создаем героя
	$player_damage = $object->getDamage(); //урон игрока
	$health = $object->getHp(); //здоровье игрока
	if ($turn == 1)   //если координаты героя только сгенерированы, берем их из сессии
		{
			$x = $_SESSION['x']; 
			$y = $_SESSION['y'];
			$coord[0] = $x;
			$coord[1] = $y;
			$object->setCoord($coord); //перезапись координат игрока из сессии в объект
		} else {  //иначе берем координаты героя и монстров из данных класса
			$coord = $object->getCoord();
			$x = $coord[0];
			$y = $coord[1];
		}
	/*if($down == 1) {
		unset($backpack[count($backpack) - 1]);
		$map[$x][$y][1] = 3;
		$_SESSION['backpack'] = $backpack;
		$_SESSION['map'] = $map;
		$_GET['down'] = 0;
	}
	if($down == 2) {
		unset($backpack[count($backpack) - 1]);
		$map[$x][$y][1] = 4;
		$_SESSION['backpack'] = $backpack;
		$_SESSION['map'] = $map;
		$_GET['down'] = 0;
	}*/
	if($down != -1) {
		for($i = 0; $i < count($backpack); $i++) {
			if($down == $i) {
				$map[$x][$y][1] = $backpack[$i];
				$backpack[$i] = 0;
				//$map[$x][$y][1] = 3;
				$_SESSION['backpack'] = $backpack;
				$_SESSION['map'] = $map;
				$_GET['down'] = 0;
			}
		}
	}
	if($up == 1) {
		$backpack[] = 3;
		$map[$x][$y][1] = 0;
		$_SESSION['backpack'] = $backpack;
		$_SESSION['map'] = $map;
		$_GET['up'] = 0;
	}
	if($up == 2) {
		$backpack[] = 4;
		$map[$x][$y][1] = 0;
		$_SESSION['backpack'] = $backpack;
		$_SESSION['map'] = $map;
		$_GET['up'] = 0;
	}
	for($i = 0; $i < count($backpack); $i++) {
		if($backpack[$i] == 4)  $object->setDamage(3);//есть ли в рюкзаке оружие, увеличиваем урон до 3
		else $object->setDamage(1);
	}

	for($a = 1; $a <= $max; $a++) {
		if(isset($_SESSION['mons_obj'][$a])) $mons_obj[$a] = $_SESSION['mons_obj'][$a]; else $mons_obj[$a] = new Monster; //создаем монстра 1
		$monster_damage[$a] = $mons_obj[$a]->getDamage();
		$monster_health[$a] = $mons_obj[$a]->getHp();
	}

	if ($turn == 1)   //если координаты монстров только сгенерированы, берем их из сессии
		{
			for($a = 1; $a <= $max; $a++) {
				$monster_position[$a] = $_SESSION['monster_position'][$a];
				$mons_obj[$a]->setCoord($monster_position[$a]);
			}
		} else {  //иначе берем координаты героя и монстров из данных класса
			for($a = 1; $a <= $max; $a++) {
				if ($monster_health[$a] > 0 )	$monster_position[$a] = $mons_obj[$a]->getCoord();
				if ($monster_health[$a] < 0 )	{
					unset($monster_position[$a][0]);
					unset($monster_position[$a][1]);
					}
			}
		}
	$bool = true;		//переменная, отвечающая за столкновения с монстрами
	for($a = 1; $a <= $max; $a++) {
		if(obstacle_monster($x, $y, $direction, $a) == false AND $direction !=0) {
				if($monster_health[$a] > 0) {
					echo 'Вы нанесли по монстру удар!';
					$monster_health[$a] = $monster_health[$a] - $player_damage;
					}
				$mons_obj[$a]->setHp($monster_health[$a]);
			}
		if(obstacle($x, $y, $direction, $a) == false) {
			$bool = false;
			break;
		}
	}

	if($direction == 1 AND $bool == true AND $health > 0) {
		$temp = $y-1;
		if($temp == 0 ) $y = $temp+1; else $y = $temp;
		$coord[0] = $x;
		$coord[1] = $y;
		$object->setCoord($coord);
	}
	if($direction == 2 AND  $bool == true AND  $health > 0) {
		$temp = $x-1;
		if($temp == 0 ) $x = $temp+1; else $x = $temp;
		$coord[0] = $x;
		$coord[1] = $y;
		$object->setCoord($coord);
	}
	if($direction == 3 AND  $bool == true AND $health > 0) {
		$temp = $y+1;
		if($temp == $Height+1) $y = $temp-1; else $y = $temp;
		$coord[0] = $x;
		$coord[1] = $y;
		$object->setCoord($coord);
	}
	if($direction == 4 AND  $bool == true AND $health > 0) {
		$temp = $x+1;
		if($temp == $Width+1) $x = $temp-1; else $x = $temp;
		$coord[0] = $x;
		$coord[1] = $y;
		$object->setCoord($coord);
	}

for($num = 1; $num <= $max; $num++) {	//необходимо будет оптимизировать эту функцию
	if($monster_health[$num] > 0) $dir = mt_rand(1,4); else $dir = 0;//случайный выбор направления движения монстра
		if($dir == 1 AND obstacle($monster_position[$num][0], $monster_position[$num][1], $dir, $num) == true) {
			/*$temp = $monster_position[$num][1] - 1;
			$bool = true;		//переменная, отвечающая за столкновения с монстрами
			for($a = 1; $a <= $max; $a++) if($monster_position[$a][0] == $monster_position[$num][0] AND $monster_position[$a][1] == $temp) {$bool = false; echo 'Пересечение снизу вверх!'; break;}
			if($temp == 0 OR $bool == false OR ($monster_position[$num][0]==$x AND $temp==$y)) $monster_position[$num][1] = $temp+1; else $monster_position[$num][1] = $temp;
			$mons_obj[$num]->setCoord($monster_position[$num]);
			if($monster_position[$num][0]==$x AND $temp==$y) {
				echo 'Монстр решил вас ударить!';
				$health = $health - $monster_damage[$num];
				$object->setHp($health);
			}*/
		}
		if($dir == 2 AND obstacle($monster_position[$num][0], $monster_position[$num][1], $dir, $num) == true) {
			/*$temp = $monster_position[$num][0] - 1;
			$bool = true;		//переменная, отвечающая за столкновения с монстрами
			for($a = 1; $a <= $max; $a++) if($monster_position[$a][0] == $temp AND $monster_position[$a][1] == $monster_position[$num][1]) {$bool = false; echo 'Пересечение справа налево!'; break;}
			if($temp == 0 OR $bool == false OR ($temp==$x AND $monster_position[$num][1]==$y)) $monster_position[$num][0] = $temp+1; else $monster_position[$num][0] = $temp;
			$mons_obj[$num]->setCoord($monster_position[$num]);
			if($temp==$x AND $monster_position[$num][1]==$y) {
				echo 'Монстр решил вас ударить!';
				$health = $health - $monster_damage[$num];
				$object->setHp($health);
			}*/
		}
		if($dir == 3 AND obstacle($monster_position[$num][0], $monster_position[$num][1], $dir, $num) == true) {
			/*$temp = $monster_position[$num][1] + 1;
			$bool = true;		//переменная, отвечающая за столкновения с монстрами
			for($a = 1; $a <= $max; $a++) if($monster_position[$a][0] == $monster_position[$num][0] AND $monster_position[$a][1] == $temp) {$bool = false; echo 'Пересечение сверху вниз!'; break;}
			if($temp == $Height +1 OR $temp == false OR ($monster_position[$num][0]==$x AND $temp==$y)) $monster_position[$num][1] = $temp-1; else $monster_position[$num][1] = $temp;
			$mons_obj[$num]->setCoord($monster_position[$num]);
			if($monster_position[$num][0]==$x AND $temp==$y)  {
				echo 'Монстр решил вас ударить!';
				$health = $health - $monster_damage[$num];
				$object->setHp($health);
			}*/
		}
		if($dir == 4 AND obstacle($monster_position[$num][0], $monster_position[$num][1], $dir, $num) == true) {
			/*$temp = $monster_position[$num][0] + 1;
			$bool = true;		//переменная, отвечающая за столкновения с монстрами
			for($a = 1; $a <= $max; $a++) if($monster_position[$a][0] == $temp AND $monster_position[$a][1] == $monster_position[$num][1]) {$bool = false; echo 'Пересечение слева направо!'; break;}
			if($temp == $Width+1 OR $temp == false OR ($temp==$x AND $monster_position[$num][1]==$y)) $monster_position[$num][0] = $temp-1; else $monster_position[$num][0] = $temp;
			$mons_obj[$num]->setCoord($monster_position[$num]);
			if($temp==$x AND $monster_position[$num][1]==$y) {
				echo 'Монстр решил вас ударить!';
				$health = $health - $monster_damage[$num];
				$object->setHp($health);
			}*/
		}
	}

	//echo '&nbsp; &nbsp;monster_x = ' . $monster_position[1][0] . ', monster_y = ' . $monster_position[1][1] . ', monster2_x = ' . $monster_position[2][0] . ', monster2_y = ' . $monster_position[2][1];
	//for($i = 1; $i <= $max; $i++) echo '&nbsp;monster_x#' . $i . ' = ' . $monster_position[$i][0] . ', monster_y#' . $i . ' = ' . $monster_position[$i][1];
	echo '<br>&nbsp; &nbsp;x = ' . $x . ', y = ' . $y;
	if($health == 0) echo '<br>Вы бесславно погибли'; else echo '<br> &nbsp; &nbsp; Ваш запас здоровья ' . $health . ', урон ' . $player_damage;

	for($i = 1; $i <= $max; $i++) {
		if(isset($monster_health[$i]) AND isset($monster_position[$i][0])) {
			if($monster_health[$i] < 0){
				echo ' Монстр умер в страшных мучениях! ';
				//unset($_SESSION['mons_obj']);
				//unset($_SESSION['monster_position']);
				unset($mons_obj[$i]);
				unset($monster_position[$i][0]);
				unset($monster_position[$i][1]);
				unset($monster_health[$i]);
			}
		}
	}
	//if($monster_health[1] > 0) echo '&nbsp; Запас здоровья монстра ' . $monster_health[1] ;
	$_SESSION['object'] = $object;
	for($i = 1; $i <= $max; $i++) { if(isset($mons_obj[$i])) $_SESSION['mons_obj'][$i] = $mons_obj[$i]; }

	//if(isset($mons_obj[1])) $_SESSION['mons_obj'][1] = $mons_obj[1];
	//if(isset($mons_obj[2])) $_SESSION['mons_obj'][2] = $mons_obj[2];

?>
<!DOCTYPE html >
<html>
<head>
<meta charset="utf-8">

<title>Техдемка</title>
<link href="styles.css" rel="stylesheet"/>
<script src="jquery.js"></script>
<script>
    jQuery(document).ready(function(){
       $('a').on('click', function(e){
           e.preventDefault();
           var link = jQuery(this).attr('href');
           jQuery('body').load(''+link+'#container');
       });

    });
    jQuery(window).load(function(){
           $(document).keyup(function(e) {
               e.preventDefault();
               if(e.keyCode == 37) {
                   jQuery('#moveleft').click();
               }
               else if (e.keyCode == 38){
                   jQuery('#moveup').click();
               }
               else if (e.keyCode == 39){
                   jQuery('#moveright').click();
               }
               else if (e.keyCode == 40){
                   jQuery('#movedown').click();
               }
               else if (e.keyCode == 13){
                  jQuery('#pickup').click();
               }

           });
    });
</script>
<style>

</style>
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
	<table>
	<?php  //визуализация; в будущем переписать через вызов функции!
		for($j = 1; $j <= $Height; $j++) {
			echo '<tr>';
			for($i = 1; $i <= $Width; $i++) {
				echo '<td>';
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
				if($map[$i][$j][1] == 3 AND ($x!=$i OR $y!=$j) AND $bool == true/*($i != $monster_position[$num][0] OR $j != $monster_position[$num][1])*/) echo '<div class="ore"></div>'; //прорисовка вещи
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
							echo '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<a id="moveup" href = "index.php?direct=1">&uarr; </a><br> <br>';
							echo '<a id="moveleft" href = "index.php?direct=2">&larr; </a>&nbsp; &nbsp; &nbsp;';
							echo '<a id="movedown" href = "index.php?direct=3">&darr; </a>&nbsp; &nbsp; &nbsp';
							echo '<a id="moveright" href = "index.php?direct=4">&rarr; </a>';
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
<div id="footer">&copy 2012 Webler LLC &nbsp;</div>	
</body>
</html>