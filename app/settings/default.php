<?php return array (
  'app' => 
  array (
    'name' => 'Smart Quick View',
    'id' => 'smartquickview',
    'shortId' => 'sqv',
    'pluginFileName' => 'smartquickview',
    'textDomain' => 'smartquickview-international',
  ),
  'events' => 
  array (
    'globalValidator' => 'SmartQuickView\\App\\Events\\CustomGlobalEventsValidator',
  ),
  'schema' => 
  array (
    'applicationDatabase' => 'SmartQuickView\\App\\Data\\Schema\\ApplicationDatabase',
  ),
  'directories' => 
  array (
    'app' => 
    array (
      'schema' => 'data/schema',
      'scripts' => 'scripts',
      'dashboard' => 'scripts/dashboard',
    ),
    'storage' => 
    array (
      'branding' => 'storage/branding',
    ),
  ),
  'environment' => 'production',
);