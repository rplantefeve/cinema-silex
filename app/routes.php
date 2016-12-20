<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->get('/home',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\HomeController::home')
->bind('home');

$app->match('/user/add',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\HomeController::createNewUser')
->bind('/user/add');

$app->post('/login',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\HomeController::home')
->bind('login');

$app->match('/movie/list',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::moviesList')
->bind('movie/list');

$app->match('/movie/edit/{filmId}',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::editMovie')
->bind('movie/edit');

$app->match('/movie/add',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::editMovie')
->bind('movie/add');

$app->match('/movie/delete/{filmId}',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::deleteMovie')
->bind('/movie/delete');

$app->get('/movie/list',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::moviesList')
->bind('moviesList');

$app->post('/showtime/edit/{filmId}/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\ShowtimesController::editShowtime')
->bind('showtime/edit');

//$app->get('/showtime/movie/add/{filmId}',
//'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::editShowtime')
//->bind('editShowtime');
//
//$app->get('/showtime/cinema/add/{cinemaId}',
//'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::editShowtime')
//->bind('editShowtime');
//
//$app->get('/showtime/add/{filmId}/{cinemaId}',
//'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::editShowtime')
//->bind('editShowtime');