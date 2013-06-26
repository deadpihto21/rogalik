<?php
	Class Monster {
	//свойства класса
		var $hp;
		var $x;
		var $y;
		var $coord;
		var $damage;
	//методы
		function monster() {   //конструктор класса
			$this->hp = 3;  //запас очков жизни
			$this->x = 3;
			$this->y = 3;
			$this->coord = array(1,1);
			$this->damage = 1; //наносимый монстром урон
		} 
		function getCoord() {  //метод получения координат монстра
			return $this -> coord;
		}
		function getDamage() {  //метод получения значения урона монстра
			return $this -> damage;
		}		
		function setCoord($coord) {  //метод установки координат монстра
			$this -> coord = $coord;
			$x = $coord[0];
			$y = $coord[1];
		}
		function getHp() {  //метод получения очков жизни
			return $this -> hp;
		}
		function setHp($hp) {  //метод получения очков жизни
			$this -> hp = $hp;
		}
	}
	Class Player {
	//свойства класса
		var $hp;
		var $x;
		var $y;
		var $coord;
		var $damage;
	//методы
		function player() {   //конструктор класса
			$this->hp = 10;  //запас очков жизни
			$this->x = 1; //начальная координата по х
			$this->y = 1; //начальная координата по у
			$this->coord = array(1,1); //массив координат игрока
			$this->damage = 1; //наносимый игроком урон
		} 
		function getCoord() {  //метод получения координат игрока
			return $this -> coord;
		}
		function setCoord($coord) {  //метод установки координат игрока
			$this -> coord = $coord;
			$x = $coord[0];
			$y = $coord[1];
		}		
		function getDamage() {  //метод получения значения урона игрока
			return $this -> damage;
		}
		function setDamage($damage) {  //метод установки очков урона
			$this -> damage = $damage;
		}		
		function getHp() {  //метод получения очков жизни
			return $this -> hp;
		}
		function setHp($hp) {  //метод установки очков жизни
			$this -> hp = $hp;
		}		
	}
	Class Item {
	//свойства класса
		var $hp;
		var $x;
		var $y;
		var $coord;
		var $damage;
	//методы
		/*function player() {   //конструктор класса
			$this->hp = 10;  //запас очков жизни
			$this->x = 1; //начальная координата по х
			$this->y = 1; //начальная координата по у
			$this->coord = array(1,1); //массив координат игрока
			$this->damage = 1; //наносимый игроком урон
		} 
		function getCoord() {  //метод получения координат игрока
			return $this -> coord;
		}
		function setCoord($coord) {  //метод установки координат игрока
			$this -> coord = $coord;
			$x = $coord[0];
			$y = $coord[1];
		}		
		function getDamage() {  //метод получения значения урона игрока
			return $this -> damage;
		}
		function setDamage($damage) {  //метод установки очков урона
			$this -> damage = $damage;
		}		
		function getHp() {  //метод получения очков жизни
			return $this -> hp;
		}
		function setHp($hp) {  //метод установки очков жизни
			$this -> hp = $hp;
		}		*/
	}	
?>