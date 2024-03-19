<?php
class MorphCardNumber{
	public $length=['first'=>'6','last'=>'4'];
	public $morphLabel = 'X';
	public $query = ['tablename'=>'tablename','columnName'=>'cardNumber'];
	public function  __construct(){
		$this->changeCardNumberDetails();
	}
	public function changeCardNumberDetails(){
		global $adb;
		$getNumber = [];
		$getCardNumberQuery = "select ".$this->query['columnName']." from ".$this->query['tableName']." ";
		$getCardNumberResult = $adb->pquery($getCardNumberQuery);
		if($adb->num_rows($getCardNumberResult) > 0){
			while($data = $adb->fetchByAssoc($getCardNumberResult)){
				$morphedContent =  $this->morphCardNumber(trim($data[$this->query['columnName']]));
				$getNumber[$data['id']] = $morphedContent;
				$updateResult = $this->updateMorphedContent($getNumber);
				if($updateResult['status'] == 'success'){
					echo "\n\n====>\nFor this id ".$data['id']."UpdateStatus : ".$updateResult['status']." for Ref row id :".$updateResult['rowCount'];
				}else{
					echo "\n\n====>\nFor this id ".$data['id']."UpdateStatus : ".$updateResult['status'];
				}
			}
		}else{
			echo "No record found";
		}
	}
	public function morphCardNumber($cardNumber){
		$numLength = strlen($cardNumber);
		$visibleNumber = array_sum(array_values($this->length));
		$morphedContentLength = abs($numLength - $visibleNumber);
		$firstDigit = substr($cardNumber,1,$this->length['first']);
		$lastDigit =  substr($cardNumber,-$this->length['last']);
		while($morphedContentLength--){
			$morphedText .= $this->morphLabel;
		}
		return $firstDigit.$morphedText.$lastDigit;
	}
	public function updateMorphedContent($content){
		global $adb;
		if($content){
			$prepareUpdateQuery = "update ".$this->query['tablename']." set ".$this->query['columnName']." = ? where id = ? ";
			$updateQueryResult = $adb->pquery($prepareUpdateQuery,[array_values($content),array_keys($content)]);
			if($adb->getAffectedRow($updateQueryResult) > 0 ){
				return ['status'=>'sucess','rowCount'=>$adb->getAffectedRow($updateQueryResult)];
			}else{
				return ['status'=>'fail'];
			}
		}
	}
}
new MorphCardNumber();
?>
