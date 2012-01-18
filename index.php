<HTML>
<HEAD>
<TITLE>Mine_pubmed</TITLE>
</HEAD>
<BODY>

<?php include "mine_pubmed.php"; ?>

<?php
$query_default = "(autism OR asd) AND EEG AND (NOT review)";
$date_from_default = "2008";
$date_to_default = "2008";
if (isset($_GET['query'])) { $query = $_GET['query']; } else { $query = "*"; }
if (isset($_GET['query_old'])) { $query_old = $_GET['query_old']; } else { $query_old = $query_default; }
if (isset($_GET['date_from'])) { $date_from = $_GET['date_from']; } else { $date_from = $date_from_default; }
if (isset($_GET['date_from_old'])) { $date_from_old = $_GET['date_from_old']; } else { $date_from_old = $date_from_default; }
if (isset($_GET['date_to_old'])) { $date_to_old = $_GET['date_to_old']; } else { $date_to_old = $date_to_default; }
if (isset($_GET['review'])) { $review = $_GET['review']; } else { $review = "off"; }

if ($query == "*") {
	#No query has been provided
	echo '<form action="index.php" method="get">';
		echo '';
		echo "<br />";
		echo '<input name="query" size="100" value="' . $query_old . '" />';
		echo '<br />';
		echo '<table>';
		echo '<tr>';
		echo '<td><input type="checkbox" name="date_from_check" /></td>';
		echo '<td>Date from</td><td align="right">January 1st,</td><td><input type="date" name="date_from" value="' . $date_from_old . '"/></td>';
		echo "</tr><tr>";
		echo '<td><input type="checkbox" name="date_to_check" /></td>';
		echo '<td>Date to</td><td align="right">December 31st,</td><td><input type="date" name="date_to" value="' . $date_to_old . '"/></td>';
		echo '</table>';
		echo '<table><tr><td><input type="checkbox" name="review" /></td><td>Check this to include reviews</td></tr></table>';
		echo '<input type="submit" value="Query" />';
	echo '</form>';	
} else {
	#Query has been provided
	echo '<form action="index.php" method="get">';
	echo '<input type="hidden" name="query_old" value="' . $query . '" />';
	echo '<input type="hidden" name="date_from_old" value="' . $date_from . '" />';
	echo '<input type="submit" value="New Query" />';
	echo '</form>';
	
	if ($review == "off") { $query = $query + " AND (NOT review)"; }
	echo "Query: " . $query;
	
	list($PMID, $count) = get_PMIDs($query);

	echo $count . "<br>";
	
	list($authors, $title, $abstract, $date_pubmed, $date_rec, $date_acc, $journal, $affil) = get_summary($PMID[11]);
	
	echo "<Strong>authors</strong>:";
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

?>

</BODY>
</HTML>