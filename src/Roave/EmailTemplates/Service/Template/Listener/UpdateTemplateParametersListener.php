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
 * @author Antoine Hedgecock
 *
 * @copyright 2014 Roave, LLC
 * @license http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

namespace Roave\EmailTemplates\Service\Template\Listener;

use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Repository\TemplateRepositoryInterface;
use Roave\EmailTemplates\Service\TemplateService;
use Roave\EmailTemplates\Service\TemplateServiceInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;

class UpdateTemplateParametersListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var TemplateRepositoryInterface
     */
    private $templateRepository;

    /**
     * @param TemplateRepositoryInterface $templateRepository
     */
    public function __construct(TemplateRepositoryInterface $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(TemplateService::EVENT_RENDER, [$this, 'update']);
    }

    /**
     * @param $event
     */
    public function update(Event $event)
    {
        /**
         * @var TemplateEntity $template
         * @var array|\Traversable $parameters
         * @var TemplateServiceInterface $templateService
         */
        $service    = $event->getTarget();
        $template   = $event->getParam('template');
        $parameters = $event->getParam('parameters');

        if (! $template->getUpdateParameters()) {
            return;
        }

        // fetch all the templates with the id
        $templates = $this->templateRepository->getById($template->getId());

        foreach ($templates as $t) {
            $service->update(['parameters' => $parameters, 'updateParams' => false], $t);
        }
    }
}
