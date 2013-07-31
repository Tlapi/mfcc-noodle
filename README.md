mfcc-noodle
===========

Mfcc Noodle CMS ZF2 module

How to create your own module:
==============================

TBS

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
