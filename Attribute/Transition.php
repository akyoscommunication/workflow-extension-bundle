<?php

namespace LeTots\WorkflowExtension\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Transition
{
	public function __construct(
		public array|string $from,
		public array|string $to,
		public ?array $metadata = [],
	) {
	}
}
