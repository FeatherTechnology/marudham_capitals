<?php

// this will delete all files in the uploads directory except the excel_format directory
function deleteFilesInDirectory($dir, $excludeDir)
{
    if (!is_dir($dir)) {
        echo "Directory does not exist: $dir";
        return;
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($files as $fileinfo) {
        $filePath = $fileinfo->getRealPath();
        // Skip the files in the excluded directory
        if (strpos($filePath, $excludeDir) === false && $fileinfo->isFile()) {
            if (!unlink($filePath)) {
                echo "Failed to delete file: $filePath\n";
            } else {
                echo "Deleted file: $filePath\n";
            }
        }
    }
}

$uploadsDir = '../uploads';
$excludeDir = realpath('../uploads/excel_format'); // Get the real path of the excluded directory

deleteFilesInDirectory($uploadsDir, $excludeDir);

echo "File deletion process completed.";
