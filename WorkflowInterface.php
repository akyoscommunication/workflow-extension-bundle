<?php

namespace LeTots\WorkflowExtension;

interface WorkflowInterface
{
	public function getPlaces(): array;
	
	public function getTransitions(array $places): array;
}