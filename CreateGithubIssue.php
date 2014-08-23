<?php
    require_once 'CreateIssue.php';

    /**
     * Class CreateGithubIssue
     *
     * Create an issue for the Github repository located at url:
     * https://api.github.com/repos/:user/:repo/issues
     *
     * @author Ankur Kumar <ankur6971@gmail.com>
     */
    class CreateGithubIssue extends CreateIssue {

        function __construct($details) {
            parent::__construct($details->userName, $details->passPhrase);
            $this->_setUrl($details->vendorName, $details->repoUser, $details->repositoryName);
            $this->_setIssueTitle('title', $details->title);
            $this->_setIssueBody('body', $details->body);

        }


        /**
         * Set the server url where the issue will be created for this vendor. A typical github issue format would be:<br/>
         * https://api.github.com/repos/:username/:repository-name/issues
         *
         * @param string $vendorName      The hostname of the service provider
         * @param string $repositoryOwner The person who created the target repository
         * @param string $repositoryName  The name of the target repository
         */
        private function _setUrl($vendorName, $repositoryOwner, $repositoryName) {
            $apiUrl = "https://api.$vendorName/repos/$repositoryOwner/$repositoryName/issues";
            $this->_setApiUrl($apiUrl);
        }


        /**
         * Get the data to be send to the webservice
         *
         * @return string The data to be sent, in JSON encoded form
         */
        private function _getPostData() {
            return json_encode($this->_getPostArray());
        }

        function createIssue($verbose = false) {
            $postString  = $this->_getPostData();
            $information = $this->_sendIssue($postString, $verbose);
            if ($information['http_code'] != 200) {
                throw new WebServiceException($information['result']);
            } else {
                echo $information['result'];
            }
        }

    }
