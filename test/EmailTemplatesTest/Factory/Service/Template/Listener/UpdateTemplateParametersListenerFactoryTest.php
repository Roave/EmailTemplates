<?php
/**
 * @author Jonas Rosenlind <rosenlindjonas@gmail.com>
 *
 * @copyright LokalByg APS
 */

namespace EmailTemplatesTest\Factory\Service\Template\Listener;


use EmailTemplatesTest\Repository\TemplateRepositoryTest;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Factory\Service\Template\Listener\UpdateTemplateParametersListenerFactory;
use Roave\EmailTemplates\Repository\TemplateRepository;
use Roave\EmailTemplates\Repository\TemplateRepositoryInterface;
use Roave\EmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UpdateTemplateParametersListenerFactoryTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Factory\Service\Template\Listener\UpdateTemplateParametersListenerFactory
 * @covers ::<!public>
 *
 * @group factory
 */
class UpdateTemplateParametersListenerFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var UpdateTemplateParametersListenerFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new UpdateTemplateParametersListenerFactory();
    }

    /**
     * @covers ::createService
     */
    public function testCreateService()
    {
        $sl = $this->getMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->once())
            ->method('get')
            ->with(TemplateRepository::class)
            ->will($this->returnValue($this->getMock(TemplateRepositoryInterface::class)));

        $this->assertInstanceOf(UpdateTemplateParametersListener::class, $this->factory->createService($sl));
    }
}
