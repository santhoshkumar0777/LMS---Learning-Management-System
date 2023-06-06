<?php
require '/var/www/html/php/Connection.php';
use Aws\S3\ObjectUploader;

if(isset($_POST['dataArray'])){
	$arrr=$_POST['dataArray'];

	$vttFile=fopen($_POST['fileName'], 'a');
	fwrite($vttFile, 'WEBVTT'.PHP_EOL);
	fwrite($vttFile, ''.PHP_EOL);

	for ($i=0; $i < count($arrr); $i++) { 
		if(($i+1)==count($arrr)){
			$time=explode(":", $arrr[$i]['timing']);
			$ffff= (int)$time[0]+1;
			if($ffff<10){
				$time[0]='0'.strval($ffff);
			}
			fwrite($vttFile, $arrr[$i]['timing'].'.000 --> '.$time[0].':'.$time[1].'.000'.PHP_EOL);
			fwrite($vttFile, $arrr[$i]['caption'].PHP_EOL);	
		}else{
			fwrite($vttFile, $arrr[$i]['timing'].'.000 --> '.$arrr[$i+1]['timing'].'.000'.PHP_EOL);
			fwrite($vttFile, $arrr[$i]['caption'].PHP_EOL);
			fwrite($vttFile, ''.PHP_EOL);
		}
	}

	fclose($vttFile);

	$source = fopen($_POST['fileName'], 'rb');

	$uploader = new ObjectUploader(
	    $s3client,
	    $bucketName,
	    $_POST['fileName'],
	    $source
	);

	$result = $uploader->upload();

	echo $result['ObjectURL'];
	$ttttt=$_POST['fileName'];

	$delete="unlink /var/www/html/course-edit/online/$ttttt";
	system($delete);
}
?>