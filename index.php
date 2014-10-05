<?php
	/** Import external php for classes **/
	require('classes.php');
	
	$sta = 0;
	/** Check if Query string is set **/
    if(isset($_GET['sterling'])) {
		$sta = $_GET['sterling'];
	}	
	/** Create new object from sterling exchange class**/
    $getcoins = new sterling_exchange($sta);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script src="page.js"></script>
<title>Coin Test</title>
</head>

<body>
<main>
    <div id="container">
    	<section>
        	<summary>
                <h1>Coin Test Application</h1>
                <p>Please type the amount on money in Pound Sterling you wish to get coin for.</p>
            </summary>
            
            <form method="get" action="index.php">
                <input type="text" name="sterling" />
                <input type="submit" value="Submit" />
            </form>
            <p>Examples of imput are: £16.50, £16.50p, 16.50p or 1650</p>
        </section>
        <section>
            <div id="results">
            
                <?php 
                    if($getcoins->getvalid()){
						        /** print full amount of input money in pounds as well as tabled data for coin and quantity **/
                        echo "<h2>Amount: " . $getcoins->getfull() . "</h2>\r" . $getcoins->tabledata();
                    }else{
					         	/** Error message if input is invalid **/
                        echo "<h2>Sorry invalid input, please try again</h2>";	
                    }
                ?>
                
            </div>
        </section>
    </div>
</main>
</body>
</html>
