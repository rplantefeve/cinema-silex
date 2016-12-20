<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->get('/home',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::home')
->bind('home');

$app->get('/showtime/movie/{filmId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::movieShowtimes')
->bind('showtime');

$app->match('/user/add',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::createNewUser')
->bind('/user/add');

$app->match('/login',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::login')
->bind('login');

$app->post('/movie/edit',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::editMovie')
->bind('/movie/edit');

$app->get('/movie/add',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::editMovie')
->bind('/movie/add');

$app->get('/movie/delete',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::deleteMovie')
->bind('/movie/delete');

$app->get('/movie/list',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::moviesList')
->bind('moviesList');

$app->get('/showtime/edit/{filmId}/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::editShowtime')
->bind('editShowtime');

$app->get('/showtime/movie/add/{filmId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::editShowtime')
->bind('editShowtime');

$app->get('/showtime/cinema/add/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::editShowtime')
->bind('editShowtime');

$app->get('/showtime/add/{filmId}/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::editShowtime')
->bind('editShowtime');

$app->get('/showtime/cinema/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::cinemaShowtimes')
->bind('cinemaShowtimes');

$app->get('/logout',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::logout')
->bind('logout');