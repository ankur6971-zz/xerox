<?php

    /**
     * Class Common
     *
     * Handles the common validations for the application
     */
    class Common {

        const NUMBER_OF_ARGS = 8;

        /**
         * Check if a given string is empty or not.
         *
         * @param string $string The target string
         *
         * @return bool true if empty, false if not.
         */
        public static function checkEmpty($string) {
            if (strlen($string) == 0) {
                return true;
            }

            return false;
        }


        /**
         * Check if the invocation agent is the commandline or not
         *
         * @return bool true if yes, false otherwise
         */
        public static function checkCli() {
            if (php_sapi_name() == 'cli') {
                return true;
            }

            return false;
        }

        /**
         * Check if curl is available
         *
         * @return bool false if not, true otherwise
         */
        public static function checkCurlAvailable() {
            if (!function_exists('curl_init')) {
                return false;
            }

            return true;
        }
    }