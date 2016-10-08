<?php
function array_remove(&$array, $value) {
	return array_filter($array, function($a) use($value) {
        return $a !== $value;
    });
}

class parseImage {
	public $im;
	public $binaryMartix;
	public $assocNumber = [
		'44' => 0,
		'16' => 1,
		'38' => 2,
		'43' => 3,
		'30' => 4,
		'18' => 4,
		'21' => 4,
		'41' => 5,
		'45' => 6,
		'23' => 7,
		'24' => 7,
		'25' => 7,
		'53' => 8,
		'47' => 9,
	];
	public $resolve;

	function __construct($path) {
		$this->im = @imagecreatefrompng($path);
		
		if (!$this->im){ 
		   return false;
		}
		
		$this->binaryMartix = $this->imageToMatrix($this->im);  
		$explode = $this->explodeMatrix($this->binaryMartix);
		$this->resolve = '';
		
		foreach ($explode as $i=>$number) {
			if ($i == 1) $this->resolve .= ' (';
			if ($i == 4) $this->resolve .= ') ';
			if ($i == 7 || $i == 9) $this->resolve .= '-';
			$this->resolve .= $this->assocNumber[$number];
		} 
	}

	function explodeMatrix($binaryMartix) {
		$temp = array();
    
		// сложение столбцов для выявления интервалов
		$count_one = [];	
		for ($i = 0; $i < count($binaryMartix[0]); $i++) {
			$sum = 0;
			for ($j = 0; $j < count($binaryMartix); $j++) {
				$sum += $binaryMartix[$j][$i];
			}
			
			if ($sum == 1) { $count_one[] = $i; }
			
			if (count($count_one) == 4) { 
				$sum = 0; 
				
				foreach ($count_one as $ind) {
					$temp[$ind] = 0;
				}
				
				$count_one = [];
			}
			
			$temp[$i] = $sum;
		}
		
		// вычисление интервалов по полученной строке
		$start = false;
		$countPart = 0;
		$arrayInterval = array();
		foreach ($temp as $k => $v) {
			if ($v != 0 && !$start) {
				$arrayInterval[$countPart]['start'] = $k;
				$arrayInterval[$countPart]['patern'] = '';
				$start = true;
			}
			
			if ($start) {
				if ($v == 0) {
					$arrayInterval[$countPart]['end'] = $k - 1;
					$start = false;
					$countPart++;
				}
				else {
					$arrayInterval[$countPart]['patern'] .= $v.' ';
				}
			}
		}
		
		// вычисление интервалов с тире
		$error_indexes = [];
		foreach ($arrayInterval as $ind=>$interval) {	
			for ($i = $interval['start']; $i <= $interval['end']; $i++) {
				if ($temp[$i] != 1) break;
				if ($i == $interval['end']) $error_indexes[] = $ind;
			}
		}
		
		// удаляем тире
		for ($i = count($error_indexes) - 1; $i >= 0; $i--) {
			$arrayInterval = array_remove($arrayInterval, $arrayInterval[$error_indexes[$i]]);
		}
		
		// разделяем слитные цифры
		$arrInterval = [];
		foreach ($arrayInterval as $int) {
			$pat = explode(" ", substr($int['patern'], 0, strlen($int['patern']) - 1));
			
			if (count($pat) > 6) {
				$int['patern'] = '';
				$count_index = 5;
				foreach ($pat as $i=>$num) {
					$int['patern'] .= $num.' ';
	
					if ($i == $count_index) { 
						$int['end'] = $int['start'] + 5;
						$arrInterval[] = $int;
						$int['patern'] = '';
						$int['start'] = $int['start'] + $count_index + 1;
						$count_index += 6;
					}
				}
				
			}
			else { $arrInterval[] = $int; }
		}
		
				
	    // сложение всех единиц в полученных интервалах столбцов
	    foreach ($arrInterval as $interval) {
			$pat = explode(" ", substr($interval['patern'], 0, strlen($interval['patern']) - 1));
			$result[] = array_sum($pat);
	    }
		
// 		print_r($result);
	    return $result;
	}

	/**
	* Конвертация рисунка в бинарную матрицу
	* Все пиксели отличные от фона получают значение 1
	* @param imagecreatefrompng $im - картинка в формате PNG
	* @param bool $rotate - горизонтальная или вертикальная матрица 
	*/
	function imageToMatrix($im, $rotate = false) {
		$height = imagesy($im);
		$width = imagesx($im);
		
		if ($rotate) {
			$height = imagesx($im);
			$width = imagesy($im);
		}
		
		$background = 0;
		for ($i = 0; $i < $height; $i++) {
			for ($j = 0; $j < $width; $j++) {	
		        if ($rotate) {
					$rgb = imagecolorat($im, $i, $j);
		        } else {
					$rgb = imagecolorat($im, $j, $i);
		        }
		
		        //получаем индексы цвета RGB 
		        list($r, $g, $b) = array_values(imageColorsForIndex($im, $rgb));
		
		        //вычисляем индекс красного, для фона изображения
		        if ($i == 0 && $j == 0) {
			        $background = $r;
		        }
		        // если цвет пикселя не равен фоновому заполняем матрицу единицей
		        $binary[$i][$j] = ($r == $background) ? 0 : 1;
		        //echo $binary[$i][$j].' ';
			}
			//echo '<br/>';
		}
		
		return $binary;
	}
	
	/**
	* Выводит матрицу на экран
	* @param type $binaryMartix
	*/
	function printMatrix($binaryMartix) {
		for ($i = 0; $i < count($binaryMartix); $i++) {
			echo "<br/>";
			for ($j = 0; $j < count($binaryMartix[0]); $j++) {
				echo $binaryMartix[$i][$j]." ";
			}
		}
	}
}

  