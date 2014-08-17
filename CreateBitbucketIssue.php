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

        private $_issueBodyName;
        private $_issueTitleName;

        function __construct($details) {
            parent::__construct($details->userName, $details->passPhrase, $details->url, $details->repositoryName);
            $this->_setIssueTitleName('title');
            $this->_setIssueTitle($details->title);
            $this->_setIssueBodyName('content');
            $this->_setIssueBody($details->body);

        }

        function _setIssueTitleName($titleName) {
            $this->_issueTitleName = $titleName;
        }

        function _setIssueTitle($title) {
            parent::_setIssueTitle($title, true);
        }

        function _setIssueBodyName($bodyName) {
            $this->_issueBodyName = $bodyName;
        }

        function _setIssueBody($body) {
            parent::_setIssueBody($body, true);
        }

        /**
         * Get the data to be sent to the webservice.
         *
         * @return string The data to be sent, in url encoded form
         */
        private function _getPostData() {
            $postString = parent::getPostData($this->_issueTitleName, $this->_issueBodyName);
            $string     = '';
            foreach ($postString as $postIndex => $postValue) {
                $string .= $postIndex . '=' . $postValue . '&';
            }

            return rtrim($string, '&');
        }

        public function createIssue() {
            $postString  = $this->_getPostData();
            $information = parent::createIssue($postString, false);
            if ($information['http_code'] != 200) {
                $errorMessage = array("message" => "There was an error processing your request. Please retry.");
                throw new WebServiceException(json_encode($errorMessage));
            } else {
                echo $information['result'];
            }
        }

    }
