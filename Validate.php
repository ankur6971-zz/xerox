<?php

    /**
     * Class Validate
     *
     * Handles the validation and processing of the input
     *
     * @author Ankur Kumar <ankur6971@gmail.com>
     */
    class Validate {

        private $_NUMBER_OF_ARGS = 8;

        function __construct($argv, $argc) {
            require_once 'Exceptions.php';
            $this->_argv = $argv;
            $this->_argc = $argc;

        }

        /**
         * Check if the input is OK to proceed for further processing
         *
         * @return bool true if all ok, false otherwise
         */
        public function isEverythingOK() {
            /*try {
                $this->_checkCliAvailable();
            } catch (CLIAbsentException $cae) {
                die($cae->getMessage());
            }*/

            try {
                $this->checkCurlAvailable();
            } catch (CurlAbsentException $cae) {
                die($cae->getMessage());
            }

            try {
                $this->checkNumberOfArgs();
            } catch (InvalidNumberOfArgumentsException $iae) {
                die($iae->getMessage());
            }

            return true;
        }


        /**
         * Checks if the invocation is from cli.
         *
         * @throws CLIAbsentException If the invocation is not cli
         */
        private function _checkCliAvailable() {
            if (php_sapi_name() != 'cli') {
                throw new CLIAbsentException();
            }
        }


        /**
         * Checks if curl extension is available
         *
         * @throws CurlAbsentException If the curl extension is not available
         */
        private function checkCurlAvailable() {
            if (!function_exists('curl_init')) {
                throw new CurlAbsentException();
            }
        }


        /**
         * Checks the number of arguments supplied via the commandline
         *
         * @throws InvalidNumberOfArgumentsException If the number is different from _NUMBER_OF_ARGS
         */
        private function checkNumberOfArgs() {
            if ($this->_argc != $this->_NUMBER_OF_ARGS) {
                throw new InvalidNumberOfArgumentsException();
            }
        }

        /**
         * Check if a given string is empty or not.
         *
         * @param string $string  The target string
         *
         * @param string $message The message to be displayed in case of the target string being empty
         *
         * @throws EmptyStringException If the target string is empty
         */
        private function _checkEmpty($string, $message) {
            if (strlen($string) == 0) {
                throw new EmptyStringException($message);
            }
        }


        /**
         * Parse the input and find out the following:
         * <ul>
         * <li>Username</li>
         * <li>Password</li>
         * <li>Repository url</li>
         * <li>Vendor which provides the repository. (this is used in selecting the type of service)</li>
         * <li>Repository name</li>
         * <li>Issue title</li>
         * <li>Issue description</li>
         * </ul>
         *
         * @return stdClass A class containing the above mentioned details
         */
        public function parseDetails() {
            $issueDetails = new stdClass();
            $url = '';

            foreach ($this->_argv as $argIndex => $arguments) {
                if (strcasecmp($arguments, "-u") == 0) {
                    $issueDetails->userName = $this->_argv[$argIndex + 1]; // The next element is presumed to be the username.
                    continue;
                }
                if (strcasecmp($arguments, "-p") == 0) {
                    $issueDetails->passPhrase = $this->_argv[$argIndex + 1]; // The next element is presumed to be the password.
                    continue;
                }
                if (preg_match('/^(http|https):\/\/[a-z0-9\/.-]*$/i', $arguments)) {
                    $url                          = $arguments;
                    $otherSegments                = parse_url($url);
                    $issueDetails->vendorName     = $otherSegments['host'];
                    $path                         = explode("/", $otherSegments['path']);
                    $issueDetails->repoUser       = $path[1];
                    $issueDetails->repositoryName = $path[2];
                    continue;
                }
            }

            $issueDetails->title = $this->_argv[$this->_argc - 2];
            try {
                $errorMessage = "\nPlease specify the issue title.\n";
                $this->_checkEmpty($issueDetails->title, $errorMessage);
            } catch (EmptyStringException $ese) {
                die($ese->getMessage());
            }

            $issueDetails->body = $this->_argv[$this->_argc - 1];
            try {
                $errorMessage = "\nPlease specify the issue description.\n";
                $this->_checkEmpty($issueDetails->body, $errorMessage);
            } catch (EmptyStringException $ese) {
                die($ese->getMessage());
            }

            try {
                $errorMessage = "\nPlease specify the repository url.\n";
                $this->_checkEmpty($url, $errorMessage);
            } catch (EmptyStringException $ese) {
                die($ese->getMessage());
            }

            return $issueDetails;
        }
    }
