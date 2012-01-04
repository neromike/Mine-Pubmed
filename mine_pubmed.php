<?php

function get_PMID ($query) {
	$query = 'autism';
	$base_URL = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi';

	$done = False;
	$ret_start = 0;
	$ret_max = 100;
	$count = -1;

	$PMID = array();

	while (! $done) {
		$query_ID = $base_URL . '?db=pubmed&term=' . $query . '&retmax=' . $ret_max . '&retStart=' . $ret_start;
		$xml = simplexml_load_file( $query_ID );
		if ($count == -1) {	#get the total number of matching publications
			$count = $xml->Count;
			$max = ceil($count / $ret_max);
			$ret_start = $max; #this skips to the last screen only
		}
		
		for ($i=0; $i<100; $i++) {
			$PMID[] = $xml->IdList->Id[$i];
		}
		#echo '<a href="' . $query_ID . '" target="blank">' . $query_ID . '</a><br />';
		#echo "Count : " . $count . "<br />";
		
		$ret_start = $ret_start + 1;
		if ($ret_start > $max) { $done = True; }
	}
	return $PMID;
}
?>