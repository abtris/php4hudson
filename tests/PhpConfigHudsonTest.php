<?php
/**
 * Php4Hudson
 *
 * @version $Id: PhpConfigHudsonTest.php,v 2fc95111964f 2009/07/17 14:59:27 ladislav $
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
require_once dirname(__FILE__) . '/../Php4Hudson/phphudson.php';
/**
 * Hudson config library
 */
require_once dirname(__FILE__) . '/../Php4Hudson/phpconfighudson.php';
/**
 * PhpConfigHudsonTest
 * @package php4hudson
 * @subpackage tests
 * @author Ladislav Prskavec <ladislav.prskavec@gmail.com>
 * @copyright Copyright (c) 2009, Ladislav Prskavec (http://ladislav.prskavec.net)
 */
class PhpConfigHudsonTest extends PHPUnit_Framework_TestCase
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
    }
    /**
     * TearUp
     *
     */
    public function tearUp ()
    {}
}
