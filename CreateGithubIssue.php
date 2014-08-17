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

        private $_issueBodyName;
        private $_issueTitleName;

        function __construct($details) {
            parent::__construct($details->userName, $details->passPhrase, $details->url, $details->repositoryName);
            $this->_setIssueTitleName('title');
            $this->_setIssueTitle($details->title);
            $this->_setIssueBodyName('body');
            $this->_setIssueBody($details->body);

        }

        function _setIssueTitleName($titleName) {
            $this->_issueTitleName = $titleName;
        }

        function _setIssueTitle($title) {
            parent::_setIssueTitle($title);
        }

        function _setIssueBodyName($bodyName) {
            $this->_issueBodyName = $bodyName;
        }

        function _setIssueBody($body) {
            parent::_setIssueBody($body);
        }

        /**
         * Get the data to be send to the webservice
         *
         * @return string The data to be sent, in JSON encoded form
         */
        private function _getPostData() {
            return json_encode(parent::getPostData($this->_issueTitleName, $this->_issueBodyName));
        }

        public function createIssue() {
            $postString  = $this->_getPostData();
            $information = parent::createIssue($postString, false);
            if ($information['http_code'] != 200) {
                throw new WebServiceException($information['result']);
            } else {
                echo $information['result'];
            }
        }

    }
