<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Mine_pubmed</title>
<link rel="stylesheet" href="jquery-ui-1.9.2.custom.min.css" />
<script type="text/javascript" src="jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="jquery-ui-1.9.2.custom.min.js"></script>
</head>
<script>$(function() { $( ".datepicker" ).datepicker( {dateFormat: 'yy/mm/dd'} ); });</script>
<script>
function restrict_date(cb) {
	if (cb.checked) {
		document.getElementById("restrict_date_range").style.display = "inherit";
	} else {
		document.getElementById("restrict_date_range").style.display = "none";
	}
}
</script>
<body>

<?php include "mine_pubmed.php"; ?>

<?php
$query_default = "(autism OR autistic) AND dti";
$date_from_default = "2006/01/01";
$date_from_check_default = "";
$date_to_check_default = "";
$date_to_default = "2012/12/31";
if (isset($_GET['query'])) { $query = $_GET['query']; } else { $query = "*"; }
if (isset($_GET['date_restrict'])) { $date_restrict = $_GET['date_restrict']; } else { $date_restrict = $date_from_check_default; }
	if ($date_restrict != "on") { echo "<style>#restrict_date_range { display:none; }</style>"; }
if (isset($_GET['date_from'])) { $date_from = $_GET['date_from']; } else { $date_from = $date_from_default; }
if (isset($_GET['date_to'])) { $date_to = $_GET['date_to']; } else { $date_to = $date_to_default; }
#if (isset($_GET['review'])) { $review = $_GET['review']; } else { $review = "off"; }


#The query form
echo '<form action="index.php" method="get">';
	
	#query
	echo '<input name="query" size="100" value="';
	if ($query == "*") { echo $query_default; } else { echo $query; }
	echo '" />';
	echo '<table>';
	echo '<tr>';
	
	#restricted date
	echo '<td>Restrict date?<input type="checkbox" name="date_restrict"';
	if ($date_restrict == "on") { echo " checked='yes' "; }
	echo ' onClick="restrict_date(this)" /></td>';
	echo '<td id="restrict_date_range">From <input class="datepicker" name="date_from" value="' . $date_from . '"/> to <input class="datepicker" name="date_to" value="' . $date_to . '"/></td>';
	echo "</tr>";
	
	echo '</table>';
	
	#reviews
	#echo '<table><tr><td><input type="checkbox" name="review" /></td><td>Check this to include reviews</td></tr></table>';
	
	#submit
	echo '<input type="submit" value="Query" />';
	
echo '</form>';	



if ($query != "*") { #Query has been provided
	#if ($review == "off") { $query = $query . " AND (NOT review)"; }

	
	if ($date_restrict == "on") {
		$query = $query . ' AND ("' . $date_from . '"[Date - Entrez] : "' . $date_to . '"[Date - Entrez]) ';
	}
	
	echo "Query: " . $query;
	echo "<br />";
	$query = urlencode($query);
	echo "Query: " . $query;
	echo "<hr />";
	list($PMID, $count) = get_PMIDs($query);
	echo $count . "<br>";
	
	if (1==1) {
		list($authors, $title, $abstract, $date_pubmed, $date_rec, $date_acc, $journal, $affil) = get_summary($PMID[3]);
		echo "<strong>authors</strong>:";
		print_r($authors);
		echo "<br />";
		echo "<strong>title</strong>:" . $title . "<br>";
		echo "<strong>abstract</strong>:" . $abstract . "<br>";
		echo "<strong>date (pubmed):</strong>";
		print_r($date_pubmed);
		echo "<br />";
		echo "<strong>date (received):</strong>";
		print_r($date_rec);
		echo "<br />";
		echo "<strong>date (accepted):</strong>";
		print_r($date_acc);
		echo "<br />";
		echo "<strong>journal:</strong>" . $journal . "<br />";
		echo "<strong>affiliation:</strong>" . $affil . "<br />";
		echo "-------------------------------<br />";
	}
}

?>

</body>
</html>