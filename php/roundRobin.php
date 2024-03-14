<?php
$userList = ['A','B','C','D','E','F','G','K'];
$midCount = 60;
$assignedUser=[];
	$maxCount = 0;
for($i=0;$i<=60;$i++){
	if($maxCount == count($userList)){
		$maxCount=0;
	}
		$assignedUser[$i] = $userList[$maxCount];
		$maxCount++;
	
}
print_R($assignedUser);
?>
