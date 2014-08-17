<?php

    /**
     * Class CLIAbsentException
     * Handles the case when the application is not invoked via commandline
     */
    class CLIAbsentException extends Exception {
        private $_errorMessage = "\nThis script can only be run in commandline...\n";

        function __construct() {
            parent::__construct($this->_errorMessage);
        }
    }

    /**
     * Class CurlAbsentException
     * Handles the case when the PHP5-CURL extension is not available
     */
    class CurlAbsentException extends Exception {
        private $_errorMessage = "\nPHP5-Curl needs to be enabled for this application. Please enable the module to proceed\n";

        function __construct() {
            parent::__construct($this->_errorMessage);
        }
    }

    /**
     * Class InvalidNumberOfArgumentsException
     * Handles the case when the commandline input is invalid
     */
    class InvalidNumberOfArgumentsException extends Exception {
        private $_errorMessage = "\nCorrect usage: ./create-issue -u jdoe -p secret https://repository.com/example/test \"The title of my issue\" \"Here's what I did to reproduce the problem\"\n";

        function __construct() {
            parent::__construct($this->_errorMessage);
        }
    }

    /**
     * Class EmptyStringException
     * Handles the case when the target string is empty
     */
    class EmptyStringException extends Exception {
        function __construct($message) {
            parent::__construct($message);
        }
    }

    /**
     * Class InvalidUsernameException
     * Handles the case when the user name in the url is different from the username in the url
     */
    class InvalidUsernameException extends Exception {
        private $_errorMessage = "\nUsername in the url does not match the username provided in the credentials.";

        function __construct() {
            parent::__construct($this->_errorMessage);
        }
    }

    /**
     * Class WebServiceException
     * Handles the case when there is some error in the application usage and the webservice has returned some error.
     */
    class WebServiceException extends Exception {
        function __construct($message) {
            parent::__construct($message);
        }
    }