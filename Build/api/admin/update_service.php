<?php
$app->post('/updateSystem', function() use ($app)  {
	
	adminRequire(); 
    $db = new DbHandler();
    
    $filename = $_FILES['file']['name'];
    
	$rand = generateRandomString(18);
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	
	$absPath = '../../updates/';
    if (!file_exists($absPath)) {
    	mkdir($absPath, 0777, true);
	}
	$backupPath =  '../../backups/';
    if (!file_exists($backupPath)) {
    	mkdir($backupPath, 0777, true);
	}
	
    $destination = $absPath.$filename;
    move_uploaded_file( $_FILES['file']['tmp_name'] , $destination );
    
	$zip = new ZipArchive;
	$res = $zip->open($destination);
	
	if ($res === FALSE) {
    	echoError("Cannot open update archive file.");
		return;
	}
	
	$unzip_success = $zip->extractTo('../../updates/','info.txt');
	$zip->close();
	
	$updateInfo = new SiteInfo();
	$currentInfo = new SiteInfo();
	
	if(!$unzip_success){
    	echoError('cannot read info file.');
		return;
	}else{
		$myfile = fopen('../../updates/info.txt', "r") or die("Unable to open update version file!");
		
		$updateInfo->getFromFile('../../updates/info.txt');
		$currentInfo->getFromFile('../../info.txt');
		
		if($updateInfo->version == $currentInfo->version){
	    //	echoError("Not update because current site version is same with update version!");
		//	return;
		}
	}
    
	makeBackupFile($db);
	
	if($updateInfo->forceUpdateDB){
		backup_tables($db);
	}
    
	$zip = new ZipArchive;
	$res = $zip->open($destination);
	if ($res === TRUE) {
		$unzip_success= $zip->extractTo('../../');
	    $zip->close();
		
		if($updateInfo->forceUpdateDB){
			$db->startTransaction();
			updateDatabase($db,'../../db/cur-db.sql',TRUE);
			updateDatabase($db,'../../db/db-backup-data-last.sql');
			$db->commitTransaction();
		}
		
	    echoResponse(200, $unzip_success);
	    return;
	} else {
    	echoError("Error in open");
    	return;
	}
	
    echoResponse(200, "Success");
});

function updateDatabase($db,$filename, $dropRecent=FALSE){
    
    if($dropRecent){
		$result = $db->makeQuery("SHOW TABLES");
		
	    while($row = $result->fetch_array(MYSQLI_NUM))
	    {
	        $db->makeQuery('DROP TABLE IF EXISTS '.$row[0]);
	    }
	}
	/*
    $commands = file_get_contents($filename);   
	$res = $db->multiQuery($commands);
	return $res;*/

    $lines = file($filename);
	$templine ="";
	// Loop through each line
	foreach ($lines as $line)
	{
	// Skip it if it's a comment
	if (substr($line, 0, 2) == '--' || $line == '')
	    continue;
	// Add this line to the current segment
	$templine .= $line;
	// If it has a semicolon at the end, it's the end of the query
	if (substr(trim($line), -1, 1) == ';')
	{
	    // Perform the query
	    	$db->makeQuery($templine) or die('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
	    // Reset temp variable to empty
	    $templine = '';
	}
	}
} 

function backup_tables($db,$tables = '*' , $withData = TRUE){
	//get all of the tables
	if($tables == '*')
	{
		$tables = array();
		$result = $db->makeQuery('SHOW TABLES');
		while($row = $result->fetch_assoc())
		{
			$tables[] = $row['Tables_in_'.DB_NAME];
		}
	}
	else
	{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}
	$returnStructure ="";
	$returnData ="";
	//cycle through
	foreach($tables as $table)
	{
		//$returnStructure.= 'DROP TABLE '.$table.';';
		$resQ =$db->makeQuery('SHOW CREATE TABLE '.$table);
		$row2 = $resQ->fetch_assoc();
		$returnStructure.= "\n".$row2["Create Table"].";\n\n";
		
		if($withData){
			$result = $db->makeQuery('SELECT * FROM '.$table);
			$num_fields = $result->field_count;
			
			$statement = $db->makeQuery("DESCRIBE ".$table);
			$cols = [];
			while($row = $statement->fetch_assoc())
			{
				$cols[] = $row['Field'];
			}
			
			while($row = $result->fetch_array())
			{
				$returnData.= 'INSERT INTO '.$table.' (';
				for($j=0; $j < $num_fields; $j++) 
				{
					$returnData.= '`'.$cols[$j].'`' ;
					if ($j < ($num_fields-1)) { $returnData.= ','; }
				}
				$returnData.= ') VALUES(';
				for($j=0; $j < $num_fields; $j++) 
				{
					$row[$j] = addslashes($row[$j]);
					$row[$j] = ereg_replace("\n","\\n",$row[$j]);
					$str = mb_convert_encoding($row[$j], "UTF-8", "auto");
					if (isset($row[$j])) { $returnData.= '"'.$str.'"' ; } else { $returnData.= '""'; }
					
					if ($j < ($num_fields-1)) { $returnData.= ','; }
				}
				$returnData.= ");\n";
			}
			$returnData.="\n\n\n";
		}
	}
	
	$handle = fopen('../../db/db-backup-data-last.sql','w+') ;
	fwrite($handle,$returnData);
	fclose($handle);
	
	$handle = fopen('../../db/db-backup-structure-last.sql','w+') ;
	fwrite($handle,$returnStructure);
	fclose($handle);
	
	$handle = fopen('../../db/db-backup-both-last.sql','w+') ;
	fwrite($handle,$returnStructure);
	fwrite($handle,$returnData);
	fclose($handle);
}

function makeBackupFile($db){
	
	// Initialize archive object
	$zip = new ZipArchive();
	$zipFilePath = '../../backups/backup.'.date("Y.m.d.h.i.s").'.zip';
	$zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

	$dirs_to_zip = array(
		'api/',
		'app/',
		'css/',
		'fonts/',
		'images/',
		'db/',
		'jquery/',
		'js/',
		'partials/',
		'Scripts/'
	);

	foreach($dirs_to_zip as $root_dir){
		
		$rootPath = realpath('../../'.$root_dir);
		
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
			    $relativePath = $root_dir.substr($filePath, strlen($rootPath) + 1);
		        // Add current file to archive
		        $zip->addFile($filePath, $relativePath);
		    }
		}
	}
	// Zip archive will be created only after closing object
	$zip->close();
	
    $sess = $db->getSession();
	$db->insertToTable('backup','Date,FullPath,UserID',"NOW(),'$zipFilePath','".$sess["UserID"]."'");
} 
?>