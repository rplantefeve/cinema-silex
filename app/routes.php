<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->get('/home',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::home')
->bind('home');

$app->post('/login',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::home')
->bind('login');

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