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

$this_PMID = $PMID[2];

get_summary($this_PMID);


?>

</BODY>
</HTML>