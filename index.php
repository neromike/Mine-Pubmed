<HTML>
<HEAD>
</HEAD>
<BODY>

<?php include "mine_pubmed.php"; ?>

<?php
$query="autism";
$PMID = get_PMIDs($query);
#for ($i=0; $i<count($PMID); $i++) {
#	echo $i . ": " . $PMID[$i] . "<br />";
#}

$this_PMID = $PMID[41];

list($authors, $title, $abstract, $date_rec, $date_acc, $journal) = get_summary($this_PMID);


echo "<Strong>authors</strong>:";
print_r($authors);
echo "<br />";

echo "<strong>title</strong>:" . $title . "<br>";

echo "<strong>abstract</strong>:" . $abstract . "<br>";

echo "<strong>date (received):</strong>";
print_r($date_rec);
echo "<br />";

echo "<strong>date (accepted):</strong>";
print_r($date_acc);
echo "<br />";

echo "<strong>journal:</strong>" . $journal . "<br />";


?>

</BODY>
</HTML>