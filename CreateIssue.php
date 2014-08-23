<?php

    /**
     * Class CreateIssue
     *
     * Define and declare the rules which will be used to send the data to the repository
     *
     * @author AnkuBodyr Kumar <ankur6971@gmail.com>
     */
    abstract class CreateIssue {

        private $_userName;
        private $_passPhrase;
        private $_apiUrl;
        private $_issueTitle;
        private $_issueTitleName;
        private $_issueBody;
        private $_issueBodyName;

        /**
         * The constructor for the class which takes in some arguments and initiate the engine
         *
         * @param string $userName   The account access username
         * @param string $passPhrase The account access password
         *
         */
        function __construct($userName, $passPhrase) {
            $this->_setUserName($userName);
            $this->_setPassPhrase($passPhrase);
        }

        /**
         * Create an issue to be sent
         *
         * @param bool $verbose Verbose Mode: <b><i>true</i></b> or <b><i>false</i></b>
         *
         */
        abstract function createIssue($verbose);


        /**
         * Set the access user name
         *
         * @param string $userName The username
         */
        private function _setUserName($userName) {
            $this->_userName = $userName;
        }

        /**
         * Set the access password
         *
         * @param string $passPhrase The password
         */
        private function _setPassPhrase($passPhrase) {
            $this->_passPhrase = $passPhrase;
        }


        /**
         * Set the server url where the issue will be created
         *
         * @param string $url The repository url
         */
        protected function _setApiUrl($url) {
            $this->_apiUrl = $url;
        }


        /**
         * Set the issue title to be sent over the repository webservice
         *
         * @param string $titleName The name of the title element
         * @param string $title     The issue title
         * @param bool   $urlEncode If true, urlencode the title; dont encode otherwise
         */
        protected function _setIssueTitle($titleName, $title, $urlEncode = false) {
            $this->_issueTitleName = $titleName;
            if ($urlEncode) {
                $this->_issueTitle = urlencode($title);
            } else {
                $this->_issueTitle = $title;
            }
        }


        /**
         * Set the actual issue that is to be created.
         *
         * @param string $bodyName The name of the body element in the issue
         * @param string $body      The issue that is to be created
         * @param bool   $urlEncode If true, urlencode the title; dont encode otherwise
         */
        protected function _setIssueBody($bodyName, $body, $urlEncode = false) {
            $this->_issueBodyName = $bodyName;
            if ($urlEncode) {
                $this->_issueBody = urlencode($body);
            } else {
                $this->_issueBody = $body;
            }
        }


        /**
         * Get the data to be sent finally
         *
         * @return array The organised list containing the issue details.
         */
        protected function _getPostArray() {
            return array(
                $this->_issueTitleName => $this->_issueTitle,
                $this->_issueBodyName  => $this->_issueBody
            );
        }


        /**
         * Create the issue by using all the data that has been cooked all this time.
         *
         * @param string      $postString  The proper data that is to be sent to the appropriate webservice
         * @param bool|string $verboseMode If set to true, the data will be sent in verbose mode; silent mode would be used otherwise.
         *
         * @return array
         * @throws WebServiceException
         */
        protected function _sendIssue($postString, $verboseMode = true) {
            $userCred = $this->_userName . ':' . $this->_passPhrase;
            $curl     = curl_init();
            curl_setopt($curl, CURLOPT_USERAGENT, 'Ankur-Kumar-Laptop');
            curl_setopt($curl, CURLOPT_URL, $this->_apiUrl);
            curl_setopt($curl, CURLOPT_USERPWD, $userCred);
            curl_setopt($curl, CURLOPT_POST, 2);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postString);
            curl_setopt($curl, CURLOPT_VERBOSE, $verboseMode);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $result      = curl_exec($curl);
            $information = curl_getinfo($curl);
            curl_close($curl);

            return array(
                'http_code' => $information['http_code'],
                'result'    => $result
            );
        }
    }
