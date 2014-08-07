<?php
/**
 * Copyright (c) 2014 Roave, LLC.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author    Antoine Hedgecock
 * @author    Jonas Rosenlind
 *
 * @copyright 2014 Roave, LLC
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace EmailTemplatesTest\Factory\Service;

use Doctrine\Common\Persistence\ObjectManager;
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
