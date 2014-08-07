<?php
/**
 * @author Jonas Rosenlind <rosenlindjonas@gmail.com>
 *
 * @copyright LokalByg APS
 */

namespace EmailTemplatesTest\Service\Template;

use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Service\Template\Engine\EngineInterface;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Roave\EmailTemplates\Service\Template\Exception\InvalidEngineException;
use stdClass;

/**
 * Class EnginePluginManagerTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Service\Template\EnginePluginManager
 * @covers ::<!public>
 *
 * @group service
 */
class EnginePluginManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EnginePluginManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new EnginePluginManager();
    }

    /**
     * @covers ::validatePlugin
     */
    public function testValidatePluginWithInvalidArgument()
    {
        $this->setExpectedException(InvalidEngineException::class);
        $this->manager->validatePlugin(new stdClass());
    }

    /**
     * @covers ::validatePlugin
     */
    public function testValidatePlugin()
    {
        $plugin = $this->getMock(EngineInterface::class);
        $this->manager->validatePlugin($plugin);
    }
}
