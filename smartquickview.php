<?php

use SmartQuickView\Original\Installation\Installator;
use SmartQuickView\Original\Events\Registrator\EventsRegistrator;


/*
Plugin Name: Smart Quick View
Description:  A very nice quick view popup for WooCommerce.
Version:      1.0.1
Author:       Neblabs
Author URI:   https://neblabs.com
Text Domain:  smartquickview-international
Domain Path:  /international
Requires at least: 4.6
Requires PHP: 7.0
*/

/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 ++
 ++
 ++                      |----------------------------|
 ++                      |          |   Quick         |
 ++                      |   SMART  |   View          |
 ++                      |          |                 |
 ++                      |----------------------------|
 ++
 ++     
 ++     The interfaces in this codebase (classes, event hooks, etc) are prone to change 
 ++     at any time and without previous notice. Use them at your own risk.
 ++
 ++
 ++     It is recommended to browse this code base using a 
 ++     code editor or an IDE with namespace -> filename support.
 ++     
 ++     Smart Quick View logic is located inside the ~/app directory.
 ++
 ++     You will find our very own, in-house framework within ~/original, you might be
 ++     familiar with this structure if you've browsed one of our plugins before (made by Neblabs).
 ++
 ++     Third Party packages are located under the vendor/ directory and are autoloaded
 ++     using Composer's autoloader

 ++     All classes defined by this plugin are namespaced. Namespaces are mapped 1:1 to file 
 ++     names and directories, with the exception of the ID. 
 ++     For example, the namespace:
 ++     -- SmartQuickView\App\Handlers\PopupButtonRendererHandler
 ++     is mapped to a class with the filename: 
 ++     -- app/handlers/popupbuttonrendererhandler.php
 ++     
 ++     All custom event handlers (action hooks) are registered 
 ++     at: app/events/actions.php via SmartQuickView\Original\Events\Registrator\EventsRegistrator
 ++
 ++     We're not affiliated with them, but we have tested and confirmed compatibility with these plugins:
 ++     - Obviously WooCommerce
 ++     - https://wordpress.org/plugins/woo-variation-swatches/
 ++     - (more tests coming soon...)
 ++
 ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

require_once plugin_dir_path( __FILE__ ).'bootstrap.php';

(object) $installator = new Installator;

(object) $eventsRegistrator = new EventsRegistrator;

$eventsRegistrator->registerEvents();