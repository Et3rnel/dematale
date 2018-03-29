<?php
function NegativZero($var)		
{
	if ($var <= 0){
	$var2 = 0;
	}else{
	$var2 = $var;
	}
	return $var2;
}

function DegTruncation($var)		
{
	$y = 0;
	if(($var/10)>10) 
	{
		$y = 1;
		if(($var/100)>10) 
		{
			$y = 2;
			if(($var/1000)>10) 
			{
				$y = 3;
				if(($var/10000)>10) 
				{
					$y = 4;
					if(($var/100000)>10) 
					{
						$y = 5;
						if(($var/1000000)>10) 
						{
							$y = 6;
						}
					}
				}
			}
		}
	}
	if($y == 0) $result = $var;
	if($y>0 && $y<3) $result = round($var, -1);
	if($y>2 && $y<5) $result = round($var, -2);
	if($y >= 5) $result = round($var, -3);
	return $result;
}
?>

