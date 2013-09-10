<?php
    Class Entity{
    		var $hp;
    		var $x;
    		var $y;
    		var $coord;
    		var $damage;
            function getCoord() {  //����� ��������� ��������� �������
                return $this -> coord;
            }
            function getDamage() {  //����� ��������� �������� ����� �������
                return $this -> damage;
            }
            function setCoord($coord) {  //����� ��������� ��������� �������
                $this -> coord = $coord;
                $x = $coord[0];
                $y = $coord[1];
            }
            function getHp() {  //����� ��������� ����� �����
                return $this -> hp;
            }
            function setHp($hp) {  //����� ��������� ����� �����
                $this -> hp = $hp;
            }
            function setDamage($damage) {  //����� ��������� ����� �����
                $this -> damage = $damage;
            }
    }
    class Monster extends Entity{
		function monster() {   //����������� ������
			$this->hp = 3;  //����� ����� �����
			$this->x = 3;
			$this->y = 3;
			$this->coord = array(1,1);
			$this->damage = 1; //��������� �������� ����
	    }
	}
    class Player extends Entity{
		function player() {   //����������� ������
			$this->hp = 10;  //����� ����� �����
			$this->x = 1; //��������� ���������� �� �
			$this->y = 1; //��������� ���������� �� �
			$this->coord = array(1,1); //������ ��������� ������
			$this->damage = 1; //��������� ������� ����
		}
	}
/*	Class Item {
	//�������� ������
		var $hp;
		var $x;
		var $y;
		var $coord;
		var $damage;
	//������
		/*function player() {   //����������� ������
			$this->hp = 10;  //����� ����� �����
			$this->x = 1; //��������� ���������� �� �
			$this->y = 1; //��������� ���������� �� �
			$this->coord = array(1,1); //������ ��������� ������
			$this->damage = 1; //��������� ������� ����
		} 
		function getCoord() {  //����� ��������� ��������� ������
			return $this -> coord;
		}
		function setCoord($coord) {  //����� ��������� ��������� ������
			$this -> coord = $coord;
			$x = $coord[0];
			$y = $coord[1];
		}		
		function getDamage() {  //����� ��������� �������� ����� ������
			return $this -> damage;
		}
		function setDamage($damage) {  //����� ��������� ����� �����
			$this -> damage = $damage;
		}		
		function getHp() {  //����� ��������� ����� �����
			return $this -> hp;
		}
		function setHp($hp) {  //����� ��������� ����� �����
			$this -> hp = $hp;
		}		*/
	/*}*/	
?>