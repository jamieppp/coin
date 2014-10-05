<?php
/**
=========================== How to Use ===============================================

*Returns the least amount of currency coin change for Sterling.
*Create instance of sterling_exchange and pass amount of credit as the argument.
*To get returned tabled data please invoke the tabledata() function.
*To get returned array data please invoke the arraydata() function.
*To return the full amount of money invoke the getfull() function.
*To check if the user input was valid please invoke the getvalid() function.

======================================================================================
**/


/** STERLING EXCHANGE CLASS **/

class sterling_exchange {
	
	/** Class variable data **/
	private $userinput;
	private $conv;
	private $isvalid;
	private $dec;
	
	public function __construct($money){
		/** set user input and invoke parseamount function **/
		$this->userinput = $money;
		$this->parseamount();
		
	}
	private function parseamount(){
		
		/** String parse and manipulation **/
		
		$numstr = $this->userinput;
		$newst = "";
		$decimal;
		$inpence;
		if(substr_count($numstr, "£") == 1 && strpos($numstr, "£") === 0){
			$newst = trim($numstr, "£");
			if(is_numeric($newst)){
				if(substr_count($newst, ".")){
					$decimal = floatval($newst);
				}else{
					$newst = $newst . ".00";	
					$decimal = floatval($newst);
				}
				$this->isvalid = true;
			}elseif(strpos($newst, "p") == strlen($newst)-1){
				$newst = trim($newst, "p");
				if(is_numeric($newst)){
					if(substr_count($newst, ".")){
						$decimal = floatval($newst);
						$this->isvalid = true;						
					}else{
						$this->isvalid = false;
					}
				}else {
					$this->isvalid = false;	
				}
			}
		}else{
			$newst = $numstr;
			if(is_numeric($newst)){
				if(substr_count($newst, ".")){
					$decimal = floatval($newst);
				}else{
					$decimal = floatval($newst)/100;
				}
				$this->isvalid = true;
			}else{	
				if(strpos($newst, "p") == strlen($newst)-1){
					$newst = trim($newst, "p");
					if(is_numeric($newst)){
						if(substr_count($newst, ".")){
							$decimal = floatval($newst);		
						}else{
							$decimal = floatval($newst)/100;
						}
						$this->isvalid = true;
					}else{
						$this->isvalid = false;
					}
				}
			}
		}
		/** Check if the Parsed string is valid input **/
		if($this->isvalid == false){
			$decimal = 00.00;
		}
		/** Send pass amount in pence to return change **/
		$this->dec = $decimal;
		$inpence = intval(strval($decimal*100));
		$this->conv = new returnchange($inpence);
	}	
	/** Return tdata in the form of an HTML table **/
	public function tabledata(){
		$data = $this->conv->getarraydata();
		$r = "<table><tr><td>Coin</td><td>Quantity</td></tr>";
		foreach($data as $value){
			if($value[1] > 0){
				$r = $r . "<tr><td>" . $value[0] . "</td><td>" . $value[1] . "</td></tr>";
			}
		}
		$r = $r . "</table>";
		return $r;
	}
	/** Return array data **/
	public function arraydata(){
		return $this->conv->getarraydata();	
	}
	/** Get full amount of money in pounds **/
	public function getfull(){
		$amt = $this->dec;
		$range;
		if(substr_count($amt, ".")){
			$range = (strlen($amt)-1) - strpos($amt, ".");
			if($range < 2){
				$amt = $amt . "0";
			}
		}
		return "£" . $amt;
	}
	/** Check if input was valid **/
	public function getvalid(){
		return $this->isvalid;	
	}
}

/** RETURN CHANGE CLASS **/
/** Amount of money to get exchanged for coin must be passed as pence **/

class returnchange{
	/** set object variables **/
	private $amount;
	private $changeleft;
	private $coinage;	
	/** Constructor function, declare currency amount and coin instances and invoke change function **/
	public function __construct($cash){		
		$this->amount = intval($cash);
		$this->changeleft = $this->amount;
		/** Insert largest coin face value at the beginning of the array working down to the smallest face value **/
		$this->coinage = array(new coins("£2"), new coins("£1"), new coins("50p"), new coins("20p"), new coins("10p"), new coins("5p"), new coins("2p"), new coins("1p"));
		$this->change();
	}
	/** calulate the shortest amount of coin change from user input **/
	private function change(){
		$k = count($this->coinage);
		$remain;
		$total;
		$amnt;
		for($a = 0; $a < $k; $a++){
			/** calculate the amount per coin that can fit into the remaining currency change **/		
			$remain = $this->changeleft % $this->coinage[$a]->getvalue();
			$total = $this->changeleft - $remain;
			$amnt = $total / $this->coinage[$a]->getvalue();
			/** add the number of new coins and set new remaining change **/
			$this->coinage[$a]->add($amnt);
			$this->changeleft = $remain;
		}	
	}
	public function getarraydata(){
		/** return array of data **/
		$k = count($this->coinage);
		$list = range(0, $k-1);
		for($n = 0; $n < $k; $n++){	
				$list[$n] = array($this->coinage[$n]->getname(), $this->coinage[$n]->getquantity());
		}	
		return $list;
	}
}

/** COINS CLASS **/
/** Class for type of coin and quantity **/

class coins{
	/** set object variables **/
	private $_quantity;
	private $_name;
	private $_value;
	/** constructor function to set type of coin and value **/
	public function __construct($type) {
		
		$this->_quantity = 0;
		$this->_name = $type;
		
		if($type == "£2"){
			$this->_value = 200;
		}elseif($type == "£1"){
			$this->_value = 100;
		}elseif($type == "50p"){
			$this->_value = 50;
		}elseif($type == "20p"){
			$this->_value = 20;
		}elseif($type == "10p"){
			$this->_value = 10;
		}elseif($type == "5p"){
			$this->_value = 5;
		}elseif($type == "2p"){
			$this->_value = 2;
		}elseif($type == "1p"){
			$this->_value = 1;
		}			
	}
	/** Return coin data **/
	public function getname(){
		return $this->_name;	
	}
	public function getquantity(){
		return $this->_quantity;	
	}
	public function getvalue(){
		return $this->_value;	
	}
	public function add($num){
		$this->_quantity += $num;
	}
}
?>
