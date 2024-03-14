<?php
$a = "geeks for geeks";
$b = " ";

$as = strlen($a);
$bs = strlen($b);

for($i=$as;$i>=0;$i--){
	for($j=0;$j<=$bs;$j++){
		if($a[$i] != $b[$j]){
		  $b .= $a[$i];
		  $bs = strlen($b);
		}else{
			echo $a[$i];
		}
	}


}

?>
