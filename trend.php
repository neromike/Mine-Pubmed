<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Mine_pubmed</title>
</head>
<body>

<?php include "mine_pubmed.php"; ?>

<?php
$query_default = "(autism OR autistic) AND dti";
$date_from_default = "2006";
$date_from_check_default = "";
$date_to_check_default = "";
$date_to_default = "2012";
if (isset($_GET['query'])) { $query = $_GET['query']; } else { $query = "*"; }
if (isset($_GET['date_from'])) { $date_from = $_GET['date_from']; } else { $date_from = $date_from_default; }
if (isset($_GET['date_to'])) { $date_to = $_GET['date_to']; } else { $date_to = $date_to_default; }
#if (isset($_GET['review'])) { $review = $_GET['review']; } else { $review = "off"; }


#The query form
echo '<form action="trend.php" method="get">';

	#query
	echo '<input name="query" size="100" value="';
	if ($query == "*") { echo $query_default; } else { echo $query; }
	echo '" />';
	
	echo "<br />";
	
	#date from
	echo 'From January 1st,&nbsp;';
	echo '<select name="date_from" value="' . $date_from . '">';
	for ($i=1966; $i<=2012; $i++) {
		echo '<option value="' . $i . '"';
		if ($i == $date_from) { echo " selected"; }
		echo '>' . $i . '</option>';
	}
	echo '</select>';
	
	#date to
	echo '&nbsp; to December 31st,&nbsp;';
	echo '<select name="date_to" value="' . $date_to . '">';
	for ($i=1966; $i<=2012; $i++) {
		echo '<option value="' . $i . '"';
		if ($i == $date_to) { echo " selected"; }
		echo '>' . $i . '</option>';
	}
	echo '</select>';
	
	#submit
	echo '<br />';
	echo '<input type="submit" value="Query" />';
	
echo '</form>';	



if ($query != "*") { #Query has been provided
	$base_query = $query;
	
	echo "<hr />";
	
	echo "<table border=1px>";
	echo "<tr>";
	echo "<td align=center><strong>Year</strong></td>";
	echo "<td align=center><strong># of publications</strong></td>";
	echo "<td></td>";
	echo "</tr>";
	for ($i=$date_from; $i<=$date_to; $i++) {
		$query = $base_query . ' AND ("' . $i . '/01/01"[Date - Entrez] : "' . $i . '/12/31"[Date - Entrez]) ';
		$query = urlencode($query);
		echo "<tr>";
		echo "<td align=center>" . $i . "</td>";
		echo "<td align=center>" . $count = get_PMIDs($query, true) . "</td>";
		echo "<td><a href='http://www.ncbi.nlm.nih.gov/pubmed?term=" . $query . "' target='blank'>query</a>";
		echo "</tr>";
	}
	$query = $base_query . ' AND ("' . $date_from . '/01/01"[Date - Entrez] : "' . $date_to . '/12/31"[Date - Entrez]) ';
	$query = urlencode($query);
	$count = get_PMIDs($query, true);
	echo "<tr>";
	echo "<td align=center><strong>TOTAL</strong></td>";
	echo "<td align=center><strong>" . $count . "</strong></td>";
	echo "<td><a href='http://www.ncbi.nlm.nih.gov/pubmed?term=" . $query . "' target='blank'>Original query</a></td>";
	echo "</tr>";
	echo "</table>";
}
?>

</body>
</html>