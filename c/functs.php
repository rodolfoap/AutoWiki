<?php
function lengthsort($a,$b){
    return strlen($b[0])-strlen($a[0]);
}
function camel2phrase($text){ 
	return trim(ucwords(preg_replace('/([[:upper:]])/', ' $1', $text))); 
}
function getlinks(){
	function lengthc($a, $b){ return strlen($b)-strlen($a); }
	$lnklst=array_diff(scandir('m/'), array('.', '..'));
	usort($lnklst, "lengthc");
	foreach ($lnklst as $key=>$lnk) { 
		$lnkls[$key][0]="$lnk"; 
		$lnkls[$key][1]=camel2phrase($lnk); 
		$lnkls[$key][2]="<a href=$lnk>".camel2phrase($lnk).'</a>'; 
	}
	usort($lnklst,'lengthsort');
	return $lnkls;
}
function translinks($wconts){
	$lnklst=getlinks();
	foreach($lnklst as $lnk) {
		$parts=preg_split('/(<a [^>]*>[^<]*<\/a>)/i', $wconts, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		$fline="";
		foreach($parts as $part){
			if(substr($part, 0, 8)!='<a href=') $part=str_ireplace($lnk[0], $lnk[2], $part);
			$fline.=$part;
		}
		$wconts=$fline;
	}
	return $wconts;
}
function wpage($windex) {
	$wtitle=camel2phrase($windex);
	if($wconts=file_get_contents('m/'.$windex)) {
		$parser=new parser;
		$wconts=$parser->parse(htmlspecialchars($wconts));
		$wconts=translinks($wconts);
		include('v/page.tpl');
	}
	else include('v/404.tpl');
}
