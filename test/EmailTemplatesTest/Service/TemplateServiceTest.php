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
 *
 * @copyright 2014 Roave, LLC
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace EmailTemplatesTest\Service;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Tests\Common\Annotations\Fixtures\Annotation\Template;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use Roave\EmailTemplates\InputFilter\TemplateInputFilter;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Roave\EmailTemplates\Repository\TemplateRepositoryInterface;
use Roave\EmailTemplates\Service\Exception\FailedDataValidationException;
use Roave\EmailTemplates\Service\Template\Engine\EchoResponse;
use Roave\EmailTemplates\Service\Template\Engine\EngineInterface;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Roave\EmailTemplates\Service\TemplateService;
use Zend\EventManager\EventManagerInterface;

/**
 * Class TemplateServiceTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Service\TemplateService
 *
 * @group service
 */
class TemplateServiceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $repository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $inputFilter;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $hydrator;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $engineManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventManager;

    /**
     * @var TemplateServiceOptions
     */
    protected $options;

    /**
     * @var TemplateService
     */
    protected $templateService;

    protected function setUp()
    {
        $this->objectManager = $this->getMock(ObjectManager::class);
        $this->repository    = $this->getMock(TemplateRepositoryInterface::class);
        $this->inputFilter   = $this->getMock(TemplateInputFilter::class);
        $this->hydrator      = $this->getMock(TemplateHydrator::class);
        $this->engineManager = $this->getMock(EnginePluginManager::class);
        $this->eventManager  = $this->getMock(EventManagerInterface::class);
        $this->options       = new TemplateServiceOptions();

        $this->templateService = new TemplateService(
            $this->objectManager,
            $this->repository,
            $this->inputFilter,
            $this->hydrator,
            $this->engineManager,
            $this->options
        );

        $this->templateService->setEventManager($this->eventManager);
    }

    /**
     * @covers ::render
     */
    public function testRenderCreateIfTemplateMissing()
    {
        $this->repository
            ->expects($this->once())
            ->method('getByIdAndLocale');

        $this->objectManager
            ->expects($this->once())
            ->method('persist');

        $this->engineManager
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->getMock(EngineInterface::class)));

        $this->templateService->render('helloWorld', 'en_US');
    }

    /**
     * @covers ::render
     */
    public function testRenderTriggersEvent()
    {
        $this->repository
            ->expects($this->once())
            ->method('getByIdAndLocale')
            ->will($this->returnValue(new TemplateEntity()));

        $this->eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with(TemplateService::EVENT_RENDER, $this->templateService);

        $this->engineManager
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->getMock(EngineInterface::class)));

        $this->templateService->render('helloWorld', 'en_US');
    }

    /**
     * @covers ::render
     */
    public function testCreateTriggersEvent()
    {
        $this->repository
            ->expects($this->once())
            ->method('getByIdAndLocale');

        $this->eventManager
            ->expects($this->at(0))
            ->method('trigger')
            ->with(TemplateService::EVENT_CREATE, $this->templateService);

        $this->engineManager
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($this->getMock(EngineInterface::class)));

        $this->templateService->render('helloWorld', 'en_US');
    }

    /**
     * @covers ::render
     */
    public function testRendersInProperOrder()
    {
        $template = new TemplateEntity();
        $template->setSubject('subject');
        $template->setTextBody('text');
        $template->setHtmlBody('html');

        $this->repository
            ->expects($this->once())
            ->method('getByIdAndLocale')
            ->will($this->returnValue($template));

        $this->engineManager
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue(new EchoResponse()));

        list ($subject, $html, $text) = $this->templateService->render('test', 'foo');

        $this->assertEquals($template->getSubject(), $subject);
        $this->assertEquals($template->getHtmlBody(), $html);
        $this->assertEquals($template->getTextBody(), $text);
    }

    /**
     * @covers ::update
     */
    public function testUpdateWithIncorrectData()
    {
        $this->setExpectedException(FailedDataValidationException::class);

        $this->hydrator
            ->expects($this->once())
            ->method('extract')
            ->will($this->returnValue([]));


        $this->inputFilter
            ->expects($this->once())
            ->method('getMessages')
            ->will($this->returnValue([]));

        $this->templateService->update([], new TemplateEntity());
    }

    /**
     * @covers ::update
     */
    public function testUpdate()
    {
        $data     = ['foo' => 'bar'];
        $template = $this->getMock(TemplateEntity::class);
        $template
            ->expects($this->once())
            ->method('setUpdatedAt')
            ->with($this->isInstanceOf(DateTime::class));

        $this->hydrator
            ->expects($this->once())
            ->method('extract')
            ->will($this->returnValue([]));

        $this->inputFilter
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->inputFilter
            ->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue($data));

        $this->hydrator
            ->expects($this->once())
            ->method('hydrate')
            ->with($data, $template);

        $this->objectManager
            ->expects($this->once())
            ->method('flush');

        $this->templateService->update($data, $template);
    }
}
