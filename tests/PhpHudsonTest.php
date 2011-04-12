<?php
/**
 * Php4Hudson
 *
 * @version $Id: PhpHudsonTest.php,v 2fc95111964f 2009/07/17 14:59:27 ladislav $
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @package php4hudson
 * @category php4hudson
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 * @license  MIT http://www.opensource.org/licenses/mit-license.php
 * @link http://code.google.com/p/php4hudson/
 * @filesource
 */
/**
 * Hudson library
 */
require_once __DIR__ . '/../Php4Hudson/phphudson.php';
/**
 * Hudson config
 */
require_once __DIR__ . '/../conf/config.php';
/**
 * PhpHudsonTest
 * @package php4hudson
 * @subpackage tests
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 */
class PhpHudsonTest extends PHPUnit_Framework_TestCase
{
    /**
     * phpHudson object
     *
     * @var PhpHudson
     */
    public $hudson;
    /**
     * Config
     *
     * @var string
     */
    public $config;
    /**
     * SetUp
     *
     */
    public function setUp ()
    {
        if (! extension_loaded('curl')) {
            $this->markTestSkipped('The CURL extension is not available.');
        }
        $this->hudson = new Php4Hudson_Hudson(JENKINS_URL);
        $this->config = file_get_contents(dirname(__FILE__).'/config-expected.xml');
    }
    /**
     * TearUp
     *
     */
    public function tearUp ()
    {}
    /**
     * testCreateJob
     * @return bool
     */
    public function testCreateJob ()
    {
          $this->assertFalse($this->hudson->checkUrl(JENKINS_URL .'/job/TestCreateJob1/'), "Job not exists");
          $res = $this->hudson->createJob("TestCreateJob1", $this->config);
          $this->assertTrue($res, "Job Created");
          $res = $this->hudson->deleteJob(JENKINS_URL .'/job/TestCreateJob1/');
          $this->assertFalse($this->hudson->checkUrl(JENKINS_URL .'/job/TestCreateJob1/'), "Job not exists");
    }
    /**
     * testCopyJob
     * @return bool
     */
    public function testCopyJob ()
    {
        $res = $this->hudson->copyJob("CopyJobTest1", "PhpHudson", $this->config);
        $this->assertTrue($res, "Copy job");
    }
    /**
     * testDeleteJob
     * @return bool
     */
    public function testDeleteJob()
    {
        $res = $this->hudson->deleteJob(JENKINS_URL .'/job/CopyJobTest1/');
        $this->assertTrue($res, "Delete job");
    }
    /**
     * testSaveJobConfig
     * @return bool
     */
    public function testSaveJobConfig ()
    {
        $config = $this->hudson->getConfig(JENKINS_URL . '/job/PhpHudson/');
        file_put_contents("config-sample.xml", $config);
        // check config.xml
        $this->assertFileExists('config-sample.xml');
        // check config.xml
        $this->assertFileExists(dirname(__FILE__).'/config-expected.xml');
        // compare files
        $this->assertFileEquals('config-sample.xml', dirname(__FILE__).'/config-expected.xml');
        // delete config file
        unlink('config-sample.xml');
    }
}
