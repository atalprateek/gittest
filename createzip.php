<?php
include("backup.php");
if($backup_name!=''){
	$tozip="gradxs";
	// Get real path for our folder
	$rootPath = realpath("$tozip");
	
	// Initialize archive object
	$zip = new ZipArchive();
	if(date('H')>11){
		$time="evening-".date('d-m-Y');
	}
	else{
		$time="morning-".date('d-m-Y');
	}
	$filename="backup/".$tozip."-".$time.".zip";
	$zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE);
	
	// Create recursive directory iterator
	/** @var SplFileInfo[] $files */
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($rootPath),
		RecursiveIteratorIterator::LEAVES_ONLY
	);
	
	foreach ($files as $name => $file)
	{
		// Skip directories (they would be added automatically)
		if (!$file->isDir())
		{
			// Get real and relative path for current file
			$filePath = $file->getRealPath();
			$relativePath = substr($filePath, strlen($rootPath) + 1);
			// Add current file to archive
			$zip->addFile($filePath, $relativePath);
		}
	}
	$zip->addFile(realpath($backup_name),$backup_name);
	// Zip archive will be created only after closing object
	if($zip->close()){
		$dir="backup/";
		$dirs=array();
		if (is_dir($dir)){
			if ($dh = opendir($dir)){
				while (($file = readdir($dh)) !== false){
					$filetype=filetype($dir.$file);
					if($filetype!='dir'){
						$time=filemtime($dir.$file);
						$dirs[$time]=$file;
					}
				}
			}
		}
		ksort($dirs);
		print_r($dirs);
		if(sizeof($dirs)>14){
			foreach($dirs as $single){
				//echo $dir.$single;
				unlink($dir.$single);
				break;
			}
		}
		//mail('atal.prateek@rsgss.com','Cron Job Test Script 123',date("d-m-Y H:i:s")." backed up.");
		unlink($backup_name);
	}
}
?>
