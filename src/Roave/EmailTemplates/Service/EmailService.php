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

use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Message as MailMessage;
use Zend\Mail\Transport\TransportInterface;
use Zend\Stdlib\ArrayUtils;
use Roave\EmailTemplates\Options\EmailServiceOptions;

/**
 * Class EmailService
 */
class EmailService implements EmailServiceInterface
{
    /**
     * @var EmailServiceOptions
     */
    protected $options;

    /**
     * @var TemplateServiceInterface
     */
    protected $templates;

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @param TemplateServiceInterface $templates
     * @param EmailServiceOptions      $options
     */
    public function __construct(TemplateServiceInterface $templates, EmailServiceOptions $options = null)
    {
        $this->options   = $options ?: new EmailServiceOptions();
        $this->templates = $templates;
    }

    /**
     * @param TransportInterface $transport
     */
    public function setTransport(TransportInterface $transport = null)
    {
        $this->transport = $transport;
    }

    /**
     * Get the mail transport
     *
     * @return TransportInterface
     */
    public function getTransport()
    {
        if ($this->transport === null) {

            $className = $this->options->getDefaultTransport();
            $this->transport = new $className;
        }

        return $this->transport;
    }

    public function send($email, $templateId, $params = [], $locale = null)
    {
        $params = ArrayUtils::iteratorToArray($params);
        $locale = $locale ?: $this->options->getDefaultLocale();

        list ($subject, $html, $text) = $this->templates->render($templateId, $locale, $params);

        $message = new MailMessage();
        $message->getHeaders()->addHeaderLine('Content-Type', 'multipart/alternative');

        $htmlPart = new MimePart($html);
        $htmlPart->type = 'text/html';

        $textPart = new MimePart($text);
        $textPart->type = 'text/plain';

        $body = new MimeMessage();
        $body->setParts([$htmlPart, $textPart]);

        $message->setEncoding($this->options->getEncoding());
        $message->setBody($body);

        $message->setSubject($subject);
        $message->setReplyTo($this->options->getReplyTo());
        $message->setFrom($this->options->getFrom());
        $message->setTo($email);
        $message->setBcc($this->options->getBcc());

        $this->getTransport()->send($message);
    }
}
