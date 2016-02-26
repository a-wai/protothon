<?php

require_once $basedir."/include/Project.class.php";
require_once $basedir."/include/Option.class.php";

class ProjectView
{
  private $project;

  public function __construct($prj)
  {
    $this->project = $prj;
  }

  public function getScript($token, $base_url)
  {
    $script = "<script type=\"text/javascript\">\nvar currentPrice;\nvar theLink;\nvar projectName = \"".$this->project->title."\";\n\n";

    $idx = 0;
    foreach($this->project->options as $option)
    {
    	$idx++;

		// Create table for option cost
    	$script .= "var supp".$idx." = [";

    	$first = true;
    	foreach($option->values as $value)
    	{
    		if ($first)
    		{
    			$script .= $value;
    			$first = false;
    		}
    		else
    		{
    			$script .= ", $value";
    		}
    	}
    	$script .= "];\n";

		// Create table for option text
    	$script .= "var opt".$idx."texts = [";

     	$first = true;
    	foreach($option->texts as $text)
    	{
    		if ($first)
    		{
    			$script .= "\"$text\"";
    			$first = false;
    		}
    		else
    		{
    			$script .= ", \"$text\"";
    		}
    	}
    	$script .= "];\n\n";
    }

    $script .= "var basePrice = ".$this->project->proto_price.";\n";
    $script .= "var baseLink = \"#\";\n\n";

   /*
	 * Output Javascript function calculatePrice()
	 *
	 * This function retrieves the selected options and calculates
	 * the final prototype price using their costs.
	 * The real price is then updated for the user to see.
	 */

    $script .= "function calculatePrice() {\n";
    $script .= "	var price = basePrice;\n";

    // Create index variables
    for ($i = 1; $i <= $idx; $i++)
    {
    	$script .= "	var idx".$i." = document.getElementById(\"option".$i."\").selectedIndex;\n";
    }
    $script .= "\n";

    // Add supplements prices
    for ($i = 1; $i <= $idx; $i++)
    {
    	$script .= "	price += supp".$i."[idx".$i."];\n";
    }
    $script .= "\n	currentPrice = price;\n\n";

    $script .= "	document.getElementById(\"protoPrice\").innerHTML = price + \"€\";\n";
    $script .= "}\n\n";

	/*
	 * Output Javascript function updateHash()
	 *
	 * This function sends a POST request to /hash.php with project id
	 * and the new hash retrieved from the Payname payment
	 */

    $script .= "function updateHash(newHash) {\n";
    $script .= "	var request = \"id=".$this->project->id."&hash=\" + newHash;\n";
    $script .= "	var xhttp = new XMLHttpRequest();\n\n";
    $script .= "	xhttp.open(\"POST\", \"ajax/hash.php\", false);\n";
    $script .= "	xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");\n";
    $script .= "	xhttp.send(request);\n\n";
    $script .= "	if (xhttp.responseText == \"OK\\n\") return true;\n";
    $script .= "	else return false\n;";
    $script .= "}\n\n";

 	/*
 	 * Output Javascript function generatePayment()
	 *
	 * This function uses the Payname API to create a new unique payment with
	 *		- a title derived from the project name and selected options values
	 *		- the amount calculated from the prototype base price and options prices
	 *		- a return URL for the current project
	 * It then updates the payment hash and redirects the user to the payment page
	 */

    $script .= "function generatePayment() {\n";
    $script .= "	var request = \"token=".$token."&back_url=\";\n";
    $script .= "	var xhttp = new XMLHttpRequest();\n";
    $script .= "	var answer;\n";
    $script .= "	var paymentData;\n\n";
    $script .= "	request += encodeURIComponent(\"".$base_url."/project.php?id=\" + ".$this->project->id." + \"&return=0\");\n";
    $script .= "	request += \"&amount=\" + currentPrice;\n";

    $script .= "	request += \"&title=\" + encodeURIComponent(projectName";
    for ($i = 1; $i <= $idx; $i++)
    {
        $script .= " + \" \" + opt".$i."texts[document.getElementById(\"option".$i."\").selectedIndex]";
    }
    $script .= ");\n\n";

    $script .= "	xhttp.open(\"POST\", \"ajax/payment.php\", false);\n";
    $script .= "	xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");\n";
    $script .= "	xhttp.send(request);\n";
    $script .= "	answer = xhttp.responseText;\n\n";
    $script .= "	paymentData = JSON.parse(answer);\n";
    $script .= "	if (paymentData.success) {\n";
    $script .= "		updateHash(paymentData.hash);\n";
    $script .= "		document.getElementById(\"protoLink\").href = paymentData.link;\n";
    $script .= "		window.location.assign(paymentData.link);\n";
    $script .= "	}\n";
    $script .= "}\n\n";

    $script .= "function initOptions() {\n";
    for ($i = 1; $i <= $idx; $i++)
    {
    	$script .= "	document.getElementById(\"option".$i."\").selectedIndex = 0;\n";
    }
    $script .= "\n";
    $script .= "	calculatePrice();\n";
    $script .= "}\n";
    $script .= "</script>\n";

    return $script;
  }

	public function printOptions()
	{
		$idx = 0;
		foreach($this->project->options as $option)
		{
			$idx++;

			echo "									<div class=\"control-group\">\n";
			echo "										<div class=\"control-label\">".$option->label."</div>\n";
			echo "										<select id=\"option".$idx."\" onchange=\"calculatePrice()\">\n";

			foreach($option->values as $key => $value)
			{
				echo "											<option>".$key."</option>\n";
			}
			echo "										</select>\n";
			echo "									</div>\n\n";
		}
	}

	public function printProto()
	{
		if ($this->project->proto_sold)
		{
			echo "									<p>Le prototype n'est malheureusement plus disponible pour ce projet.</p><br>\n";
			echo "									<div class=\"row-fluid\">\n";
			echo "										<button class=\"span5 offset7 btn btn-block disabled\">Vendu</button>\n";
			echo "									</div>\n";
		}
		else
		{
			$this->printOptions();

			echo "									<div class=\"row-fluid control-group\">\n";
			echo "										<div class=\"control-label\" style=\"font-size:18px; margin-top:0\">Prix</div>\n";
			echo "										<div class=\"price-label\"><strong id=\"protoPrice\">€</strong></div>\n";
			echo "									</div>\n";
			echo "									<div class=\"row-fluid\">\n";
			echo "										<a class=\"span5 offset7 btn btn-info btn-block\" id=\"protoLink\" href=\"#\" onclick=\"generatePayment()\">Acheter</a>\n";
			echo "									</div>\n";
		}
	}

	public function optionsDesc()
	{
		$first = true;

		foreach($this->project->options as $option)
		{
			if ($first)
			{
				echo "					<br><div class=\"heading\"><h5>Détail des options</h5></div>\n";
				$first = false;
			}

			echo "					<div class=\"reward\" title=\"".$option->label."\">\n";
			echo $option->desc;
			echo "					</div>\n\n";
		}
	}

	public function printSummary()
	{
		echo $this->project->summary;
	}

	public function printDesc()
	{
		echo $this->project->long_desc;
	}

	public function printSpecs()
	{
		echo $this->project->specs;
	}
}

?>
