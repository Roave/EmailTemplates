<?php
/**
 * Copyright (c] 2011-2013 Antoine Hedgecock.
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
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION] HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE] ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author      Antoine Hedgecock <antoine@pmg.se>
 * @author      Jonas Eriksson <jonas@pmg.se>
 *
 * @copyright   2011-2013 Antoine Hedgecock
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 */

use Doctrine\ORM\EntityManager;
use Roave\EmailTemplates\Factory\AbstractOptionsFactory;
use Roave\EmailTemplates\Factory\Repository\TemplateRepositoryFactory;
use Roave\EmailTemplates\Factory\Service\EmailServiceFactory;
use Roave\EmailTemplates\Factory\Service\Template\Engine\TwigEngineFactory;
use Roave\EmailTemplates\Factory\Service\Template\EnginePluginManagerFactory;
use Roave\EmailTemplates\Factory\Service\Template\Listener\UpdateTemplateParametersListenerFactory;
use Roave\EmailTemplates\Factory\Service\TemplateServiceFactory;
use Roave\EmailTemplates\Factory\Service\TransportFactory;
use Roave\EmailTemplates\Factory\Validator\CanRenderValidatorFactory;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use Roave\EmailTemplates\InputFilter\TemplateInputFilter;
use Roave\EmailTemplates\Options\EmailServiceOptions;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Roave\EmailTemplates\Repository\TemplateRepository;
use Roave\EmailTemplates\Service\EmailService;
use Roave\EmailTemplates\Service\Template\Engine\EchoResponse;
use Roave\EmailTemplates\Service\Template\Engine\Twig;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Roave\EmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener;
use Roave\EmailTemplates\Service\TemplateService;
use Roave\EmailTemplates\Validator\CanRenderValidator;
use Zend\Mail\Transport\TransportInterface;

return [
    'roave' => [
        'options' => [
            TemplateServiceOptions::class => [
                'engine' => Twig::class,
            ],

            EmailServiceOptions::class => [

            ],
        ],

        'email_templates' => [
            'transport' => [
                'type'    => 'sendmail',
                'options' => []
            ],

            'engine_manager' => [
                'invokables' => [
                    EchoResponse::class => EchoResponse::class
                ],

                'factories' => [
                    Twig::class => TwigEngineFactory::class
                ]
            ]
        ]
    ],

    'service_manager' => [
        'aliases' => [
            'Roave\EmailTemplates\ObjectManager' => EntityManager::class
        ],

        'factories' => [

            // Repository
            TemplateRepository::class => TemplateRepositoryFactory::class,

            // Services
            EmailService::class        => EmailServiceFactory::class,
            TemplateService::class     => TemplateServiceFactory::class,

            // Misc
            TransportInterface::class  => TransportFactory::class,
            EnginePluginManager::class => EnginePluginManagerFactory::class,

            // Listeners
            UpdateTemplateParametersListener::class => UpdateTemplateParametersListenerFactory::class
        ],

        'abstract_factories' => [
            AbstractOptionsFactory::class
        ]
    ],

    'hydrators' => [
        'invokables' => [
            TemplateHydrator::class => TemplateHydrator::class
        ]
    ],

    'input_filters' => [
        'invokables' => [
            TemplateInputFilter::class => TemplateInputFilter::class
        ]
    ],

    'validators' => [
        'factories' => [
            CanRenderValidator::class => CanRenderValidatorFactory::class,
        ]
    ],

    'doctrine' => include __DIR__ . '/doctrine.config.php'
];
