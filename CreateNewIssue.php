<?php

    class CreateNewIssue {

        private $vendor;
        private $userName;
        private $passPhrase;
        private $apiUrl;
        private $repository;
        private $issueTitle;
        private $issueTitleName;
        private $issueBody;
        private $issueBodyName;

        function __construct($vendor, $userName, $passPhrase, $url, $repositoryName, $title, $body) {
            $this->setVendor($vendor);
            $this->setUserName($userName);
            $this->setPassPhrase($passPhrase);
            $this->setUrl($url);
            $this->setRepository($repositoryName);
            $this->setIssueTitleName('title');
            $this->setIssueTitle($title);
            $this->setIssueBodyName($body);
            $this->setIssueBody($body);
        }

        private function setVendor($vendor) {
            $this->vendor = $vendor;
        }

        private function setUserName($userName) {
            $this->userName = $userName;
        }

        private function setPassPhrase($passPhrase) {
            $this->passPhrase = $passPhrase;
        }

        private function setUrl($url) {
            $this->apiUrl = $url;
        }

        private function setRepository($repositoryName) {
            $this->repository = $repositoryName;
        }

        private function requestType() {
            if (preg_match('/\bbitbucket\b/i', $this->vendor)) {
                $this->setIssueBodyName('content');

                return 'url';
            }

            $this->setIssueBodyName('body');

            return 'json';
        }

        private function setIssueTitle($title) {
            if ($this->requestType() == 'url') {
                $this->issueTitle = urlencode($title);
            } else {
                $this->issueTitle = $title;
            }
        }


        private function setIssueBody($body) {
            if ($this->requestType() == 'url') {
                $this->issueBody = urlencode($body);
            } else {
                $this->issueBody = $body;
            }
        }

        private function setIssueTitleName($titleName) {
            $this->issueTitleName = $titleName;
        }

        private function setIssueBodyName($bodyName) {
            $this->issueBodyName = $bodyName;
        }

        private function getPostString() {
            $postString = array(
                $this->issueTitleName => $this->issueTitle,
                $this->issueBodyName  => $this->issueBody
            );
            if ($this->requestType() == 'json') {
                $postString = json_encode($postString);

                return $postString;
            }

            $string = '';
            foreach ($postString as $postIndex => $postValue) {
                $string .= $postIndex . '=' . $postValue . '&';
            }

            return rtrim($string, '&');
        }

        public function createIssue() {
            $postString = $this->getPostString();
            $userName   = $this->userName . ':' . $this->passPhrase;
            $curl       = curl_init();
            curl_setopt($curl, CURLOPT_USERAGENT, 'Ankur-Kumar-Laptop');
//            curl_setopt($curl, CURLOPT_HEADER, 'Content-type: application/json');
            curl_setopt($curl, CURLOPT_URL, $this->apiUrl);
            curl_setopt($curl, CURLOPT_USERPWD, $userName);
            curl_setopt($curl, CURLOPT_POST, 2);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postString);
            curl_setopt($curl, CURLOPT_VERBOSE, true);
            $result = curl_exec($curl);
            curl_close($curl);
        }


    }
