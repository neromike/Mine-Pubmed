<?php
function get_PMIDs ($query, $return_count=false) {
	#input: a text-string that will be used for the PubMed query
	#output: returns an array with all the PMIDs
	#echo "<strong>Query</strong>: " . $query . "<br />";
	#second parameter is an optional boolean. If true, the function will just return the total count.
	$base_URL = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi';
	$done = False;
	$ret_start = 0;
	$ret_max = 100;
	$count = -1;
	$PMID = array();
	$debug = False;
	while (! $done) {
		$query_ID = $base_URL . '?db=pubmed&term=' . $query . '&retmax=' . $ret_max . '&retStart=' . $ret_start;
		$xml = simplexml_load_file( $query_ID );
		if ($count == -1) {	#get the total number of matching publications
			$count = $xml->Count;
			$max = ceil($count / $ret_max);
			#echo "<Strong>Total number of matching publications</strong>: " . $count . "<br />";
			#$ret_start = $max; #this skips to the last screen only, used for debugging
			if ($return_count) { return $count; }
		}
		
		#figure out how many results have been returned
		$max_loop = $count - ($ret_start * $ret_max);
		if ($max_loop > $ret_max) { $max_loop = $ret_max; }	
		
		#debug messages
		if ($debug) {
			echo "--------------<br>";
			echo "ret_start : " . $ret_start . "<br />";
			echo "ret_max : " . $ret_max . "<br />";
			echo "max : " . $max . "<br />";
			echo "count : " . $count . "<br />";
			echo "max_loop : " . $max_loop . "<br />";
		}
		
		for ($i=0; $i<$max_loop; $i++) {
			$PMID[] = $xml->IdList->Id[$i];
		}
		$ret_start = $ret_start + 1;
		if ($ret_start == $max) { $done = True; }
	}
	if ($debug) { echo "--------------<br><br>"; }
	return array($PMID, $count);
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
	if ($start) {
		$len = strpos(substr($info, $start), "}");
		$authors = explode(",", substr($info, $start, $len));
	} else {
		$authors = "Not provided";
	}
	
	#Get the title
	$start = strpos($info, "cit {");
	if ($start) {
		$start = $start + strpos( substr($info, $start), '"') + 1;
		$len = strpos(substr($info, $start), '"');
		$title = substr($info, $start, $len);
	} else {
		$title = "Not provided";
	}

	#Get the abstract
	$start = strpos($info, "abstract");
	if ($start) {
		$start = $start + strpos( substr($info, $start), '"') + 1;
		$len = strpos(substr($info, $start), '"');
		$abstract = substr($info, $start, $len);
	} else {
		$abstract = "Not provided";
	}
	
	#Get the pubmed date
	$start = strpos($info, "pubstatus pubmed");
	if ($start) {
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
		$date_pubmed = array($year, $month, $day);
	} else {
		$date_pubmed = "";
	}
	
	#Get the received date
	$start = strpos($info, "pubstatus received");
	if ($start) {
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
	} else {
		$date_rec = "";
	}
	
	#Get the accepted date
	$start = strpos($info, "pubstatus accepted");
	if ($start) {
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
	} else {
		$date_acc = "";
	}
	
	#Get the journal
	$start = strpos($info, "journal");
	if ($start) {
		$start = $start + strpos( substr($info, $start), "name");
		$start = $start + strpos( substr($info, $start), '"') + 1;
		$len = strpos( substr($info, $start), '"');
		$journal = substr($info, $start, $len);
	} else {
		$journal = "Not provided";
	}
	
	#Get the affiliation
	$start = strpos($info, "affil str");
	if ($start) {
		$start = $start + strpos( substr($info, $start), '"') + 1;
		$len = strpos( substr($info, $start), '"');
		$affil = substr($info, $start, $len);
	} else {
		$affil = "Not provided";
	}
	
	return array($authors, $title, $abstract, $date_pubmed, $date_rec, $date_acc, $journal, $affil);
}
?>