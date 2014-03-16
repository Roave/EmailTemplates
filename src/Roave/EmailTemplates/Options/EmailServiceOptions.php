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

namespace Roave\EmailTemplates\Options;

use Zend\Mail\Transport\Sendmail;
use Zend\Stdlib\AbstractOptions;

/**
 * Class EmailServiceOptions
 *
 * A list of available configuration options for the EmaiService {@see \Roave\EmailTemplates\Service\EmailService}
 *
 * @property array  $bcc
 * @property string $defaultTransport
 * @property string $from
 * @property string $replyTo
 * @property string $encoding
 * @property string $defaultLocale
 */
class EmailServiceOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $bcc = array();

    /**
     * @var string
     */
    protected $defaultTransport = Sendmail::class;

    /**
     * @var string|null
     */
    protected $from = 'webmaster@localhost';

    /**
     * @var string|null
     */
    protected $replyTo = 'noreply@localhost';

    /**
     * @var string
     */
    protected $encoding = 'utf-8';

    /**
     * @var string
     */
    protected $defaultLocale = 'en-US';

    /**
     * @param string $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = (string) $defaultLocale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @param array $bcc
     */
    public function setBcc(array $bcc)
    {
        $this->bcc = $bcc;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * @param string $defaultTransport
     */
    public function setDefaultTransport($defaultTransport)
    {
        $this->defaultTransport = (string) $defaultTransport;
    }

    /**
     * @return string
     */
    public function getDefaultTransport()
    {
        return $this->defaultTransport;
    }

    /**
     * @param $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = (string) $encoding;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param $from
     */
    public function setFrom($from)
    {
        $this->from = (string) $from;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param $replyTo
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = (string) $replyTo;
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }
}
