<?php

namespace LeTots\WorkflowExtension\DependencyInjection\Compiler;

use LeTots\WorkflowExtension\Attribute\AsWorkflow;
use ReflectionException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\WorkflowInterface;
use ReflectionClass;

class WorkflowExtensionCompilerPass implements CompilerPassInterface
{
	/**
	 * @throws ReflectionException
	 */
	public function process(ContainerBuilder $container)
	{
		// Parcourir tous les services définis dans le conteneur
		foreach ($container->getDefinitions() as $definition) {
			$class = $definition->getClass();
			
			if (!$class || !class_exists($class, false)) {
				continue;
			}
			
			$reflectionClass = new ReflectionClass($class);
			$attributes = $reflectionClass->getAttributes(AsWorkflow::class);
			
			foreach ($attributes as $attribute) {
				/** @var AsWorkflow $workflowAttr */
				$workflowAttr = $attribute->newInstance();
				$workflowName = $workflowAttr->name;
				
				// Enregistrer le service du workflow en spécifiant explicitement la classe
				$container->register($class)
					->setClass($class)  // Spécifier explicitement la classe
					->setPublic(true);
				
				// Ajouter le workflow au registry avec un alias basé sur le nom du workflow
				$container->register('workflow.'.$workflowName, WorkflowInterface::class)
					->setClass(WorkflowInterface::class)  // Spécifier la classe WorkflowInterface
					->setFactory([new Reference($class), '__invoke']) // Utilisation de la méthode __invoke
					->setPublic(true);
				
				// Retrouver le service Registry et ajouter le workflow
				if ($container->hasDefinition(Registry::class)) {
					$registryDefinition = $container->findDefinition(Registry::class);
					$registryDefinition->addMethodCall('addWorkflow', [
						new Reference('workflow.'.$workflowName),
						$class::WORKFLOW_SUPPORT_STRATEGY
					]);
				}
				
				// Créer un alias pour que Symfony puisse retrouver le workflow par son nom
				$container->setAlias(WorkflowInterface::class . ' $'.$workflowName, 'workflow.'.$workflowName);
			}
		}
	}
}
