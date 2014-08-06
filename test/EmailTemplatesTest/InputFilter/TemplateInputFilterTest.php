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
namespace EmailTemplatesTest\InputFilter;

use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\InputFilter\TemplateInputFilter;
use Zend\Validator\NotEmpty;

/**
 * Class TemplateInputFilterTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\InputFilter\TemplateInputFilter
 * @covers ::<!public>
 *
 * @group inputFilter
 */
class TemplateInputFilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateInputFilter
     */
    protected $inputFilter;

    /**
     * @covers ::init
     */
    protected function setUp()
    {
        $this->inputFilter = new TemplateInputFilter();
        $this->inputFilter->init();
    }

    /**
     * Data provider for {@see testValidationOfEachProperty}
     *
     * @return array
     */
    public function propertyValidationData()
    {
        return [
            ['updateParameters', '', [NotEmpty::IS_EMPTY]],                      // Empty
            ['updateParameters', true, null, true],                              // Valid
            ['updateParameters', 'true', null, true],                            // Valid

            ['parameters', '', [NotEmpty::IS_EMPTY]],                            // Empty
            ['parameters', 'Param', null, 'Param'],                              // Valid

            ['subject', '', [NotEmpty::IS_EMPTY]],                               // Empty
            ['subject', ' ', [NotEmpty::IS_EMPTY]],                              // Empty, asserting StringTrim
            ['subject', 'Test Subject', null, 'Test Subject'],                   // Valid
            ['subject', ' Test Subject   ', null, 'Test Subject'],               // Valid, asserting StringTrim

            ['textBody', '', [NotEmpty::IS_EMPTY]],                              // Empty
            ['textBody', ' ', [NotEmpty::IS_EMPTY]],                             // Empty, asserting StringTrim
            ['textBody', 'Test Body', null, 'Test Body'],                        // Valid
            ['textBody', ' Test Body   ', null, 'Test Body'],                    // Valid, asserting StringTrim

            ['htmlBody', '', [NotEmpty::IS_EMPTY]],                              // Empty
            ['htmlBody', ' ', [NotEmpty::IS_EMPTY]],                             // Empty, asserting StringTrim
            ['htmlBody', 'Test Html', null, 'Test Html'],                        // Valid
            ['htmlBody', ' Test Html   ', null, 'Test Html'],                    // Valid, asserting StringTrim

            ['description', '', null, ''],                                       // Empty, still valid
            ['description', ' ', null, ''],                                      // Empty, asserting StringTrim
            ['description', 'Test description', null, 'Test description'],       // Valid
            ['description', ' Test description ', null, 'Test description'],     // Valid, asserting StringTrim

        ];
    }

    /**
     * @dataProvider propertyValidationData
     *
     * @param string      $property
     * @param string      $value
     * @param array|null  $errors
     * @param string|null $expected
     *
     * @return void
     */
    public function testValidationOfEachProperty($property, $value, array $errors = null, $expected = null)
    {
        // We only validate against the given property
        $this->inputFilter->setValidationGroup([$property]);
        $this->inputFilter->setData([$property => $value]);

        // we expect errors
        if (! empty($errors)) {

            $this->assertFalse($this->inputFilter->isValid());
            $this->assertEquals($errors, array_keys($this->inputFilter->getMessages()[$property]));
        } else {

            $this->assertTrue($this->inputFilter->isValid());
            $this->assertSame($expected, $this->inputFilter->getValue($property));
        }
    }
}
