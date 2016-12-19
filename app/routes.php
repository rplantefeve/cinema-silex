<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$app->get('/home',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\HomeController::home')
->bind('home');

// CinemaList, editCinema, delete cinema, add cinema team papat
$app->match('/cinema/list',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::cinemaList')
->bind('cinema/list');

$app->match('/cinema/edit/{cinemaID}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::editCinema')
->bind('cinema/edit/{cinemaID}');

$app->match('/cinema/add',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::editCinema/add')
->bind('cinema/add');

$app->post('/cinema/delete/{cinemaID}',
'Semeformation\\Mvc\\Cinema_crud\\controllers\\CinemaController::deleteCinema')
->bind('cinema/cinemaDelete/{cinemaID}');