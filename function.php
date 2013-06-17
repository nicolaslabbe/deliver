<?php

/**
 * affiche les sous dossier d'un path donné
 * @param $folder path folder 
 * @param $countFolder compte le folder into input select (never at first level)
 */
function showSubFolder($folder, $countFolder = false) {
	global $folderLevel, $folderNum, $output, $export, $ini;
	
	if($folder != $ini['selfPath']) {
		
		// if some file to export so don't dowload all the folder otherwise use all
		$arrayFolder = scandir($folder);
		
		foreach($arrayFolder as $key => $value) {
			if($value[0] != ".") {
				$file = $folder . "/" . $value;
				
				if($folder . "/" . $value != $ini['selfPath']) {
					if(isset($export[$file]) && $export[$file] == 1) {
						$checkIt = true;
						$output .= '<div style="padding: 2px 0px 3px 0px; margin-left: 20px; border-top: 1px solid #FFFFFF; background-color: #69dd76;" class="children" data-selected="true">';
					}elseif(!isset($export[$file])|| $export[$file] == null) {
						$checkIt = false;
						$output .= '<div style="padding: 2px 0px 3px 0px; margin-left: 20px; border-top: 1px solid #FFFFFF; background-color: #69b0dd;" class="children" data-selected="false">';
					}else {
						$checkIt = false;
						$output .= '<div style="padding: 2px 0px 3px 0px; margin-left: 20px; border-top: 1px solid #FFFFFF; background-color: #dd6981;" class="children" data-selected="false">';
					}
					
					$stringCheck = "";
					if($checkIt){
						$stringCheck = "checked='checked'";
					}

					if(is_dir($folder . "/" . $value)) {
						$output .= '<input autocomplete="off" '.$stringCheck.' type="checkbox" name="file[]" value="' . $file . '" class="folder" data-show-level="'.$file.'"/><label><strong>' . $value . '</strong></label>';
					}else {
						$output .= '<input autocomplete="off" '.$stringCheck.' type="checkbox" name="file[]" value="' . $file . '"/><label>' . $value . '</label>';
					}
				
					if(is_dir($folder . "/" . $value)) {
						$folderLevel++;
						showSubFolder($folder . "/" . $value, false);
						$folderLevel--;
					}
					
					if( $countFolder ) {
						$folderNum++;
					}
					$output .= '</div>';
				}
			}
		}
	}
}


/**
 * affiche les sous dossier d'un path donné
 * @param $folder path folder 
 * @param $countFolder compte le folder into input select (never at first level)
 */
function showSubFolderExport($folder, $countFolder = false) {
	global $folderLevel, $folderNum, $output, $export, $arrayExport, $livrableFolder, $livrableName, $ini;
	
	if($folder != $ini['selfPath']) {
		// if some file to export so don't dowload all the folder otherwise use all
		$arrayFolder = scandir($folder);
		
		foreach ($arrayFolder as $key => $value) {
			if($value[0] != ".") {
				$file = $folder . "/" . $value;
					
				if($file != $ini['selfPath']) {
					if(in_array($file, $export)) {
						$arrayExport[$file] = 1;
						// on créé le dossier
						$newFile = $livrableFolder . "/" . $livrableName . "/" . str_replace('../', '', $file);
						if(!file_exists($newFile)) {
							$is_dir = is_dir($file);
							$count_dir = 0;
							$explode = explode("/", $newFile);
							$allInOne = "";
							foreach ($explode as $key => $value) {
								$count_dir++;
								$allInOne .= $value . "/";
								if($count_dir < count($explode) && !$is_dir) {
									if(!file_exists($allInOne)) {
										if((file_exists($allInOne) === false) && $allInOne != "") {
											if(!mkdir($allInOne, 0777)){
												throw new Exception("File cannot be create : " . $allInOne); exit();
											}
											chmod($allInOne, 0777);
										}
									}
								}
							}
						}
						if(is_dir($file)) {
							$output .= '<div style="margin: 2px 0px 3px 20px; border-top: 1px solid #FFFFFF; background-color: #69dd76;">';
						// on copy le fichier
						}else {
							if(!copy($file, $newFile)) {
								throw new Exception("Cannot copy file file :" . $file . " to new file :". $newfile); exit();
							}
							$output .= '<div style="margin: 2px 0px 3px 20px; border-top: 1px solid #FFFFFF; background-color: #69dd76;">';
						}
					}else {
						$arrayExport[$file] = 2;
						$output .= '<div style="margin: 2px 0px 3px 20px; border-top: 1px solid #FFFFFF; background-color: #dd6981;">';
					}
					
					$output .= '<label style="text-indent: 5px; display: block;">' . $value . '</label>';
					
					if(is_dir($folder . "/" . $value)) {
						$folderLevel++;
						showSubFolderExport($folder . "/" . $value, false);
						$folderLevel--;
					}
					
					if( $countFolder ) {
						$folderNum++;
					}
					$output .= '</div>';
				}
			}
		}
	}
}


/**
 * Supprime récursivement tout les fichiers et dossier dans un path donné en params
 * @param $dir string path name
 */
function rmdir_recursive($dir)
{
	//Liste le contenu du répertoire dans un tableau
	$dir_content = scandir($dir);
	//Est-ce bien un répertoire?
	if($dir_content !== FALSE){
		//Pour chaque entrée du répertoire
		foreach ($dir_content as $entry)
		{
			//Raccourcis symboliques sous Unix, on passe
			if(!in_array($entry, array('.','..'))){
				//On retrouve le chemin par rapport au début
				$entry = $dir . '/' . $entry;
				//Cette entrée n'est pas un dossier: on l'efface
				if(!is_dir($entry)){
					unlink($entry);
				}
				//Cette entrée est un dossier, on recommence sur ce dossier
				else{
					rmdir_recursive($entry);
				}
			}
		}
	}
	//On a bien effacé toutes les entrées du dossier, on peut à présent l'effacer
	rmdir($dir);
}
?>