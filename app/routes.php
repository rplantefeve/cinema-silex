<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->get('/home',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\HomeController::home')
->bind('home');

$app->get('/showtime/movie/{filmId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::movieShowtimes')
->bind('showtime');

$app->match('/user/add',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\HomeController::createNewUser')
->bind('/user/add');

$app->post('/login',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\HomeController::home')
->bind('login');
// CinemaList, editCinema, delete cinema, add cinema team papat
$app->match('/cinema/list',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::cinemasList')
->bind('/cinema/list');

$app->match('/movie/list',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::moviesList')
->bind('movie/list');
$app->post('/cinema/delete/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::deleteCinema')
->bind('/cinema/delete');

$app->match('/cinema/edit/{cinemaID}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::editCinema')
->bind('/cinema/edit');

$app->match('/cinema/add',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::editCinema')
->bind('/cinema/add');
// ****************************
$app->post('/movie/edit',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\MovieController::editMovie')
->bind('/movie/edit');

$app->match('/movie/edit/{filmId}',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::editMovie')
->bind('movie/edit');

$app->match('/movie/add',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::editMovie')
->bind('movie/add');

$app->match('/movie/delete/{filmId}',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\MovieController::deleteMovie')
->bind('/movie/delete');

$app->post('/showtime/delete/{filmId}/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\ShowtimesController::deleteShowtime')
->bind('/showtime/delete');
$app->post('/showtime/edit/{filmId}/{cinemaId}',
'Semeformation\\Mvc\\Cinema_crud\\Controllers\\ShowtimesController::editShowtime')
->bind('showtime/edit');

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

$app->get('/favorite/list',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\FavoriteController::editFavoriteMoviesList')
->bind('editFavoriteMoviesList');