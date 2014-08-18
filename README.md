[![Build Status](https://travis-ci.org/Tlapi/mfcc-noodle.png?branch=master)](https://travis-ci.org/Tlapi/mfcc-noodle) [![Dependency Status](https://www.versioneye.com/user/projects/52167869632bac7749010ca8/badge.png)](https://www.versioneye.com/user/projects/52167869632bac7749010ca8)
-----------------
mfcc-noodle
===========

NOTE: This is still under heavy-development. Use at your own risk.

Easy to use, easy to implement, superflexibile and modular Content Management system base on Zend Framework 2 and Twitter Bootstrap.

Famous 9 step installation to create new website with admin
===========

1. Install Zend Skeleton App (optional)
2. Require mfcc-noodle in composer ``"mfcc/noodle": "dev-master"``
3. There is no step 3
4. Copy config.dist files from ``vendor/mfcc/noodle/config`` to ``config/autoload`` and setup
5. Setup you doctrine connection driver if not present
6. Copy ``cli-config.php`` from ``vendor/mfcc/noodle`` to project root
7. Run ``php vendor/doctrine/orm/bin/doctrine orm:schema-tool:update --force`` to create db
8. Set default admin user
9. Create your entities and run ``php vendor/doctrine/orm/bin/doctrine orm:schema-tool:update --force`` after every update


How to create your own module:
==============================

1. Create you own entity in ``Noodle\Entity\Tables``
2. Create corresponding table for your entity.
3. Add your entity to modules table

Bundled datatypes and options
==============================

TBS

How to create your own datatype:
==============================

1. Create your own form element base on one of Zend Form Elements
2. Implement ``prepare()``, ``treatValueBeforeSave()`` and ``getListedValue()`` if needed.
3. Use data type in your entity like that:

```sh
/**
  * @ORM\Column(type="string");
	* @Annotation\Type("Your\Datatype\Namespace")
	* @Annotation\Options({"label":"Your label"})
	* @Annotation\Required(true)
	*/
	public $title;
```

How to create your own custom module:
====================================

Noodle is prepared for vendor modules. Just listen for this event:

```sh
$events->getSharedManager()->attach('Noodle\Service\ModulesService', 'vendorModules.load', function ($e) {
  		$e->getTarget()->addVendorModule('your_module_service');
});
```

Vendor module must implement ``TODO``.

How to create dashboard widget:
==============================

Attach view helper to shared event manager event

```sh
$events->getSharedManager()->attach('Noodle\Controller\IndexController', 'dashboard', function ($e) {
  		$e->getTarget()->addDashboardModule('some_helper_service');
});
```

Use and save noodle options for your widget if needed. 

NOTE: Please be carefull if you use your own option settings so there will be no conflicts in namespaces.

In you helper:

```sh
$optionService = $this->getServiceLocator()->getServiceLocator()->get('noodleOptions');
$optionService->setOption('your_widget_namespace.your_option_key', option_value);
$optionService->getOption('your_widget_namespace.your_option_key');
```

Widget will be rendered via your helper ``public function __invoke(){}`` method

Please see ``Noodle\View\Helper\Dashboard\GoogleAnalytics`` as a reference.

Option settings
===============

You can set and get noodle options settings wherever you want using:

```sh
$optionService = $this->getServiceLocator()->getServiceLocator()->get('noodleOptions');
$optionService->setOption('some_widget_namespace.some_option_key', option_value);
$optionService->getOption('some_option_key');
```

TODOS
=====
1. Create tables automatically from entities
2. Manage tables in gui
3. Clean code and provide interfaces
4. Add user role management
5. Set absolute namespace path for Noodle\Entity\Tables and set base table to extend from
