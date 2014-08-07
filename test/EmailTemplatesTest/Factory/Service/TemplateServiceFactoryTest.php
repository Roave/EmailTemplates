<?php
/**
 * @author    Jonas Rosenlind <rosenlindjonas@gmail.com>
 *
 * @copyright LokalByg APS
 */

namespace EmailTemplatesTest\Factory\Service;

use Doctrine\Common\Persistence\ObjectManager;
use EmailTemplatesTest\InputFilter\TemplateInputFilterTest;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Factory\Service\TemplateServiceFactory;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use Roave\EmailTemplates\InputFilter\TemplateInputFilter;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Roave\EmailTemplates\Repository\TemplateRepository;
use Roave\EmailTemplates\Repository\TemplateRepositoryInterface;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Roave\EmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener;
use Roave\EmailTemplates\Service\TemplateService;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\InputFilter\InputFilterPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

/**
 * Class TemplateServiceFactoryTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Factory\Service\TemplateServiceFactory
 * @covers ::<!public>
 */
class TemplateServiceFactoryTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var TemplateServiceFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new TemplateServiceFactory();
    }

    /**
     * @covers ::createService
     */
    public function testCreateService()
    {
        $templateRepository = $this->getMock(TemplateRepositoryInterface::class);

        $inputFilterManager = $this->getMock(InputFilterPluginManager::class);
        $inputFilterManager
            ->expects($this->once())
            ->method('get')
            ->with(TemplateInputFilter::class)
            ->will($this->returnValue($this->getMock(TemplateInputFilter::class)));

        $hydratorManager = $this->getMock(HydratorPluginManager::class);
        $hydratorManager
            ->expects($this->once())
            ->method('get')
            ->with(TemplateHydrator::class)
            ->will($this->returnValue($this->getMock(TemplateHydrator::class)));

        $sl = $this->getMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->at(0))
            ->method('get')
            ->with('Roave\EmailTemplates\ObjectManager')
            ->will($this->returnValue($this->getMock(ObjectManager::class)));

        $sl
            ->expects($this->at(1))
            ->method('get')
            ->with(TemplateRepository::class)
            ->will($this->returnValue($templateRepository));

        $sl
            ->expects($this->at(2))
            ->method('get')
            ->with('inputFilterManager')
            ->will($this->returnValue($inputFilterManager));

        $sl
            ->expects($this->at(3))
            ->method('get')
            ->with('hydratorManager')
            ->will($this->returnValue($hydratorManager));

        $sl
            ->expects($this->at(4))
            ->method('get')
            ->with(EnginePluginManager::class)
            ->will($this->returnValue($this->getMock(EnginePluginManager::class)));

        $sl
            ->expects($this->at(5))
            ->method('get')
            ->with(TemplateServiceOptions::class)
            ->will($this->returnValue($this->getMock(TemplateServiceOptions::class)));

        $sl
            ->expects($this->at(6))
            ->method('get')
            ->with(UpdateTemplateParametersListener::class)
            ->will($this->returnValue($this->getMock(ListenerAggregateInterface::class)));

        $service = $this->factory->createService($sl);

        $this->assertInstanceOf(TemplateService::class, $service);
    }
}
