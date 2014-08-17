<?php

    /**
     * Class CreateIssue
     *
     * Define and declare the rules which will be used to send the data to the repository
     *
     * @author Ankur Kumar <ankur6971@gmail.com>
     */
    abstract class CreateIssue {

        protected $_userName;
        protected $_passPhrase;
        protected $_apiUrl;
        protected $_repository;
        protected $_issueTitle;
        protected $_issueBody;

        /**
         * Set the name of the body to be passed in the url / json string
         *
         * @param string $bodyName The name of the body element in the issue
         */
        abstract protected function _setIssueBodyName($bodyName);

        /**
         * Set the name of the title to be passed in the url / json string
         *
         * @param string $titleName The name of the title element
         */
        abstract protected function _setIssueTitleName($titleName);


        /**
         * The constructor for the class which takes in some arguments and initiate the engine
         *
         * @param string $userName       The account access username
         * @param string $passPhrase     The account access password
         * @param string $url            The url where the issue will be pushed to
         * @param string $repositoryName The name of the repository for which the issue will be created. A proper use is to yet to be found out for this argument
         */
        function __construct($userName, $passPhrase, $url, $repositoryName) {
            $this->_setUserName($userName);
            $this->_setPassPhrase($passPhrase);
            $this->_setUrl($url);
            $this->_setRepository($repositoryName);
        }


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
        private function _setUrl($url) {
            $this->_apiUrl = $url;
        }

        /**
         * Set the name of the repository
         *
         * @param string $repositoryName The name of the repository where the issue will be created
         */
        private function _setRepository($repositoryName) {
            $this->_repository = $repositoryName;
        }


        /**
         * Set the issue title to be sent over the repository webservice
         *
         * @param string $title     The issue title
         * @param bool   $urlEncode If true, urlencode the title; dont encode otherwise
         */
        protected function _setIssueTitle($title, $urlEncode = false) {
            if ($urlEncode) {
                $this->_issueTitle = urlencode($title);
            } else {
                $this->_issueTitle = $title;
            }
        }


        /**
         * Set the actual issue that is to be created.
         *
         * @param string $body      The issue that is to be created
         * @param bool   $urlEncode If true, urlencode the title; dont encode otherwise
         */
        protected function _setIssueBody($body, $urlEncode = false) {
            if ($urlEncode) {
                $this->_issueBody = urlencode($body);
            } else {
                $this->_issueBody = $body;
            }
        }


        /**
         * Get the data to be sent finally
         *
         * @param string $issueTitleName The name by which the webservice recognises the issue title
         * @param string $issueBodyName  The name by which the webservice recognises the issue body
         *
         * @return array The organised list containing the issue details.
         */
        protected function getPostData($issueTitleName, $issueBodyName) {
            return array(
                $issueTitleName => $this->_issueTitle,
                $issueBodyName  => $this->_issueBody
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
        protected function createIssue($postString, $verboseMode = true) {
            $userName = $this->_userName . ':' . $this->_passPhrase;
            $curl     = curl_init();
            curl_setopt($curl, CURLOPT_USERAGENT, 'Ankur-Kumar-Laptop');
            curl_setopt($curl, CURLOPT_URL, $this->_apiUrl);
            curl_setopt($curl, CURLOPT_USERPWD, $userName);
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
