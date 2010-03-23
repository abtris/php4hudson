<?php
/**
 * Php4Hudson
 *
 * @version $Id: phphudson.php,v 5639da364458 2009/07/21 08:45:56 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
/**
 * Php class for Hudson management via REMOTE-API
 * @package php4hudson
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 */
class Php4Hudson_Hudson {
/**
 * @var string xml for Hudson - API (REST)
 */
    private $_xml;
    /**
     * @var string $baseUrl Hudson url
     */
    public $baseUrl;
    /**
     * user for auth
     * @var string
     */
    protected $user;
    /**
     * password for auth
     * @var string
     */
    protected $password;
    /**
     * Log name
     *
     * @var string
     */
    private $_log;
    /**
     * Log File
     *
     * @var file
     */
    private $_logFile;
    /**
     * Logging
     * @var bool
     */
    private $_logging;
    /**
     * Constructor
     * @param string $baseUrl Base url of Hudson
     * http://localhost:8080/ in standalone mode
     * @param bool $logging log in file default is true
     * @return void
     */
    public function __construct ($baseUrl, $user = null, $password = null, $logging = true) {
        if (! extension_loaded('curl')) {
            throw new Exception('Extension CURL not loaded!');
            exit();
        }
        $this->_logging = $logging;
        $this->user = $user;
        $this->password = $password;
        $this->baseUrl = $baseUrl;
        // logging
        if ($this->_logging) {
            $this->_log = "error-hudson.log";
            $this->_logFile = fopen($this->_log, 'a+');
        }
    }
    /**
     * Destruct
     *
     */
    public function __destruct () {
        if ($this->_logFile) {
            fclose($this->_logFile);
        }
    }
    /**
     * Post by curl to url config.xml
     * @access private
     * @param string $url URL to post
     * @param string $config xml hudson config
     * @return bool
     */
    private function _postCurl ($url, $config) {
    // some data for post without config
        if (is_null($config))
            $config = "<?xml version='1.0' encoding='UTF-8'?><project></project>";
        // custom header
        $header[] = "Content-type: text/xml";
        $header[] = "Content-length: " . strlen($config) . "\r\n";
        $header[] = $config;
        // init curl
        $ch = curl_init();
        if ($this->_logging) {
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
        }
        if ($this->_logFile && $this->_logging) {
            curl_setopt($ch, CURLOPT_STDERR, $this->_logFile);
        }
        // URL to post to
        curl_setopt($ch, CURLOPT_URL, $url);
        // SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // return into a variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // custom headers, see above
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        // This POST is special, and uses its specified Content-type
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        //run!
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // result return 200, 302 for OK, 400, 500 for BAD
        if ($status == 200 || $status == 302) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * check URL
     * 
     * @param string $url
     * @return bool
     */
    public static function checkUrl($url) {
        // URL to post to
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // return into a variable
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // result return 200, 302 for OK, 400, 500 for BAD
        if ($status == 200 || $status == 302) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * get Last Revision
     * @param string job url
     * @return string
     */
    public function getLastRevision ($url) {
        $this->xml = simplexml_load_file($url . 'lastSuccessfulBuild/api/xml');
        return $this->xml->changeSet->revision->revision;
    }
    /**
     * Create job by REST $baseUrl.'createItem?name=$newJobName'
     * @param string $newJobName new JobName
     * @param string $config xml config file
     * @example ../examples/example2.php How to use this function
     * @return bool
     */
    public function createJob ($newJobName, $config) {
        return $this->_postCurl($this->baseUrl . 'createItem?name=' . $newJobName, $config);
    }
    /**
     * Copy job
     *
     * $baseUrl.'createItem?name=$newJobName&mode=copyJob&from=$fromJobName'
     *
     * @param string $newJobName new JobName
     * @param string $fromJobName from JobName
     * @param string $config xml config file
     * @return bool
     */
    public function copyJob ($newJobName, $fromJobName, $config) {
        return $this->_postCurl($this->baseUrl . 'createItem?name=' . $newJobName . '&mode=copyJob&from=' . $fromJobName, $config);
    }
    /**
     * Restart hudson
     * $baseUrl.'restart'
     * @return bool
     */
    public function restartHudson () {
        if ($this->_postCurl($this->baseUrl . 'restart', NULL)) {
            echo "Hudson restarted.";
        } else {
            if ($this->_postCurl($this->baseUrl . 'quietDown', NULL)) {
                echo "Hudson going down.";
            } else {
                echo "Hudson restart failed";
            }
        }
    }
    /**
     * QuitDown hudson
     * @return bool
     */
    public function goingDownHudson () {
        $this->_postCurl($this->baseUrl . 'QuietDown', NULL);
    }
    /**
     * Cancel QuitDown hudson
     * @return bool
     */
    public function cancelDownHudson () {
        $this->_postCurl($this->baseUrl . 'cancelQuietDown', NULL);
    }
    /**
     * get Config
     * @param $url job url
     * @return string config.xml
     */
    public function getConfig ($url) {
        if (empty($this->user)) {
        $config = file_get_contents($url . "/config.xml");        
        } else {
        $config = file_get_contents(str_replace("http://","http://".urlencode($this->user).":".urlencode($this->password)."@",$url) . "/config.xml");        
        }
        return $config;
    }
    /**
     * Update job
     * by POST to same url as getConfig
     * @param string $url
     * @param string $config file with config
     */
    public function updateConfig ($url, $config) {
    // TODO test
        return $this->_postCurl($url . 'config.xml', $config);
    }
    /**
     * get Jobs List
     * @param string $select null (names), url
     * @return array
     */
    public function getJobsList ($select = null) {
            // check URL
            if (self::checkUrl($this->baseUrl . 'api/xml')) {
        
                $this->_xml = simplexml_load_file($this->baseUrl . 'api/xml');
                foreach ($this->_xml->job as $i) {
                    if (is_null($select)) {
                        $jobs[] = $i->name;
                    } elseif ($select == "url") {
                        $jobs[] = $i->url;
                    } elseif ($select == "both") {
                        $jobs[] = array("name" => $i->name , "url" => $i->url);
                    }
                }

                return $jobs;
            }
    }

    /**
     * Get xml
     * @param string $xpath
     * @return SimpleXMLObject
     */
    public function getXml($xpath = null)
    {
            if (self::checkUrl($this->baseUrl . 'api/xml')) {
                $this->_xml = simplexml_load_file($this->baseUrl . 'api/xml');
                if (is_null($xpath)) {
                    return $this->_xml;
                } else {
                    return $this->_xml->xpath($xpath);
                }
            }
    }

    /**
     * get All Jobs Configs
     * @param string $dir output directory
         * @example ../examples/example.php How to use this function
     * @return void
     */
    public function getAllConfigs ($dir = null) {
        $jobs = $this->getJobsList("both");
        foreach ($jobs as $job) {
            $file = str_replace(" ", "_", $job["name"]);
            if (is_null($dir)) {
                $filename = $file;
            } else {
                file_exists($dir) ? null : mkdir($dir, 0777, true);
                $filename = $dir . $file;
            }

            $jobUrl = $job['url'];
            echo $jobUrl . "\n";
            // take baseUrl instead url for auth
            //  $pos = strpos($job['url'], '/job');
            // $jobUrl =  $this->baseUrl . substr($job['url'], $pos+1);
            $config = $this->getConfig($jobUrl, $filename, $dir);
            file_put_contents($filename . "-config.xml", $config);
            //sleep(1);
        }
    }
    /**
     * Delete job
     * $url.'doDelete?json={}&Submit=Yes'
     * @link http://www.nabble.com/how-are-you-using-Hudson-for-non-Java-projects--tt17145574.html
     *
     * @param string $url
     * @return bool
     */
    public function deleteJob ($url) {
        return $this->_postCurl($url . 'doDelete?json={}&Submit=Yes', null);
    }
    /**
     * Disable job
     * $url.'disable';
     * @param string $url
     * @return bool
     */
    public function disableJob ($url) {
        return $this->_postCurl($url . 'disable', null);
    }
    /**
     * Enable job
     * $url.'enable';
     * @param string $url
     * @return bool
     */
    public function enableJob ($url) {
        return $this->_postCurl($url . 'enable', null);
    }
    /**
     * Build job
     * $url.'build';
     * @param string $url
     * @return bool
     */
    public function buildJobs ($url) {
        return $this->_postCurl($url . 'build', null);
    }



}





