<?php

namespace PhpConsole;

spl_autoload_register(function ($class) {
	if(strpos($class, __NAMESPACE__) === 0) {
		/** @noinspection PhpIncludeInspection */
		require_once(__DIR__ . '/' . '..' . '/' . str_replace('\\', '/', $class) . '.php');
	}
});
