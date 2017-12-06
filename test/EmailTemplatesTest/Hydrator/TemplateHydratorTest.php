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

namespace EmailTemplatesTest\Hydrator;

use DateTime;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Entity\TemplateEntity;
use Roave\EmailTemplates\Hydrator\Exception\InvalidArgumentException;
use Roave\EmailTemplates\Hydrator\TemplateHydrator;
use stdClass;

/**
 * Class TemplateHydratorTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Hydrator\TemplateHydrator
 * @covers ::<!public>
 *
 * @group unit
 */
class TemplateHydratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateHydrator
     */
    protected $hydrator;

    protected function setUp()
    {
        $this->hydrator = new TemplateHydrator();
    }

    /**
     * @covers ::hydrate
     */
    public function testHydrateThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            sprintf('Object must be an instance of %s', TemplateEntity::class)
        );

        $this->hydrator->hydrate([], new stdClass());
    }

    /**
     * @covers ::extract
     */
    public function testExtractThrowsInvalidArgumentException()
    {
        $this->setExpectedException(
            InvalidArgumentException::class,
            sprintf('Object must be an instance of %s', TemplateEntity::class)
        );

        $this->hydrator->extract(new stdClass());
    }

    /**
     * Helper for setting properties for a template
     * @return TemplateEntity
     */
    private function getTemplate()
    {
        $date = new DateTime('2011-01-01');

        $template = new TemplateEntity();
        $template->setId(1);
        $template->setUuid('123');
        $template->setLocale('uk-US');
        $template->setSubject('42');
        $template->setTextBody('Body made of text');
        $template->setHtmlBody('Body made of html');
        $template->setDescription('This describes everything');
        $template->setParameters(['foo' => 'bar']);
        $template->setUpdateParameters('true');

        $template->setCreatedAt($date);
        $template->setUpdatedAt($date);
        $template->setparametersUpdatedAt($date);

        return $template;
    }

    /**
     * Helper for testing hydration and extraction
     *
     * @return array
     */
    private function getExtractedData()
    {
        $dateTime = new DateTime('2011-01-01');

        return [
            'id'                  => 1,
            'uuid'                => '123',
            'locale'              => 'uk-US',
            'subject'             => '42',
            'textBody'            => 'Body made of text',
            'htmlBody'            => 'Body made of html',
            'description'         => 'This describes everything',
            'parameters'          => ['foo' => 'bar'],
            'updateParameters'   => true,

            'createdAt'           => $dateTime,
            'updatedAt'           => $dateTime,
            'parametersUpdatedAt' => $dateTime
        ];
    }

    /**
     * @covers ::extract
     */
    public function testExtract()
    {
        $result = $this->hydrator->extract($this->getTemplate());

        // Assert that the extracted data matches the response.
        $this->assertEquals($this->getExtractedData(), $result);
    }

    /**
     * @covers ::hydrate
     */
    public function testHydrate()
    {
        $data = $this->getExtractedData();

        $template = $this->createMock(TemplateEntity::class);

        $expectedProperties = [
            'setSubject'             => 'subject',
            'setTextBody'            => 'textBody',
            'setHtmlBody'            => 'htmlBody',
            'setDescription'         => 'description',
            'setUpdateParameters'    => 'updateParameters',
        ];

        foreach ($expectedProperties as $method => $property) {

            $template
                ->expects($this->once())
                ->method($method)
                ->with($data[$property]);
        }

        $this->hydrator->hydrate($data, $template);
    }
}
