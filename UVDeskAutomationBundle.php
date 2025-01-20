<?php

namespace Webkul\UVDesk\AutomationBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Webkul\UVDesk\AutomationBundle\DependencyInjection\UVDeskExtension;
use Webkul\UVDesk\AutomationBundle\DependencyInjection\Compilers as UVDeskAutomationCompilers;

class UVDeskAutomationBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new UVDeskExtension();
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new UVDeskAutomationCompilers\WorkflowPass());
        $container->addCompilerPass(new UVDeskAutomationCompilers\PreparedResponsePass());
    }
}
