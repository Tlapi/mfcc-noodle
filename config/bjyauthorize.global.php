<?php
/**
 * Created by PhpStorm.
 * User: tlapi
 * Date: 27/03/14
 * Time: 16:07
 */
return array(
    'bjyauthorize' => array(
        'guards' => array(
            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all controllers and actions unless they are specified here.
             * You may omit the 'action' index to allow access to the entire controller
             */
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'index', 'action' => 'index', 'roles' => array('guest','user')),
                array('controller' => 'index', 'action' => 'stuff', 'roles' => array('user')),
                // You can also specify an array of actions or an array of controllers (or both)
                // allow "guest" and "admin" to access actions "list" and "manage" on these "index",
                // "static" and "console" controllers
                array(
                    'controller' => array('index', 'static', 'console'),
                    'action' => array('list', 'manage'),
                    'roles' => array('guest', 'admin')
                ),
                array(
                    'controller' => array('search', 'administration'),
                    'roles' => array('staffer', 'admin')
                ),
                array('controller' => 'zfcuser', 'roles' => array()),
                // Below is the default index action used by the ZendSkeletonApplication
                // array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'user')),
            ),

            /* If this guard is specified here (i.e. it is enabled), it will block
             * access to all routes unless they are specified here.
             */
            'BjyAuthorize\Guard\Route' => array(
                array('route' => 'zfcuser', 'roles' => array('user')),
                array('route' => 'zfcuser/logout', 'roles' => array('user')),
                array('route' => 'zfcuser/login', 'roles' => array('guest')),
                array('route' => 'zfcuser/register', 'roles' => array('guest')),
                // Below is the default index action used by the ZendSkeletonApplication
                array('route' => 'home', 'roles' => array('guest', 'user')),
            ),
        ),
    )
);