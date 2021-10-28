<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['HomeController', 'index',],
    'categorys' => ['CategoryController', 'index',],
    'categorys/edit' => ['CategoryController', 'edit', ['id']],
    'categorys/show' => ['CategoryController', 'show', ['id']],
    'categorys/add' => ['CategoryController', 'add',],
    'categorys/delete' => ['CategoryController', 'delete',],
    'themes' => ['ThemeController', 'index',],
    'themes/edit' => ['ThemeController', 'edit', ['id']],
    'themes/show' => ['ThemeController', 'show', ['id']],
    'themes/add' => ['ThemeController', 'add',],
    'themes/delete' => ['ThemeController', 'delete',],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],
];
