<?php
return array(
    'modules' => array(
    	'EdpModuleLayouts',
    	'DoctrineModule',
    	'DoctrineORMModule',
    	'FileBank',
    	'WebinoImageThumb',
    	'AssetManager',
        'Noodle',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            '../../../config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
    ),
);