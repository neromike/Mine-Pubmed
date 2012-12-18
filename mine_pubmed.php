<?php
function get_PMIDs ($query, $return_count=false) {
	#input: a text-string that will be used for the PubMed query
	#output: returns an array with all the PMIDs
	#echo "<strong>Query</strong>: " . $query . "<br />";
	#second parameter is an optional boolean. If true, the function will just return the total count.
	$base_URL = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed';
	$done = False;
	$ret_start = 0;
	$ret_max = 100;
	$count = -1;
	$PMID = array();
	$debug = False;
	while (! $done) {
		$query_ID = $base_URL . '&term=' . $query . '&retmax=' . $ret_max . '&retStart=' . $ret_start;
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
	$debug = True;
	$base_URL = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi';
	$query_ID = $base_URL . '?db=pubmed&id=' . $this_PMID . "&retmode=xml";
	if ($debug) {
		echo '<a href="' . $query_ID . '" target="blank">' . $query_ID . '</a><br />';
	}
	$xml = simplexml_load_file( $query_ID );
	
	#Get author names
	$authors = "";
	foreach ($xml->PubmedArticle->MedlineCitation->Article->AuthorList->children() as $child) {
		$authors = $authors . $child->LastName . " " . $child->Initials . ", ";
	}
	
	#Get title
	$title = $xml->PubmedArticle->MedlineCitation->Article->ArticleTitle;
	
	#Get abstract
	$abstract = $xml->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText;
	
	#Get dates
	$date_pubmed = $xml->PubmedArticle->PubmedData->History->PubMedPubDate;
	$date_rec = "";
	$date_pubmed = "";
	$date_acc = "";
	foreach ($xml->PubmedArticle->PubmedData->History->children() as $child) {
		if ($child['PubStatus'] == "received") {
			$date_rec = array( $child->Year, $child->Month, $child->Day );
		} elseif ($child['PubStatus'] == "pubmed") {
			$date_pubmed = array( $child->Year, $child->Month, $child->Day );
		} elseif ($child['PubStatus'] == "accepted") {
			$date_acc = array( $child->Year, $child->Month, $child->Day );
		}
	}
	
	#Get journal
	$journal = $xml->PubmedArticle->MedlineCitation->Article->Journal->Title;
	
	#Get the affiliation
	$affil = $xml->PubmedArticle->MedlineCitation->Article->Affiliation;
	
	return array($authors, $title, $abstract, $date_pubmed, $date_rec, $date_acc, $journal, $affil);
}
?>