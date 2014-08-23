Xerox and Ankur
=====

Xerox Code Challenge - August 2014
-----

### Objective ######
To post issues to bitbucket and github using PHP

### Installation / Software Requirements ######
You need to have the PHP5-Curl library installed for the application to function.
To be able to use the application, you need to checkout all the files (mentioned under the purpose section below) from github to your local system.

### Correct Usage ######
./create-issue -u jdoe -p secret https://bitbucket.org/:user/:repo-name "Another issue title" "Here's some more reproduction steps"
OR
./create-issue -u jdoe -p secret https://github.com/:user/:repo-name "Another issue title" "Here's some more reproduction steps"

### Validations that have been made ######
> * username follows immediately after "-u"
> * password follows immediately after "-p"
> * url should contain:
>> * http or https and followed by a :,
>> * //
>> * combination of any number of alphabets, /, ., -
> * issue title and issue body are mandatory

## Purpose of the files
> * create-issue : The file that is executed from the commandline.
> * CreateIssue.php: The basic features in the engine behind the issue creation
> * CreateBitbucketIssue.php: The features special to BitBucket issue
> * CreateGithubIssue.php: The features special to Github issue
> * Exceptions.php: The features special to BitBucket issue
> * Validate.php: The class that contains the common validations and data processing algorithm
