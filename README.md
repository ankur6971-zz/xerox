Xerox and Ankur
=====

Xerox Code Challenge - August 2014
-----

### Objective ######
To post issues to bitbucket and github using PHP

### Installation / Software Requirements ######
You need to have the PHP5-Curl library installed for the application to function

### Correct Usage ######
./create-issue -u jdoe -p secret https://bitbucket.org/example/test "Another issue title" "Here's some more reproduction steps"

### Validations that have been made ######
> * username follows immediately after "-u"
> * password follows immediately after "-p"
> * url should contain:
>> * http or https and followed by a :,
>> * //
>> * combination of any number of alphabets, /, ., -
>> * the word 'issues' (as both the urls given in the task had issues in their url)
> * issue title and issue body are mandatory

## Purpose of the files
> * create-issue : The file that is executed from the commandline.
> * Common.php: The class that contains the common validations
> * CreateNewIssue.php: The engine behind the issue creation
