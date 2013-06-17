<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

set_time_limit(0);
require_once 'function.php';

?>
<html>
	<head>
		<meta http-equiv="Pragma" content="no-cache"> 
		<meta http-equiv="Expires" content="-1">
	</head>
	<body>
		<form method="post" action="export.php">
<?php

$ini = parse_ini_file("ini/" . $_POST["iniFileName"]);
$export = $_POST["file"];
$arrayExport = array();
$folderLevel = 0;
$folderNum = 0;
$output = "";
$post = $_POST["file"];
$livrableName = $ini["deliveryName"] . date("Y_m_d");
$livrableFolder = $ini["deliveryFolder"];

if(file_exists($livrableFolder . "/" . $livrableName)) {
	rmdir_recursive($livrableFolder . "/" . $livrableName);
}

if(!mkdir($livrableFolder . "/" . $livrableName, 0777)) {
	throw new Exception("File cannot be create : " . $livrableFolder . "/" . $livrableName); exit();
}
chmod($livrableFolder . "/" . $livrableName, 0777);

showSubFolderExport($ini['url'], true, false);

echo $output;

$handle = fopen("json/" . $ini["json"], "w+");
fwrite($handle, json_encode($arrayExport));
fclose($handle);

$zipfilename  = $livrableName . ".zip";
if(file_exists($livrableFolder . "/" . $zipfilename)){
	unlink($livrableFolder . "/" . $zipfilename);
}
$dirlist = new RecursiveDirectoryIterator($livrableFolder);
$filelist = new RecursiveIteratorIterator($dirlist);
$zip = new ZipArchive();
if ($zip->open($livrableFolder . "/" . $zipfilename, ZipArchive::CREATE) !== TRUE) {
    die ("Could not open archive " . $livrableFolder . "/");
}
// add each file in the file list to the archive
foreach ($filelist as $key => $value) {
    $zip->addFile($key, $key) or die ("ERROR: Could not add file: $key");
}
$zip->close();
chmod($zipfilename, 0777);

if(file_exists($livrableFolder . "/" . $livrableName)) {
	rmdir_recursive($livrableFolder . "/" . $livrableName);
}
?>
		</form>
	</body>
</html>