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

namespace Roave\EmailTemplates\Service;

use DateTime;
use Doctrine\Common\Persistence\ObjectManager;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use Roave\EmailTemplates\InputFilter\TemplateInputFilter;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Roave\EmailTemplates\Repository\TemplateRepositoryInterface;
use Roave\EmailTemplates\Service\Template\Engine\EngineInterface;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Class TemplateEntity
 */
class TemplateService implements TemplateServiceInterface
{
    use EventManagerAwareTrait;

    const EVENT_RENDER = 'render';
    const EVENT_CREATE = 'create';

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var TemplateRepositoryInterface
     */
    protected $repository;

    /**
     * @var TemplateInputFilter
     */
    protected $inputFilter;

    /**
     * @var TemplateHydrator
     */
    protected $hydrator;

    /**
     * @var Template\EnginePluginManager
     */
    private $engineManager;

    /**
     * @param ObjectManager               $objectManager
     * @param TemplateRepositoryInterface $repository
     * @param TemplateInputFilter         $inputFilter
     * @param TemplateHydrator            $hydrator
     * @param EnginePluginManager         $engineManager
     * @param TemplateServiceOptions|null $options
     */
    public function __construct(
        ObjectManager $objectManager,
        TemplateRepositoryInterface $repository,
        TemplateInputFilter $inputFilter,
        TemplateHydrator $hydrator,
        EnginePluginManager $engineManager = null,
        TemplateServiceOptions $options = null
    ) {
        $this->objectManager      = $objectManager;
        $this->repository         = $repository;
        $this->inputFilter        = $inputFilter;
        $this->hydrator           = $hydrator;
        $this->options            = $options ?: new TemplateServiceOptions();
        $this->engineManager      = $engineManager ?: new EnginePluginManager();
    }

    /**
     * Render a template
     *
     * @triggers render
     *
     * @param string                  $templateId
     * @param string                  $locale
     * @param array|\Traversable $parameters
     *
     * @return string[]
     */
    public function render($templateId, $locale, array $parameters = array())
    {
        $template = $this->repository->getByIdAndLocale($templateId, $locale);

        if (! $template) {
            $template = $this->create($templateId, $locale, $parameters);
        }

        $this->getEventManager()
            ->trigger(static::EVENT_RENDER, $this, ['template' => $template, 'parameters' => $parameters]);

        /** @var EngineInterface $engine */
        $engine = $this->engineManager->get($this->options->getEngine());

        return [
            $engine->render($template->getSubject(), $parameters),
            $engine->render($template->getHtmlBody(), $parameters),
            $engine->render($template->getTextBody(), $parameters)
        ];
    }

    /**
     * Create a new template
     *
     * @param string $templateId
     * @param string $locale
     * @param array  $parameters
     *
     * @return TemplateEntity
     */
    protected function create($templateId, $locale, array $parameters)
    {
        $template = new TemplateEntity();
        $template->setId($templateId);
        $template->setLocale($locale);
        $template->setParameters($parameters);
        $template->setSubject($this->options->getDefaultSubject());
        $template->setTextBody(sprintf($this->options->getDefaultBody(), $templateId, $locale));
        $template->setHtmlBody(sprintf($this->options->getDefaultBody(), $templateId, $locale));
        $template->setCreatedAt(new DateTime());
        $template->setUpdatedAt(new DateTime());

        $this->getEventManager()
            ->trigger(static::EVENT_CREATE, $this, ['template' => $template]);

        $this->objectManager->persist($template);
        $this->objectManager->flush();

        return $template;
    }

    /**
     * Save a template entity
     *
     * @param array          $data
     * @param TemplateEntity $template
     *
     * @throws Exception\FailedDataValidationException
     */
    public function update(array $data, TemplateEntity $template)
    {
        // Allow for delta updates
        $data = array_merge($this->hydrator->extract($template), $data);

        $this->inputFilter->setData($data);
        if (! $this->inputFilter->isValid()) {
            throw Exception\FailedDataValidationException::create($this->inputFilter->getMessages());
        }

        $values = $this->inputFilter->getValues();

        $template->setUpdatedAt(new DateTime());

        $this->hydrator->hydrate($values, $template);
        $this->objectManager->flush();
    }
}
