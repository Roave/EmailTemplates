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

namespace EmailTemplatesTest\Service\Template\Listener;

use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Repository\TemplateRepositoryInterface;
use Roave\EmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener;
use Roave\EmailTemplates\Service\TemplateService;
use Roave\EmailTemplates\Service\TemplateServiceInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

/**
 * Class UpdateTemplateParametersListenerTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener
 *
 * @group service
 */
class UpdateTemplateParametersListenerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateRepository;

    /**
     * @var UpdateTemplateParametersListener
     */
    protected $template;

    /**
     * @covers ::__construct
     */
    public function setUp()
    {
        $this->templateRepository = $this->getMock(TemplateRepositoryInterface::class);

        $this->template = new UpdateTemplateParametersListener($this->templateRepository);
    }

    /**
     * @covers ::attach
     */
    public function testAttach()
    {
        $events = $this->getMock(EventManagerInterface::class);
        $events
            ->expects($this->once())
            ->method('attach')
            ->with(TemplateService::EVENT_RENDER, $this->template, [$this->template, 'update']);

        $this->template->attach($events);
    }

    /**
     * @covers ::update
     */
    public function testUpdateIfStatement()
    {
        $template = new TemplateEntity();
        $template->setId(1337);
        $template->setUpdateParameters(false);

        $service = $this->getMock(templateServiceInterface::class);
        $event = new Event(TemplateService::EVENT_RENDER, $service, ['template' => $template, 'parameters' => []]);

        $this->template->update($event);
    }

    /**
     * @covers ::update
     */
    public function testUpdate()
    {
        $template = new TemplateEntity();
        $template->setId(1337);
        $template->setUpdateParameters(true);

        $service = $this->getMock(templateServiceInterface::class);
        $service
            ->expects($this->once())
            ->method('update')
            ->with(['parameters' => [], 'updateParams' => false]);

        $event = new Event(TemplateService::EVENT_RENDER, $service, ['template' => $template, 'parameters' => []]);

        $this->templateRepository
            ->expects($this->once())
            ->method('getById')
            ->with($template->getId())
            ->will($this->returnValue([$template]));

        $this->template->update($event);
    }
} 
