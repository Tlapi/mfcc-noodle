<?php
/**
 * This configuration should be put in your module `configs` directory.
 */
return array(
    'assetic_configuration' => array(
        // Use on production environment
        // 'debug'              => false,
        // 'buildOnRequest'     => false,

        // Use on development environment
        'debug' => true,
        'buildOnRequest' => true,

        // this is specific to this project
        'webPath' => realpath('public/assets'),
        'basePath' => 'public/assets',

        'controllers' => array(
            'Noodle\Controller\Index' => array(
                '@my_css',
                '@my_js',
            ),
        ),

        'modules' => array(
            'Noodle' => array(
                'root_path' => __DIR__ . '/../assets',

                'collections' => array(
                    'my_css' => array(
                        'assets' => array(
                            // 'css/main1.css',
                            // 'css/main2.css',
                        ),
                        'filters' => array(
                            '?CssRewriteFilter' => array(
                                'name' => 'Assetic\Filter\CssRewriteFilter'
                            ),
                            '?CssMinFilter' => array(
                                'name' => 'Assetic\Filter\CssMinFilter'
                            ),
                        ),
                    ),
                    'my_js' => array(
                        'assets' => array(
                        // 'js/main1.js',
                            // 'js/main2.js',
                        ),
                        'filters' => array(
                            '?JSMinFilter' => array(
                                'name' => 'Assetic\Filter\JSMinFilter'
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
