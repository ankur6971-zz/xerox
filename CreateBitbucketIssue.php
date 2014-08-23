<?php
    require_once 'CreateIssue.php';

    /**
     * Class CreateBitbucketIssue
     *
     * Create an issue for the BitBucket repository located at url:
     * https://bitbucket.org/api/1.0/repositories/:user/:repo/issues
     *
     * @author Ankur Kumar <ankur6971@gmail.com>
     */
    class CreateBitbucketIssue extends CreateIssue {

        function __construct($details) {
            parent::__construct($details->userName, $details->passPhrase);
            $this->_setUrl($details->vendorName, $details->repoUser, $details->repositoryName);
            $this->_setIssueTitle('title', $details->title, true);
            $this->_setIssueBody('content', $details->body, true);
        }


        /**
         * Set the server url where the issue will be created for this vendor. A typical bitbucket issue format would be:<br/>
         * https://bitbucket.org/api/1.0/repositories/:username/:repository-name/issues
         *
         * @param string $vendorName      The hostname of the service provider
         * @param string $repositoryOwner The person who created the target repository
         * @param string $repositoryName  The name of the target repository
         */
        private function _setUrl($vendorName, $repositoryOwner, $repositoryName) {
            $apiUrl = "https://$vendorName/api/1.0/repositories/$repositoryOwner/$repositoryName/issues";
            $this->_setApiUrl($apiUrl);
        }


        /**
         * Get the data to be sent to the webservice.
         *
         * @return string The data to be sent, in url encoded form
         */
        private function _getPostData() {
            $postString = $this->_getPostArray();
            $string     = '';
            foreach ($postString as $postIndex => $postValue) {
                $string .= $postIndex . '=' . $postValue . '&';
            }

            return rtrim($string, '&');
        }

        function createIssue($verbose = false) {
            $postString  = $this->_getPostData();
            $information = $this->_sendIssue($postString, $verbose);
            if ($information['http_code'] != 200) {
                $errorMessage = array("message" => "There was an error processing your request. Please retry.");
                throw new WebServiceException(json_encode($errorMessage));
            } else {
                echo $information['result'];
            }
        }

    }
