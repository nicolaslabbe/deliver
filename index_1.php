<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

require_once 'function.php';
$iniFileName = "config_1.ini";

?>
<html>
	<head>
		<meta http-equiv="Pragma" content="no-cache"> 
		<meta http-equiv="Expires" content="-1">
		
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/common.js"></script>
	</head>
	<body>
<?php

$ini = parse_ini_file("ini/" . $iniFileName);
if(file_exists("json/" . $ini["json"])) {
	$stringExport = file_get_contents("json/" . $ini["json"]);
}else {
	$stringExport = "";
}
$export = json_decode($stringExport, true);
$folderLevel = 0;
$folderNum = 0;
$output = "";

showSubFolder($ini['url'], true, false);

?>
		<div style="float: right;">
			<label>Green</label>
			<input type="checkbox" value="rgb(105, 221, 118)" class="showColor"/>
			<label>Red</label>
			<input type="checkbox" value="rgb(221, 105, 129)" class="showColor"/>
			<label>Blue</label>
			<input type="checkbox" value="rgb(105, 176, 221)" class="showColor"/>
		</div>
		<form method="post" action="<?php echo $ini['export']; ?>" id="formulaire">
			<input type="button" name="Export" value="Export" class="export" />
			<input type="hidden" name="iniFileName" value="<?php echo $iniFileName; ?>">
<?php
echo $output;
?>
			<input type="button" name="Export" value="Export" class="export" />
		</form>
	</body>
</html>