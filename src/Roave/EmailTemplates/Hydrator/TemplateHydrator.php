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

namespace Roave\EmailTemplates\Hydrator;

use Roave\EmailTemplates\Entity\TemplateEntity;
use Zend\Stdlib\Hydrator\AbstractHydrator;

class TemplateHydrator extends AbstractHydrator
{
    /**
     * Extract values from an object
     *
     * @param object $object
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return array
     */
    public function extract($object)
    {
        if (! $object instanceof TemplateEntity) {
            throw new Exception\InvalidArgumentException();
        }

        return [
            'id'               => $this->extractValue('id', $object->getId()),
            'locale'           => $this->extractValue('locale', $object->getLocale()),
            'subject'          => $this->extractValue('subject', $object->getSubject()),
            'textBody'         => $this->extractValue('textBody', $object->getTextBody()),
            'htmlBody'         => $this->extractValue('htmlBody', $object->getHtmlBody()),
            'description'      => $this->extractValue('description', $object->getDescription()),
            'parameters'       => $this->extractValue('parameters', $object->getParameters()),
            'updateParameters' => $this->extractValue('updateParameters', $object->getUpdateParameters()),

            // Dates
            'createdAt'           => $this->extractValue('createdAt', $object->getCreatedAt()),
            'updatedAt'           => $this->extractValue('updatedAt', $object->getUpdatedAt()),
            'parametersUpdatedAt' => $this->extractValue('parametersUpdatedAt', $object->getParamsUpdatedAt()),
        ];
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array  $data
     * @param object $object
     *
     * @throws Exception\InvalidArgumentException
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (! $object instanceof TemplateEntity) {
            throw new Exception\InvalidArgumentException();
        }

        $object->setLocale($this->hydrateValue('locale', $data['locale']));
        $object->setSubject($this->hydrateValue('subject', $data['subject']));
        $object->setTextBody($this->hydrateValue('textBody', $data['textBody']));
        $object->setHtmlBody($this->hydrateValue('htmlBody', $data['htmlBody']));
        $object->setDescription($this->hydrateValue('description', $data['description']));
        $object->setUpdateParameters($this->hydrateValue('updatedParameters', $data['updateParameters']));

        return $object;
    }
}
