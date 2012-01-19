<HTML>
<HEAD>
<TITLE>Mine_pubmed</TITLE>
</HEAD>
<BODY>

<?php include "mine_pubmed.php"; ?>

<?php
$query_default = "autism AND dti";
$date_from_default = "2008";
$date_to_default = "2008";
if (isset($_GET['query'])) { $query = $_GET['query']; } else { $query = "*"; }
if (isset($_GET['query_old'])) { $query_old = $_GET['query_old']; } else { $query_old = $query_default; }
if (isset($_GET['date_from'])) { $date_from = $_GET['date_from']; } else { $date_from = $date_from_default; }
if (isset($_GET['date_from_old'])) { $date_from_old = $_GET['date_from_old']; } else { $date_from_old = $date_from_default; }
if (isset($_GET['date_to_old'])) { $date_to_old = $_GET['date_to_old']; } else { $date_to_old = $date_to_default; }
if (isset($_GET['review'])) { $review = $_GET['review']; } else { $review = "off"; }


#The query form
echo '<form action="' . basename($_SERVER['REQUEST_URI']) . '" method="get">';
	
	echo '<input type="hidden" name="date_from_old" value="' . $date_from . '" />';
	echo '';
	echo "<br />";
	if ($query == "*") {
		echo '<input name="query" size="100" value="' . $query_default . '" />';
	} else {
		echo '<input name="query" size="100" value="' . $query . '" />';
	}
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



if ($query != "*") {
	#Query has been provided
	
	#if ($review == "off") { $query = $query + " AND (NOT review)"; }
	#$query = $query + ' AND ("2011/12/01"[Date - Entrez] : "2011/12/31"[Date - Entrez]) ';
	
	$query = urlencode($query);
	echo "Query: " . $query;
	echo "<hr />";
	#list($PMID, $count) = get_PMIDs($query);
	echo $count . "<br>";
	
	list($authors, $title, $abstract, $date_pubmed, $date_rec, $date_acc, $journal, $affil) = get_summary($PMID[2]);
	
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