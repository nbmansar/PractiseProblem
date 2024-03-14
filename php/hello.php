<?php
$a = "program";
for($i=0;$i<=strlen($a);$i++){
	for($j=0;$j<=strlen($a);$j++){
		if($i==$j){
		echo $a[$i];
		}else{
			echo " ";
		}
	}echo "\n";
}
?>
