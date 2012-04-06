<?php
/**
 * REST CRUD is a simple framework for building RESTful CRUD applications
 *
 * @package    Rest_Crud
 * @version    1.0
 * @author     Tyler Menezes
 * @license    MIT License
 * @copyright  Copyright (c) 2012 Tyler Menezes.
 * @link       https://tyler.menez.es/
 */


Autoloader::add_classes(array(
  'Fuel\\Core\\Controller_Rest_Crud'             => __DIR__.'/classes/rest_crud.php',
));