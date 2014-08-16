<?php

    /**
     * Class CreateNewIssue
     *
     * Create a new issue by sending the data to the appropriate server
     *
     * @author Ankur Kumar <ankur6971@gmail.com>
     */
    class CreateNewIssue {

        private $_vendor;
        private $_userName;
        private $_passPhrase;
        private $_apiUrl;
        private $_repository;
        private $_issueTitle;
        private $_issueTitleName;
        private $_issueBody;
        private $_issueBodyName;

        /**
         * The constructor for the class which takes in some arguments and initiate the engine
         *
         * @param string $vendor         The repository vendor name
         * @param string $userName       The account access username
         * @param string $passPhrase     The account access password
         * @param string $url            The url where the issue will be pushed to
         * @param string $repositoryName The name of the repository for which the issue will be created. A proper use is to yet to be found out for this argument
         * @param string $title          The issue title
         * @param string $body           The issue description
         */
        function __construct($vendor, $userName, $passPhrase, $url, $repositoryName, $title, $body) {
            $this->_setVendor($vendor);
            $this->_setUserName($userName);
            $this->_setPassPhrase($passPhrase);
            $this->_setUrl($url);
            $this->_setRepository($repositoryName);
            $this->_setIssueTitleName('title');
            $this->_setIssueTitle($title);
            $this->_setIssueBodyName($body);
            $this->_setIssueBody($body);
        }

        /**
         * Set the service provider name
         *
         * @param string $vendor The name of the service provider
         */
        private function _setVendor($vendor) {
            $this->_vendor = $vendor;
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
         * Decide the type of request that will be used to send data
         *
         * @return string The type (url or json)
         */
        private function _requestType() {
            if (preg_match('/\bbitbucket\b/i', $this->_vendor)) {
                $this->_setIssueBodyName('content');

                return 'url';
        }

            $this->_setIssueBodyName('body');

            return 'json';
        }

        /**
         * Set the title of the issue to be created
         *
         * @param string $title The title of the issue to be created
         */
        private function _setIssueTitle($title) {
            if ($this->_requestType() == 'url') {
                $this->_issueTitle = urlencode($title);
            } else {
                $this->_issueTitle = $title;
            }
        }

        /**
         * Set the issue description
         *
         * @param string $body The body or content of the issue
         */
        private function _setIssueBody($body) {
            if ($this->_requestType() == 'url') {
                $this->_issueBody = urlencode($body);
            } else {
                $this->_issueBody = $body;
            }
        }


        /**
         * Set the name of the title to be passed on in the url / json string
         *
         * @param string $titleName The name of the title
         */
        private function _setIssueTitleName($titleName) {
            $this->_issueTitleName = $titleName;
        }

        /**
         * Set the name of the body to be passed in the url / json string
         *
         * @param string $bodyName The name of the body
         */
        private function _setIssueBodyName($bodyName) {
            $this->_issueBodyName = $bodyName;
        }

        /**
         * Get the string which will be used to pass data to the webservice
         *
         * @return string Json or url encoded data
         */
        private function _getPostString() {
            $postString = array(
                $this->_issueTitleName => $this->_issueTitle,
                $this->_issueBodyName  => $this->_issueBody
            );
            if ($this->_requestType() == 'json') {
                $postString = json_encode($postString);

                return $postString;
        }

            $string = '';
            foreach ($postString as $postIndex => $postValue) {
                $string .= $postIndex . '=' . $postValue . '&';
        }

            return rtrim($string, '&');
        }


        /**
         * Create the issue by using all the data that has been cooked all this time.
         */
        public function createIssue() {
            $postString = $this->_getPostString();
            $userName   = $this->_userName . ':' . $this->_passPhrase;
            $curl       = curl_init();
            curl_setopt($curl, CURLOPT_USERAGENT, 'Ankur-Kumar-Laptop');
            curl_setopt($curl, CURLOPT_URL, $this->_apiUrl);
            curl_setopt($curl, CURLOPT_USERPWD, $userName);
            curl_setopt($curl, CURLOPT_POST, 2);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postString);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            $result = curl_exec($curl);
            curl_close($curl);
        }


    }
