<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->get('/home',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::home')
->bind('home');

// CinemaList, editCinema, delete cinema
$app->get('/cinemaList',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::cinemaList')
->bind('cinema/list');

$app->get('/editCinema',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::editCinema')
->bind('cinema/edit/cinemaID');

$app->get('/editCinema',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::editCinema/add')
->bind('cinema/add');

$app->get('/cinemaDelete',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::cinemaDelete')
->bind('cinema/cinemaDelete/cinemaID');