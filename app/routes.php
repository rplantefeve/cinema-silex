<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->get('/home',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::home')
->bind('home');

$app->match('/user/add',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::createNewUser')
->bind('/user/add');

$app->get('/movie/list',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::moviesList')
->bind('/movie/list');

$app->post('/movie/edit',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::editMovie')
->bind('/movie/edit');

$app->get('/movie/add',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::editMovie')
->bind('/movie/add');

$app->get('/movie/delete',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::deleteMovie')
->bind('/movie/delete');