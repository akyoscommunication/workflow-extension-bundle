<?php

namespace LeTots\WorkflowExtension\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Place
{
	public function __construct(
		public bool $initial = false,
		public ?array $metadata = null
	) {
	}
}
