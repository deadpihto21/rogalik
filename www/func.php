<?php
	$Width = 16; $Height = 16; //размеры игровой комнаты
	//$max = 5; //число монстров
	require_once('class.php');
	
function make_seed() {									//инициализация таймером
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	}
function initialize() {

	}
function obstacle($x, $y, $direction, $i) {  //расчет столкновений со стенками
		global $monster_position;
		$map = $_SESSION['map'];
			if($direction == 1) if ($map[$x][$y-1][0] == 2 or obstacle_monster($x, $y, $direction, $i) == false) return false; else return true;
			if($direction == 2) if ($map[$x-1][$y][0] == 2 or obstacle_monster($x, $y, $direction, $i) == false) return false; else return true;
			if($direction == 3) if ($map[$x][$y+1][0] == 2 or obstacle_monster($x, $y, $direction, $i) == false) return false; else return true;
			if($direction == 4) if ($map[$x+1][$y][0] == 2 or obstacle_monster($x, $y, $direction, $i) == false) return false; else return true;
	}
function obstacle_monster($x, $y, $direction, $num) {  //расчет столкновений
		global $monster_position;
		$map = $_SESSION['map'];
		if($direction == 1) if ($x == $monster_position[$num][0] and $y-1 == $monster_position[$num][1]) return false; else return true;
		if($direction == 2) if ($x-1 == $monster_position[$num][0] and $y == $monster_position[$num][1]) return false; else return true;
		if($direction == 3) if ($x == $monster_position[$num][0] and $y+1 == $monster_position[$num][1]) return false; else return true;
		if($direction == 4) if ($x+1 == $monster_position[$num][0] and $y == $monster_position[$num][1]) return false; else return true;		
		}
/*function move($monster_position) {  //движение монстра
		$dir = mt_rand(1,4);
		if(obstacle($monster_position[0], $monster_position[1], $dir) == TRUE) {
			$temp = $monster_position[1] - 1;
			if($temp != 0 AND $map[$monster_position[0]][$temp][0] != 2) $monster_position[1]--;
			echo 'Результат выполнения функции перемещения монстра '. $monster_position[0] .  ' ' . $monster_position[1];
		} else echo 'Выхода нет! Значение = ' . $dir;
		return $monster_position;
	}*/
function generate_backpack() {  //генерация рюкзака
	$backpack = array();
	$_SESSION['backpack'] = $backpack;
	}
function generate_room() {  //генерация комнаты
		global 	$Width, $Height, $max; //определение глобальных размеров комнаты
		for($i = 1; $i <= $Width; $i++) $map[$i][1][0] = 2; //стенка наверху комнаты
		for($j = 2; $j < $Height; $j++) $map[1][$j][0] = 2; //левая стенка
		for($j = 2; $j < $Height; $j++) $map[$Width][$j][0] = 2; //правая стенка
		for($i = 1; $i <= $Width; $i++) $map[$i][$Height][0] = 2; //стенка по низу комнаты
		
		$a = floor($Width/2); //координаты центральной
		$b = floor($Height/2); //точки
		
		$temp_coord_x = mt_rand($a - 2, $a + 2); //выбор рандомной точки примерно посередине комнаты по горизонтали
		$temp_coord_y = mt_rand($b - 1, $b + 1); //выбор рандомной точки примерно посередине комнаты по вертикали
		
		if(mt_rand(0,1) == 0) {
			for($j = 2; $j < $Height; $j++) {
				$map[$temp_coord_x - 1][$j][0] = 2; //стена по высоте
				$map[$temp_coord_x + 1][$j][0] = 2; //стена по высоте
//---------------заполним комнаты их числовыми значениями-----------------------------
				for($n = 2; $n < $temp_coord_x - 1; $n++) //рассчет значений для 
					for($m = 2; $m < $Height; $m++) $map[$n][$m][0] = 10;//первой комнаты
					
				for($m = 2; $m < $Height; $m++)	$map[$temp_coord_x][$m][0] = 20; //рассчет значений для коридора
				
				for($n = $temp_coord_x + 2; $n < $Width; $n++) 
					for($m = 2; $m < $Height; $m++) $map[$n][$m][0] = 30; //рассчет значения для последней, третьей комнаты
				
			}
			$map[$temp_coord_x - 1][mt_rand(3,$Height - 1)][0] = 0; //создание проходов в комнаты
			$map[$temp_coord_x + 1][mt_rand(3,$Height - 1)][0] = 0; //создание проходов в комнаты
		}	
		else {
			for($i = 2; $i < $Width; $i++) {
				$map[$i][$temp_coord_y - 1][0] = 2; //стена по ширине
				$map[$i][$temp_coord_y + 1][0] = 2; //стена по ширине
//---------------заполним комнаты их числовыми значениями---------------------------------
				for($n = 2; $n < $Width; $n++) //рассчет значений для 
					for($m = 2; $m < $temp_coord_y - 1; $m++) $map[$n][$m][0] = 10;//первой комнаты
				
				for($n = 2; $n < $Width; $n++)	$map[$n][$temp_coord_y][0] = 20; //рассчет значений для коридора	
				
				for($n = 2; $n < $Width; $n++) //рассчет значений для 
					for($m = $temp_coord_y + 2; $m < $Height; $m++) $map[$n][$m][0] = 30;//первой комнаты
			}	
			$map[mt_rand(3,$Width - 1)][$temp_coord_y - 1][0] = 0; //создание проходов в комнаты
			$map[mt_rand(3,$Width - 1)][$temp_coord_y + 1][0] = 0; //создание проходов в комнаты

		}
		$_SESSION['x'] = $temp_coord_x; //определение координат героя
		$_SESSION['y'] = $temp_coord_y; //определение координат героя
//--------разбросаем предметы-----------------------------------------------------------------
		for($i = 2; $i < $Width; $i++) 
			for($j = 2; $j < $Height; $j++) {
				$map[$i][$j][1] = 0;	
				if(mt_rand(0,15) == 0) {
				if($map[$i][$j][0] == 0 OR $map[$i][$j][0] == 10 OR $map[$i][$j][0] == 20 OR $map[$i][$j][0] == 30)
				$map[$i][$j][1] = 3;
			}
		}
	
		$i = mt_rand(2,$Width-1);
		$j = mt_rand(2,$Height-1);
		$map[$i][$j][1] = 4;
//----------установим монстров--------------------------------------------------------------------
		$max = mt_rand(2, 6);
		//$max = 2;
		for($i = 1; $i <= $max; $i++) {
			$flag = false;
			while(!$flag) {
				$monster_position[$i][0] = mt_rand(2,$Width-1);
				$monster_position[$i][1] = mt_rand(2,$Height-1);
				if($map[$monster_position[$i][0]][$monster_position[$i][1]][0] != 2) $flag = true; ;	
			}
			$_SESSION['monster_position'][$i] = $monster_position[$i];			
		}
		$_SESSION['max'] = $max;
		/*$flag = false;
		while(!$flag) {
			$monster_position[$num][0] = mt_rand(2,$Width-1);
			$monster_position[$num][1] = mt_rand(2,$Height-1);
			if($map[$monster_position[$num][0]][$monster_position[$num][1]][0] != 2) $flag = true; ;	
		}
		$_SESSION['monster_position'][1] = $monster_position[$num];
		
		$flag = false;
		while(!$flag) {
			$monster_position[2][0] = mt_rand(2,$Width-1);
			$monster_position[2][1] = mt_rand(2,$Height-1);
			if($map[$monster_position[2][0]][$monster_position[2][1]][0] != 2) $flag = true; ;	
		}
		$_SESSION['monster_position'][2] = $monster_position[2];*/
		$_SESSION['map'] = $map;
		return $map;
	}	
?>