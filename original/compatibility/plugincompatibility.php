<?php

namespace SmartQuickView\Original\Compatibility;

use SmartQuickView\Original\Compatibility\BuiltIn\GlobalCompatibility;
use SmartQuickView\Original\Compatibility\CompatibilityManager;
use SmartQuickView\Original\Utilities\TypeChecker;

/**
 * GlobalCompatibility will *always* run 
 * DefaultCompatibilty will only run when no other CompatibiltyManagers have run (excluding GlobalCompatibility).
 */
Class PluginCompatibility
{
    use TypeChecker;

    public static function handle(array $compatibilityManagers)
    {
        (boolean) $defaultShouldRun = true;

        foreach ($compatibilityManagers as $compatibilityManager) {
            $compatibilityManager = static::expectValue($compatibilityManager)->toBe(CompatibilityManager::class);

            if (($compatibilityManager instanceof GlobalCompatibility) || 
                $compatibilityManager->shouldHandle($defaultShouldRun)) {

                $defaultShouldRun = $compatibilityManager->shouldDefaultBeHandled($defaultShouldRun);
                $compatibilityManager->handle();
            }
        }
    }
    
}