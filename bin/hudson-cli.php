#!/usr/bin/env php
<?php
/**
 * Php4Hudson
 *
 * @version $Id: hudson-cli.php,v 40c270f31707 2009/07/20 12:56:35 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors',true); 
date_default_timezone_set('Europe/Prague');
/**
 * Zend GetOpt
 */
require_once('Zend/Console/Getopt.php');
require_once('Zend/Console/Getopt/Exception.php');
/**
 * Command line client for php4hudson
 */
require_once (__DIR__ ."/../Php4Hudson/phphudson.php");
require_once (__DIR__ ."/../Php4Hudson/phphudsonui.php");
/**
 * Config
 */
if (file_exists(__DIR__ . '/../conf/config.php')) {
	require_once(__DIR__. '/../conf/config.php');
}
/**
 * Try  processing Zend_Console_Getopt
 */
try {
    $opts = new Zend_Console_Getopt(
        array(
        'help'    => 'Displays usage information.',
        'version|v' => 'Version',
        'username|u=s'=> 'Username',
        'password|p=s'=> 'password',
        'host|h=s'    => 'Hudson URL',
        'restart|r'   => 'restart Hudson',
        'output|o=s'=> 'output path',
        'configs|c'   => 'get Hudson Configs',
        'jobs|j'      => 'print jobs list',
        'search|s=s'    => 'search job',
        'artifact|a=s'   => 'get artifacts for job',
        'debug|d'     => 'debug'
        )
    );
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    exit($e->getMessage() ."\n\n". $e->getUsageMessage());
}
// start processing ops
// help + without arguments
if (count($opts->toArray())==0 || isset($opts->help)) {
    php4hudsonUI::printVersion();
    echo $opts->getUsageMessage();
    exit;
}
// version
if (isset($opts->v)) {
    php4hudsonUI::printVersion();
    echo $opts->getUsageMessage();
}
// username
if (isset($opts->u)) {
    $username = $opts->u;
} else {
    $username = null;
}
// passwod
if (isset($opts->p)) {
    $password = $opts->p;
} else {
    $password = null;
}
// host
if (isset($opts->h)) {
    $host = $opts->h;
} elseif (defined("JENKINS_URL")) {
	$host = JENKINS_URL;
} else {
    print "Error url to Hudson must be set!\n";
    exit;
}
// debug
if (isset($opts->d)) {
    $debug = true;
} else {
    $debug = false;
}
// output
if (isset($opts->o)) {
    $output = $opts->o;
} else {
    $output = null;
}
// jobs
if (isset($opts->j)) {
    php4hudsonUI::printListJobs($host, $username, $password, $debug);    
}
// search
if (isset($opts->s)) {
    $string = $opts->s;
    php4hudsonUI::searchJobs($host, $username, $password, $debug, $string);
}
// artifact
if (isset($opts->a)) {
    $string = $opts->a;
    php4hudsonUI::getArtifactByName($host, $username, $password, $debug, $string);
}
// config
if (isset($opts->c)) {
    php4hudsonUI::getConfigs($host, $username, $password, $debug, $output);
}
// restart
if (isset ($opts->r)) {
    php4hudsonUI::system($host, $username, $password, $debug, "restart");
}
// end processing ops

