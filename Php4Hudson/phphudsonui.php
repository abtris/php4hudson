<?php
/**
 * Php4Hudson
 *
 * @version $Id: phphudsonui.php,v 0b29ee27093c 2009/07/20 13:16:04 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
/**
 * php4hudson UI
 * @package php4hudson
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 */
class php4hudsonUI {
    /**
     * searchJobs
     * @param string $host
     * @param string $username
     * @param string $password
     * @param bool $debug
     */
    public static function searchJobs($host,  $username,  $password,  $debug, $string)
    {
        $hudson = new Php4Hudson_Hudson($host, $username, $password, $debug);
        $list = $hudson->getXml("job/name[contains(.,'{$string}')]/parent::*");
        foreach ($list as $i) {
            echo $i->name."\n";
            echo $i->url."\n";
        }
        
    }

    /**
     * get Artifact
     * @param string $host
     * @param string $username
     * @param string $password
     * @param bool $debug
     * @param string $string
     */
    public static function getArtifactByName($host,  $username,  $password,  $debug, $string)
    {
        $hudson = new Php4Hudson_Hudson($host, $username, $password, $debug);
        $list = $hudson->getXml("job/name[contains(.,'{$string}')]/parent::*");
        foreach ($list as $i) {
            echo $i->name."\n";            
            $job = $hudson->getXml(null, $i->url);
            $stable = $hudson->getXml(null, $job->lastStableBuild->url);
            // artifacts
            foreach ($stable->artifact as $j) {
                if (!empty($j->relativePath)) {
                $artifact = $job->lastStableBuild->url.'/artifact/'.$j->relativePath;
                echo $artifact."\n";
                }
            }

        }
        
    }

    /**
     * Print version
     */
    public static function printVersion()
    {
        echo "Php4Hudson 1.0.0 Ladislav Prskavec\n\n";
    }
    /**
     * Print jobs list
     */
    public static function printListJobs($host, $username, $password, $debug)
    {
        $hudson = new Php4Hudson_Hudson($host, $username, $password, $debug);
        $list = $hudson->getJobsList();
        if (!empty($list)) {
            print "Jobs list:\n\n";
            foreach ($list as $i) {
                print $i."\n";
            }
        } else {
            print "No jobs available on ".$host."\n";
        }
    }
    /**
     * Get configs to directory
     */
    public static function getConfigs($host, $username, $password, $debug, $output)
    {
        $hudson = new Php4Hudson_Hudson($host, $username, $password, $debug);
        if (empty($outputPath)) $outputPath = "./";
        $hudson->getAllConfigs($outputPath);
    }
    /**
     * Hudson system functions
     *
     * @param string $switch
     */
    public static function system($host, $username, $password, $debug, $switch)
    {
        $hudson = new Php4Hudson_Hudson($host, $username, $password, $debug);
        // test switches
        switch ($switch) {
            case "restart": {
                    $hudson->restartHudson();
                }
            break;
            case "shutdown": {
                    $hudson->goingDownHudson();
            }
            break;
            case "cancel": {
                    $hudson->cancelDownHudson();
            }
            break;
        }

    }

}
