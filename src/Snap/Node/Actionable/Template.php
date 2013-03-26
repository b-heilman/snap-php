<?php

namespace Snap\Node\Actionable;

interface Template {
	public function getJavascriptTemplate();
	public function getWrapper();
	public function getPath();
}