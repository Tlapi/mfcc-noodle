mfcc-noodle
===========

Easy to use, easy to implement, superflexibile and modular Content Management system base on Zend Framework 2 and Twitter Bootstrap.

How to create your own module:
==============================

1. Create you own entity in ``Noodle\Entity\Tables``
2. Create corresponding table for your entity.
3. Add your entity to modules table

How to create your own datatype:
==============================

1. Create your own form element base on one of Zend Form Elements
2. Implement ``prepare()``, ``treatValueBeforeSave()`` and ``getListedValue()`` if needed.
3. Use data type in your entity

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

TODOS
=====
1. Create tables automatically from entities
2. Manage tables in gui
3. Clean code and provide interfaces
