# File Adaptor

File adaptor that read from a given CSV file and writes data into an output file according to different writter specifications.

## Requirements
(>=) PHP 5.3
- Make use of SPL (Standard PHP Library) -> in the php core from 5.3
- Type hinting array from 5.1
- XmlWritter from 5.1.2
- php libxml should not be disabled

## Installation

###### GitHub
- git clone https://github.com/bitzyk/testParser.git

###### Composer

Add packet in composer.json file:
<pre>
{
  "require" : {
		"bitzyk/test-parser": "dev-master"
	}
}
</pre>

and then:
<pre>
php composer install
</pre>
or
<pre>
php composer update
</pre>

###### ZIP
- download zip archieve and unzip on you pc

## Run

###### CLI
<pre>
php {installationDir}/testParser/FileAdaptor/fileAdaptorBootstrap.php {outputFormat}
</pre>

###### HTTP
<pre>
access {serverName}/testParser/FileAdaptor/fileAdaptorBootstrap.php 
</pre>

## Options

###### Output Format
-  xml or html

## Personal Note:
- My main focus on this test was for the script to be able to process very large files. So, to achieve this, I developed an architecture which favored memory usage at the expense of cpu usage.
- In the same time i wanted to keep the code in a very easy to read form and to scale for future modification.
