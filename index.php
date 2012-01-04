<HTML>
<HEAD>
</HEAD>
<BODY>

<?php include "mine_pubmed.php"; ?>

<?php
$query="autism";
$PMID = get_PMID($query);

for ($i=0; $i<count($PMID); $i++) {
	echo $i . ": " . $PMID[$i] . "<br />";
}
?>

</BODY>
</HTML>