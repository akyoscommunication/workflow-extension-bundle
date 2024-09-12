<?php

namespace LeTots\WorkflowExtension\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class AsWorkflow
{
	public function __construct(
		public string $name,
		public string $supportStrategy,
	) {
	}
}
