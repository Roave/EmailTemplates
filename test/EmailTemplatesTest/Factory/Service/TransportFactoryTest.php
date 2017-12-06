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

use Roave\EmailTemplates\Factory\Service\Exception\InvalidArgumentException as InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Factory\Service\Exception\ConfigurationException;
use Roave\EmailTemplates\Factory\Service\TransportFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TransportFactoryTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Factory\Service\TransportFactory
 */
class TransportFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TransportFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new TransportFactory();
    }

    /**
     * @covers ::createService
     */
    public function testCreateServiceWithSmtp()
    {
        $config = [
            'roave' => [
                'email_templates' => [
                    'transport' => [
                        'type'    => 'smtp',
                        'options' => []
                    ]
                ]
            ]
        ];

        $sl = $this->createMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($config));

        $this->factory->createService($sl);
    }

    /**
     * @covers ::createService
     */
    public function testCreateServiceWithFile()
    {
        $config = [
            'roave' => [
                'email_templates' => [
                    'transport' => [
                        'type'    => 'file',
                        'options' => []
                    ]
                ]
            ]
        ];

        $sl = $this->createMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($config));

        $this->factory->createService($sl);
    }

    /**
     * @covers ::createService
     */
    public function testCreateServiceWithSendmail()
    {
        $config = [
            'roave' => [
                'email_templates' => [
                    'transport' => [
                        'type'    => 'sendmail',
                        'options' => []
                    ]
                ]
            ]
        ];

        $sl = $this->createMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($config));

        $this->factory->createService($sl);
    }

    /**
     * @covers ::createService
     */
    public function testCreateServiceWithMissingTransport()
    {
        $this->setExpectedException(ConfigurationException::class);
        $config = [
            'roave' => [
                'email_templates' => [

                ]
            ]
        ];

        $sl = $this->createMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($config));

        $this->factory->createService($sl);
    }

    /**
     * @covers ::createService
     */
    public function testCreateServiceWithIncorrectTransport()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $config = [
            'roave' => [
                'email_templates' => [
                    'transport' => [
                        'type'    => 'nerdtrain',
                        'options' => []
                    ]
                ]
            ]
        ];

        $sl = $this->createMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->once())
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($config));

        $this->factory->createService($sl);
    }
}
