<?php
/**
 * @author Antoine Hedgcock
 */

namespace EmailTemplatesTest\Service;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Roave\EmailTemplates\Options\EmailServiceOptions;
use Roave\EmailTemplates\Service\EmailService;
use Roave\EmailTemplates\Service\TemplateServiceInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

/**
 * Class EmailServiceTest
 *
 * @coversDefaultClass \Roave\EmailTemplates\Service\EmailService
 * @covers ::<!public>
 *
 * @group service
 */
class EmailServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var EmailServiceOptions
     */
    protected $options;

    /**
     * @var TemplateServiceInterface|PHPUnit_Framework_MockObject_MockObject
     */
    protected $templateService;

    /**
     * @var EmailService
     */
    protected $service;

    /**
     * @covers ::__construct
     */
    protected function setUp()
    {
        $this->options         = new EmailServiceOptions();
        $this->templateService = $this->createMock(TemplateServiceInterface::class);

        $this->service = new EmailService(
            $this->templateService,
            $this->options
        );
    }

    private function mockRender()
    {
        $this->templateService
            ->expects($this->any())
            ->method('render')
            ->willReturn(['subject', 'html', 'text']);
    }

    /**
     * @covers ::setTransport
     */
    public function testSetTransport()
    {
        $this->service->setTransport($this->createMock(TransportInterface::class));
    }

    /**
     * @covers ::getTransport
     */
    public function testGetTransport()
    {
        $transport = $this->service->getTransport();

        $this->assertInstanceOf($this->options->getDefaultTransport(), $transport);

        $transport = $this->createMock(TransportInterface::class);
        $this->service->setTransport($transport);

        $this->assertSame($transport, $this->service->getTransport());
    }

    /**
     * @covers ::send
     */
    public function testSend()
    {
        $email      = 'contact@roave.com';
        $locale     = 'sv_SE';
        $templateId = 'roave:contact';
        $parameters = ['name' => 'Antoine'];

        $this->mockRender();

        $transport = $this->createMock(TransportInterface::class);
        $transport
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(Message::class));

        $this->service->setTransport($transport);
        $this->service->send($email, $templateId, $parameters, $locale);
    }

    /**
     * @covers ::send
     */
    public function testSendHasCorrectContentType()
    {
        $email      = 'contact@roave.com';
        $locale     = 'sv_SE';
        $templateId = 'roave:contact';
        $parameters = ['name' => 'Hotas'];

        $this->mockRender();

        $transport = $this->createMock(TransportInterface::class);
        $transport
            ->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf(Message::class))
            ->will($this->returnCallback(function(Message $message) {
                $contentType = $message->getHeaders()->get('content-type');
                $this->assertContains('multipart/alternative', $contentType->getFieldValue());
            }));

        $this->service->setTransport($transport);
        $this->service->send($email, $templateId, $parameters, $locale);
    }

    /**
     * @covers ::send
     */
    public function testSendCanOverrideReplyToHeader()
    {
        $email      = 'jonas.eriksson@interactivesolutions.se';
        $locale     = 'sv_SE';
        $templateId = 'roave:contact';
        $parameters = ['name' => 'Hotas'];
        $replyTo    = 'jonas.eriksson@interactivesolutions.se';

        $this->mockRender();

        $transport = $this->createMock(TransportInterface::class);
        $transport
            ->expects($this->at(0))
            ->method('send')
            ->with($this->isInstanceOf(Message::class))
            ->will($this->returnCallback(function(Message $message) {
                $replyToField = $message->getHeaders()->get('Reply-To');
                $this->assertContains($this->options->getReplyTo(), $replyToField->getFieldValue());
            }));

        $transport
            ->expects($this->at(1))
            ->method('send')
            ->with($this->isInstanceOf(Message::class))
            ->will($this->returnCallback(function(Message $message) use ($replyTo) {
                $replyToField = $message->getHeaders()->get('Reply-To');
                $this->assertContains($replyTo, $replyToField->getFieldValue());
            }));

        $this->service->setTransport($transport);
        $this->service->send($email, $templateId, $parameters, $locale, null);
        $this->service->send($email, $templateId, $parameters, $locale, $replyTo);
    }
}
