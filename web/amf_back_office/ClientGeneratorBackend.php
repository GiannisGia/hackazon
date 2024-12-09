<?php
/**
 *  This file is part of amfPHP
 *
 * LICENSE
 *
 * This source file is subject to the license that is bundled
 * with this package in the file license.txt.
 * @package Amfphp_BackOffice_ClientGenerator
 */
/**
 * includes
 */
require_once(dirname(__FILE__) . '/ClassLoader.php');
$accessManager = new Amfphp_BackOffice_AccessManager();
$isAccessGranted = $accessManager->isAccessGranted();
if(!$isAccessGranted){
    die('User not logged in');
}

$servicesStr = null;
if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
    $servicesStr = $GLOBALS['HTTP_RAW_POST_DATA'];
}else{
    $servicesStr = file_get_contents('php://input');
}
    
$services = json_decode($servicesStr);

// **Ασφαλής Χρήση του generatorClass**
$allowedGenerators = ['Generator1', 'Generator2', 'Generator3']; // Έγκυρα ονόματα
$generatorClass = $_GET['generatorClass'] ?? null;

if (!in_array($generatorClass, $allowedGenerators)) {
    die('Invalid generator class');
}

$generatorManager = new Amfphp_BackOffice_ClientGenerator_GeneratorManager();
$generators = $generatorManager->loadGenerators(['ClientGenerator/Generators']);
$config = new Amfphp_BackOffice_Config();
$generator = $generators[$generatorClass];

// Ασφαλής Δημιουργία Ονόματος Φακέλου
$newFolderName = date("Ymd-his-") . $generatorClass;
$genRootRelativeUrl = 'ClientGenerator/Generated/';
$genRootFolder = realpath(AMFPHP_BACKOFFICE_ROOTPATH . $genRootRelativeUrl);
$targetFolder = $genRootFolder . DIRECTORY_SEPARATOR . $newFolderName;

// Επικύρωση της Διαδρομής
if (strpos($targetFolder, $genRootFolder) !== 0) {
    die('Invalid target folder');
}

// Δημιουργία φακέλου και παραγόμενων αρχείων
$generator->generate($services, $config->resolveAmfphpEntryPointUrl(), $targetFolder);
$urlSuffix = $generator->getTestUrlSuffix();

if ($urlSuffix !== false) {
    // Ασφαλής έξοδος HTML
    $safeLink = htmlspecialchars($genRootRelativeUrl . $newFolderName . '/' . $urlSuffix, ENT_QUOTES, 'UTF-8');
    echo '<a target="_blank" href="' . $safeLink . '"> try your generated project here</a><br/><br/>';
}

if (Amfphp_BackOffice_ClientGenerator_Util::serverCanZip()) {
    $zipFileName = "$newFolderName.zip";
    $zipFilePath = $genRootFolder . DIRECTORY_SEPARATOR . $zipFileName;
    Amfphp_BackOffice_ClientGenerator_Util::zipFolder($targetFolder, $zipFilePath, $genRootFolder);

    // Ασφαλής ανακατεύθυνση μέσω JavaScript
    $safeZipLink = htmlspecialchars($genRootRelativeUrl . $zipFileName, ENT_QUOTES, 'UTF-8');
    echo '<script>window.location="' . $safeZipLink . '";</script>';
} else {
    echo "Server cannot create a zip of the generated project because ZipArchive is not available.<br/><br/>";
    echo 'Client project written to ' . htmlspecialchars($targetFolder, ENT_QUOTES, 'UTF-8');
}

