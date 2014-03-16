<?php
/**
 * Created by PhpStorm.
 * User: antoine
 * Date: 16/03/14
 * Time: 23:42
 */

namespace Roave\EmailTemplates\Factory\Service\Template\Listener;

use Roave\EmailTemplates\Repository\TemplateRepository;
use Roave\EmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener;
use Roave\EmailTemplates\Service\TemplateService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UpdateTemplateParametersListenerFactory
 *
 * Creates the update templates parameters listener
 * {@see \RoaveEmailTemplates\Service\Template\Listener\UpdateTemplateParametersListener}
 */
class UpdateTemplateParametersListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return UpdateTemplateParametersListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UpdateTemplateParametersListener(
            $serviceLocator->get(TemplateRepository::class)
        );
    }
}