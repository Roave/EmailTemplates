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
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use Roave\EmailTemplates\InputFilter\TemplateInputFilter;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Roave\EmailTemplates\Repository\TemplateRepositoryInterface;
use Roave\EmailTemplates\Service\Exception\FailedDataValidationException;
use Roave\EmailTemplates\Service\Template\Engine\EngineInterface;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Roave\EmailTemplates\Service\TemplateService;
use Zend\EventManager\EventManagerInterface;

/**
 * Class TemplateServiceTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Service\TemplateService
 * @covers ::<!public>
 *
 * @group service
 */
class TemplateServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $repository;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $inputFilter;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $hydrator;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $engineManager;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
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

    /**
     * @covers ::__construct
     */
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
    public function testRender()
    {
        $locale     = 'sv_SE';
        $templateId = 'test:template:id';
        $parameters = ['company' => 'Roave'];

        $template = new TemplateEntity();
        $template->setSubject('subject');
        $template->setTextBody('text');
        $template->setHtmlBody('html');

        $engine = $this->getMock(EngineInterface::class);

        // First run is the subject
        $engine
            ->expects($this->at(0))
            ->method('render')
            ->with($template->getSubject(), $parameters)
            ->will($this->returnValue($template->getSubject()));

        // Second run is the html body
        $engine
            ->expects($this->at(1))
            ->method('render')
            ->with($template->getHtmlBody(), $parameters)
            ->will($this->returnValue($template->getHtmlBody()));

        // Third run is the text body
        $engine
            ->expects($this->at(2))
            ->method('render')
            ->with($template->getTextBody(), $parameters)
            ->will($this->returnValue($template->getTextBody()));

        $this->repository
            ->expects($this->once())
            ->method('getByIdAndLocale')
            ->with($templateId, $locale)
            ->will($this->returnValue($template));

        $this->eventManager
            ->expects($this->once())
            ->method('trigger')
            ->with(
                TemplateService::EVENT_RENDER,
                $this->templateService,
                [
                    'template'   => $template,
                    'parameters' => $parameters
                ]
            );

        $this->engineManager
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($engine));

        list ($subject, $html, $text) = $this->templateService->render($templateId, $locale, $parameters);

        $this->assertEquals($template->getSubject(), $subject);
        $this->assertEquals($template->getHtmlBody(), $html);
        $this->assertEquals($template->getTextBody(), $text);
    }

    /**
     * @covers ::render
     * @covers ::create
     */
    public function testRenderWithMissingTemplate()
    {
        $locale     = 'sv_SE';
        $templateId = 'test:template:id';
        $parameters = ['company' => 'Roave'];

        $template = new TemplateEntity();
        $template->setSubject($this->options->getDefaultSubject());
        $template->setTextBody(sprintf($this->options->getDefaultBody(), $templateId, $locale));
        $template->setHtmlBody(sprintf($this->options->getDefaultBody(), $templateId, $locale));

        $engine = $this->getMock(EngineInterface::class);

        // First run is the subject
        $engine
            ->expects($this->at(0))
            ->method('render')
            ->with($template->getSubject(), $parameters)
            ->will($this->returnValue($template->getSubject()));

        // Second run is the html body
        $engine
            ->expects($this->at(1))
            ->method('render')
            ->with($template->getHtmlBody(), $parameters)
            ->will($this->returnValue($template->getHtmlBody()));

        // Third run is the text body
        $engine
            ->expects($this->at(2))
            ->method('render')
            ->with($template->getTextBody(), $parameters)
            ->will($this->returnValue($template->getTextBody()));

        $this->repository
            ->expects($this->once())
            ->method('getByIdAndLocale')
            ->with($templateId, $locale)
            ->will($this->returnValue(null));

        $this->eventManager
            ->expects($this->at(0))
            ->method('trigger')
            ->with(TemplateService::EVENT_CREATE, $this->templateService, $this->callback(function ($parameters) {
                return ($parameters['template'] instanceof TemplateEntity);
            }));

        $this->eventManager
            ->expects($this->at(1))
            ->method('trigger')
            ->with(TemplateService::EVENT_RENDER);

        $this->engineManager
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue($engine));

        list ($subject, $html, $text) = $this->templateService->render($templateId, $locale, $parameters);

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
