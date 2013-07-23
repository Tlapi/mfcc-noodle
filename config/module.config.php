<?php

namespace Noodle;

return array(
	'module_layouts' => array(
		'Noodle' => 'noodle/layout/layout',
	),
	'service_manager' => array(
		'factories' => array(
			//'zendeskService'   => 'MfccZendeskContact\Service\ZendeskServiceFactory',
		),
		'invokables' => array(
			//'MfccZendeskContact\Form\ContactForm' => 'MfccZendeskContact\Form\ContactForm',
		),
	),
	'controllers' => array(
		'invokables' => array(
			'Noodle\Controller\IndexController' => 'Noodle\Controller\IndexController',
			'Noodle\Controller\Settings' => 'Noodle\Controller\SettingsController',
			'Noodle\Controller\ModulesManager' => 'Noodle\Controller\ModulesManagerController',
			'Noodle\Controller\Modules' => 'Noodle\Controller\ModulesController',
			'Noodle\Controller\Filesystem' => 'Noodle\Controller\FilesystemController',
		),
	),
	'view_manager' => array(
			'display_not_found_reason' => true,
			'display_exceptions'       => true,
			'doctype'                  => 'HTML5',
			'not_found_template'       => 'error/404',
			'exception_template'       => 'error/index',
			'template_map'             => array(),
			'template_path_stack' => array(
					__DIR__ . '/../view',
			),
	),
	'router' => array(
        'routes' => array(
            'noodle' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/noodle',
                    'defaults' => array(
                        'controller' => 'Noodle\Controller\IndexController',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                		/* MODULES MANAGER */
                		'modules-manager' => array(
                				'type'    => 'literal',
                				'options' => array(
                						'route'    => '/modules-manager',
                						'defaults' => array(
                								'controller' => 'Noodle\Controller\ModulesManager',
                								'action'     => 'index',
                						),
                				),
                				'may_terminate' => true,
                				'child_routes' => array(
                						'add' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/add',
                										'constraints' => array(
                										),
                										'defaults' => array(
                												'action' => 'add'
                										)
                								)
                						),
                						'add-repository' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/add-repository',
                										'constraints' => array(
                										),
                										'defaults' => array(
                												'action' => 'add-repository'
                										)
                								)
                						),
                						'edit-repository' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/edit-repository/:name',
                										'constraints' => array(
                												'name'     => '[a-zA-Z0-9_-]+'
                										),
                										'defaults' => array(
                												'action' => 'edit-repository'
                										)
                								)
                						),
                				)
                		),
                		/* MODULES CONTENT MANAGEMENT */
                		'modules' => array(
                				'type'    => 'literal',
                				'options' => array(
                						'route'    => '/modules',
                						'defaults' => array(
                								'controller' => 'Noodle\Controller\Modules',
                								'action'     => 'index',
                						),
                				),
                				'may_terminate' => true,
                				'child_routes' => array(
                						'add' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/add/[:name]',
                										'constraints' => array(
                												'name'     => '[a-zA-Z0-9_-]+'
                										),
                										'defaults' => array(
                												'action' => 'add'
                										)
                								)
                						),
                						'show' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/show/[:name]',
                										'constraints' => array(
                												'name'     => '[a-zA-Z0-9_-]+'
                										),
                										'defaults' => array(
                												'action' => 'show'
                										)
                								)
                						),
                						'edit' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/edit/:name/:id',
                										'constraints' => array(
                												'name'     => '[a-zA-Z0-9_-]+',
                												'id'     => '[0-9]+'
                										),
                										'defaults' => array(
                												'action' => 'edit'
                										)
                								),
                								'may_terminate' => true,
                								'child_routes' => array(
                										'sheet' => array(
                												'type' => 'segment',
                												'options' => array(
                														'route' => '/sheet/[:sheet_name]',
                														'constraints' => array(
                																'name'     => '[a-zA-Z0-9_-]+'
                														),
                														'defaults' => array(
                																'action' => 'sheet'
                														)
                												)
                										),
                								)
                						),
                						'delete' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/delete/:name/:id',
                										'constraints' => array(
                												'name'     => '[a-zA-Z0-9_-]+',
                												'id'     => '[0-9]+'
                										),
                										'defaults' => array(
                												'action' => 'delete'
                										)
                								),
                								'may_terminate' => true,
                						),
                						'mass-delete' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/mass-delete/:name',
                										'constraints' => array(
                												'name'     => '[a-zA-Z0-9_-]+'
                										),
                										'defaults' => array(
                												'action' => 'mass-delete'
                										)
                								),
                								'may_terminate' => true,
                						),
                				),
                		),
                		/* NOODLE SETTINGS */
                		'settings' => array(
                				'type'    => 'literal',
                				'options' => array(
                						'route'    => '/settings',
                						'defaults' => array(
                								'controller' => 'Noodle\Controller\Settings',
                								'action'     => 'index',
                						),
                				),
                				'may_terminate' => true,
                				'child_routes' => array(

                				),
                		),
                		/* FILESYSTEM */
                		'filesystem' => array(
                				'type'    => 'literal',
                				'options' => array(
                						'route'    => '/filesystem',
                						'defaults' => array(
                								'controller' => 'Noodle\Controller\Filesystem',
                								'action'     => 'index',
                						),
                				),
                				'may_terminate' => true,
                				'child_routes' => array(
                						'upload' => array(
                								'type' => 'segment',
                								'options' => array(
                										'route' => '/upload',
                										'constraints' => array(
                										),
                										'defaults' => array(
                												'action' => 'upload'
                										)
                								)
                						),
                				)
                		),
                ),
            ),
        ),
    ),
	'doctrine' => array(
			'connection' => array(
					'orm_default' => array(
							'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
							'params' => array(
									'host'     => 'localhost',
									'port'     => '3306',
									'user'     => 'username',
									'password' => 'password',
									'dbname'   => 'database_name',
							)
					)
			),
			'driver' => array(
					__NAMESPACE__ . '_driver' => array(
							'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
							'cache' => 'array',
							'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
					),
					'orm_default' => array(
							'drivers' => array(
									__NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
							),
					),
			),
	),
	'asset_manager' => array(
        'resolver_configs' => array(
            'paths' => array(
                'Noodle' => __DIR__ . '/../public',
            ),
        ),
    ),
);
