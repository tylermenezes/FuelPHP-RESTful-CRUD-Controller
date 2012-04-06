Installation
============
Copy to a subfolder of fuel/packages. Add `rest_crud` to `['always_load']['packages']` in config.php.

Usage
=====
For the most part, HTTP verbs map directly to functions (e.g. GET /controllername => function get()).
The client can choose the return format by specifying an extension (see the documentation for Controller_Rest in
FuelPHP).

GET requests can invoke one of three methods.

* When one or more arguments are specified, it will invoke `get(...)`.
* When no arguments are specified, it will invoke `get_list($page)` (falling back to `get(...)`). Page is
  automatically populated from the "page" GET paramater (defaulting to 0) if you want to use pagination.
* When the search GET paramater is populated, it will invoke `get_search($query, $page)`, falling back
  to `get_list($page)`, and finally `get(...)`.

Routes
======
To invoke get_list with a non-default return format, you need to add a slash after the controller name.
(e.g. /controllername/.json). To remove this requirement (e.g. enable /controllername.json), add this route:

    '(:alnum).(:alpha)' => '$1/.$2',