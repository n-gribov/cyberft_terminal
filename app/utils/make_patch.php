<?php

// Creates zip patch archive with files changed in specified commits.
// Usage: php app/utils/make_patch.php a6efa5a3 ae61bc4b5 ae61bc4b50

$commits = getCommits($argv);
$changedFiles = getChangedFiles($commits);
createPatch($changedFiles);

function createPatch(array $changedFiles)
{
    $zip = new \ZipArchive();
    $zipFileName = 'patch-' . time() . '.zip';
    $isOpen = $zip->open($zipFileName, \ZipArchive::CREATE);
    if ($isOpen !== true) {
        echo "Failed to create file $zipFileName\n";
        exit(1);
    }
    foreach ($changedFiles as $fileName) {
        $isAdded = $zip->addFile($fileName, $fileName);
        if (!$isAdded) {
            echo "Failed to add file $fileName to zip archive\n";
            exit(1);
        }
    }
    $isSaved = $zip->close();
    if ($isSaved !== true) {
        echo "Failed to save file $zipFileName\n";
        exit(1);
    }
    echo "Patch is saved to $zipFileName\n";
}

function getCommits(array $argv): array
{
    if (count($argv) < 2) {
        echo "Please, specify commits\n";
        exit(1);
    }

    $commits = array_slice($argv, 1);
    foreach ($commits as $commit) {
        if (!preg_match('/^[a-f0-9]+$/', $commit)) {
            echo "Invalid commit: $commit\n";
            exit(1);
        }
    }
    return $commits;
}

function getChangedFiles(array $commits): array
{
    return array_reduce(
        $commits,
        function ($files, $commit) {
            $filesInCommit = getChangedFilesByCommit($commit);
            return array_unique(
                array_merge($files, $filesInCommit)
            );
        },
        []
    );
}

function getChangedFilesByCommit(string $commit): array
{
    exec("git --no-pager diff --name-only $commit~ $commit", $files, $result);
    if ($result !== 0) {
        echo "Failed to get changed files for commit $commit\n";
        exit(1);
    }
    return $files;
}
