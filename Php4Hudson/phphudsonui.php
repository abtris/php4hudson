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
