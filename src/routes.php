<?php

// list of accessible routes of your application, add every new route here
// key : route to match
// values : 1. controller name
//          2. method name
//          3. (optional) array of query string keys to send as parameter to the method
// e.g route '/item/edit?id=1' will execute $itemController->edit(1)
return [
    '' => ['CategoryController', 'index',],
    'user/add' => ['UserController', 'add',],
    'user/show' => ['UserController', 'show', ['id']],
    'user/edit' => ['UserController', 'edit', ['id']],
    'user/delete' => ['UserController', 'delete',],
    'user/login' => ['UserController','login',],
    'users' => ['UserController', 'index',],
    'items' => ['ItemController', 'index',],
    'items/edit' => ['ItemController', 'edit', ['id']],
    'items/show' => ['ItemController', 'show', ['id']],
    'items/add' => ['ItemController', 'add',],
    'items/delete' => ['ItemController', 'delete',],
    'post/Message/add' => ['MessageController', 'add',],
    'post/show' => ['PostController', 'show', ['id']],
    'post/add' => ['PostController', 'add',],
    'post/edit' => ['PostController', 'edit', ['id']],
    'post/delete' => ['PostController', 'delete', ['id']],
    'posts/search' => ['PostController', 'search',],
    'user/logout' => ['UserController', 'logout'],
    'themes/index' => ['ThemeController', 'listThemeByCategory', ['id']],
    'posts/index' => ['PostController', 'index', ['theme']],
    'message/delete' => ['MessageController', 'delete', ['id']],
    'post/Message/edit' => ['MessageController', 'edit', ['id']],
];
