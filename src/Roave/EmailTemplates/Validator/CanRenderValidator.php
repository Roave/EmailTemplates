<?php
/**
 * @copyright Interactive Solutions
 */
declare(strict_types=1);

namespace Roave\EmailTemplates\Validator;

use Roave\EmailTemplates\Service\Template\Engine\EngineInterface;
use Zend\Validator\AbstractValidator;

final class CanRenderValidator extends AbstractValidator
{
    const RENDERING_FAILED = 'roave:email-templates:cannot-render-template';

    /**
     * @var array
     */
    private $messageTemplates = [
        self::RENDERING_FAILED => 'Rendering failed: with %value%s',
    ];

    /**
     * @var EngineInterface
     */
    private $engine;

    public function __construct(EngineInterface $engine)
    {
        parent::__construct();

        $this->engine = $engine;
    }

    public function isValid($value, $context = null)
    {
        try {
            $this->engine->render($value, $context['parameters'] ?? []);
        } catch (\Throwable $e) {
            $this->error(self::RENDERING_FAILED, $e->getMessage());

            return false;
        }

        return true;
    }
}
