<?php
function get_PMIDs ($query) {
	#input: a text-string that will be used for the PubMed query
	#output: returns an array with all the PMIDs
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
		#print_r($xml);
		$ret_start = $ret_start + 1;
		if ($ret_start > $max) { $done = True; }
	}
	return $PMID;
}
function get_summary ($this_PMID) {
	#input: a single PMID
	#output: an array of the pertinent information	
	$base_URL = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi';
	$query_ID = $base_URL . '?db=pubmed&id=' . $this_PMID;
	#echo '<a href="' . $query_ID . '" target="blank">' . $query_ID . '</a><br />';
	$xml = simplexml_load_file( $query_ID );
	$info = $xml->body->pre;
	$info = str_replace("\t","", $info);
	#echo $info . "<br><br>";
	
	#Get author names
	$start = strpos($info, "names ml") + 21;
	$len = strpos(substr($info, $start), "}");
	$authors = explode(",", substr($info, $start, $len));
	
	#Get the title
	$start = strpos($info, "cit { title { name");
	$start = strpos( substr($info, $start), '"') + 1;
	$len = strpos(substr($info, $start), '"');
	#echo "start : " . $start . "<br>";
	#echo "len : " . $len . "<br>";
	$title = substr($info, $start, $len);

	#Get the abstract
	$start = strpos($info, "abstract");
	$start = $start + strpos( substr($info, $start), '"') + 1;
	$len = strpos(substr($info, $start), '"');
	#echo "start : " . $start . "<br>";
	#echo "len : " . $len . "<br>";
	$abstract = substr($info, $start, $len);
	
	#Get the received date
	$start = strpos($info, "pubstatus received");
		#get the year
		$start = $start + strpos( substr($info, $start), 'year') + 5;
		$len = strpos(substr($info, $start), ",");
		$year = substr($info, $start, $len);
		#get the month
		$start = $start + strpos( substr($info, $start), 'month') + 6;
		$len = strpos(substr($info, $start), ",");
		$month = substr($info, $start, $len);
		#get the day
		$start = $start + strpos( substr($info, $start), 'day') + 4;
		$len = strpos(substr($info, $start), "}");
		$day = substr($info, $start, $len);
	$date_rec = array($year, $month, $day);
	
	#Get the received date
	$start = strpos($info, "pubstatus accepted");
		#get the year
		$start = $start + strpos( substr($info, $start), 'year') + 5;
		$len = strpos(substr($info, $start), ",");
		$year = substr($info, $start, $len);
		#get the month
		$start = $start + strpos( substr($info, $start), 'month') + 6;
		$len = strpos(substr($info, $start), ",");
		$month = substr($info, $start, $len);
		#get the day
		$start = $start + strpos( substr($info, $start), 'day') + 4;
		$len = strpos(substr($info, $start), "}");
		$day = substr($info, $start, $len);
	$date_acc = array($year, $month, $day);
	
	#Get the journal
	$start = strpos($info, "journal");
	$start = $start + strpos( substr($info, $start), "name");
	$start = $start + strpos( substr($info, $start), '"') + 1;
	$len = strpos( substr($info, $start), '"');
	$journal = substr($info, $start, $len);
	
	return array($authors, $title, $abstract, $date_rec, $date_acc, $journal);
	
}
?>