<?php

namespace LeTots\WorkflowExtension;

use LeTots\WorkflowExtension\DependencyInjection\Compiler\WorkflowExtensionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class WorkflowExtensionBundle extends Bundle
{
	public function build(ContainerBuilder $container): void
	{
		parent::build($container);
		
		// Enregistrer le WorkflowCompilerPass
		$container->addCompilerPass(new WorkflowExtensionCompilerPass());
	}
}
