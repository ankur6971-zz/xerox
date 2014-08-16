<?php

    if (php_sapi_name() != 'cli') {
        die("\nThis script can only be run in commandline...\n");
    }

    if ($argc < 8) {
        die("\nToo Few Arguments specified. The exact number is 8.\n");
    }

    if ($argc > 8) {
        die("\nToo Many Arguments specified. The exact number is 8.\n");
    }

    $userName       = '';
    $passPhrase     = '';
    $url            = '';
    $repositoryName = '';
    $vendorName     = '';

    foreach ($argv as $argIndex => $arguments) {
        if (strcasecmp($arguments, "-u") == 0) {
            $userName = $argv[$argIndex + 1];
            continue;
        }
        if (strcasecmp($arguments, "-p") == 0) {
            $passPhrase = $argv[$argIndex + 1];
            continue;
        }
        if (preg_match('/^(http|https):\/\/[a-z0-9\/.-]*(issues)$/i', $arguments)) {
            $url             = $arguments;
            $otherSegments   = explode("/", $url);
            $vendorName      = $otherSegments[2];
            $repositoryName  = $otherSegments[count($otherSegments) - 2];
            $userNameFromUrl = $otherSegments[count($otherSegments) - 3];
            if (strcasecmp($userName, $userNameFromUrl) != 0) {
                die("\nUsername in the url does not match the username provided in the credentials.");
            }
            continue;
        }
    }
    $title = $argv[$argc - 2];
    $body  = $argv[$argc - 1];

    if (strlen($url) == 0) {
        die("\nMalformed URL.\n");
    }
    require_once 'CreateNewIssue.php';
    $issueHandle = new CreateNewIssue($vendorName, $userName, $passPhrase, $url, $repositoryName, $title, $body);
    $issueHandle->createIssue();

    /**
     * some points to check for invalid input and exit
     * 1) The count of argc = 8 (1 for the program name and 7 for the arguments)
     * 2) -u should mean the next element is username
     * 3) -p should mean the next element is password
     * 4) url would be parsed to extract out the repository name
     * 5) url would be parsed to check if the username matches with the username provided seperately
     * 6) url formats:
     * https://api.github.com/repos/:username/:repository-name/issues
     * https://bitbucket.org/api/1.0/repositories/:username/:repository-name/issues
     * 7) title and body text are mandatory and would have to be enclosed in double or single quotation marks
     */
