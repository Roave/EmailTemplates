<?php
/**
 * @copyright Interactive Solutions
 */

declare(strict_types=1);

namespace Roave\EmailTemplates\Factory\Validator;

use Roave\EmailTemplates\Service\Template\Engine\EngineInterface;
use Roave\EmailTemplates\Service\Template\EnginePluginManager;
use Roave\EmailTemplates\Validator\CanRenderValidator;
use Roave\EmailTemplates\Options\TemplateServiceOptions;
use Zend\Validator\ValidatorPluginManager;
use Zend\Validator\ValidatorPluginManagerAwareInterface;

final class CanRenderValidatorFactory
{
    public function __invoke(ValidatorPluginManager $manager): CanRenderValidator
    {
        $container = $manager->getServiceLocator();

        /* @var $options TemplateServiceOptions */
        $options = $container->get(TemplateServiceOptions::class);

        /* @var $engineContainer ContainerInterface */
        $engineContainer = $container->get(EnginePluginManager::class);

        return new CanRenderValidator($engineContainer->get($options->getEngine()));
    }
}
