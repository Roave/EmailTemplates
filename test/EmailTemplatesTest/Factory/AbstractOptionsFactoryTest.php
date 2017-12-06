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

namespace EmailTemplatesTest\Factory;

use PHPUnit_Framework_MockObject_MockObject;
use Roave\EmailTemplates\Factory\AbstractOptionsFactory;
use Roave\EmailTemplates\Options\EmailServiceOptions;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AbstractOptionsFactoryTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Factory\AbstractOptionsFactory
 * @covers ::<!public>
 *
 * @group factory
 */
class AbstractOptionsFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $sl;

    /**
     * @var AbstractOptionsFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->sl      = $this->createMock(ServiceLocatorInterface::class);
        $this->factory = new AbstractOptionsFactory();
    }

    /**
     * @return array
     */
    public function classes()
    {
        return [
            ['RoaveemailTemplatesoptionsfoobar', 'Roave\EmailTemplates\\Options\\FooBar', true],
            ['RoaveemailTemplatesfoobaroptions', 'Roave\EmailTemplates\\FooBar\\Options', false]
        ];
    }

    /**
     * @dataProvider classes
     * @covers ::canCreateServiceWithName
     *
     * @param string $normalizedName
     * @param string $requestedName
     * @param string $canCreate
     *
     * @return void
     */
    public function testCanCreateOptionClasses($normalizedName, $requestedName, $canCreate)
    {
        $this->assertEquals(
            $canCreate,
            $this->factory->canCreateServiceWithName($this->sl, $normalizedName,  $requestedName)
        );
    }

    /**
     * @covers ::createServiceWithName
     */
    public function testCreateServiceWithName()
    {
        $config = [
            'roave' => [
                'options' => [
                    EmailServiceOptions::class => [
                        'from' => 'awesome@roave.com'
                    ],

                    TemplateServiceOptions::class => [
                        'engine' => 'echo'
                    ]
                ]
            ]
        ];

        $sl = $this->createMock(ServiceLocatorInterface::class);
        $sl
            ->expects($this->any())
            ->method('get')
            ->with('Config')
            ->will($this->returnValue($config));

        /**
         * @var $emailOptions EmailServiceOptions
         * @var $templateOptions TemplateServiceOptions
         */
        $emailOptions    = $this->factory->createServiceWithName($sl, 'unUsedArgument', EmailServiceOptions::class);
        $templateOptions = $this->factory->createServiceWithName($sl, 'unUsedArgument', TemplateServiceOptions::class);

        $this->assertInstanceOf(TemplateServiceOptions::class, $templateOptions);
        $this->assertInstanceOf(EmailServiceOptions::class, $emailOptions);

        $this->assertEquals('awesome@roave.com', $emailOptions->getFrom());
        $this->assertEquals('echo', $templateOptions->getEngine());
    }
}
