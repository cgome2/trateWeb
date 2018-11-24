<?php

// if (!session_id()) session_start();

require_once 'init.php';

//CONFIGURATION for SmartAdmin UI

//ribbon breadcrumbs config
//array("Display Name" => "URL");
$breadcrumbs = array(
    "Home" => APP_URL
);

/*navigation array config

ex:
"dashboard" => array(
    "title" => "Display Title",
    "url" => "http://yoururl.com",
    "url_target" => "_self",
    "icon" => "fa-home",
    "label_htm" => "<span>Add your custom label/badge html here</span>",
    "sub" => array() //contains array of sub items with the same format as the parent
)

*/
$page_nav = array(
    "blank" => array(
        "title" => "Home",
        "icon" => "fa-home",
        "url" => "ajax/dashboard.php"
    ),
    "cortes" => array(
    "title" => "Cortes",
    "icon" => "fa-list",
    "url" => "ajax/cortes.php"
    ),
    "recepciones" => array(
    "title" => "Recepciones",
    "icon" => "fa-arrow-circle-down",
    "url" => "ajax/recepciones.php"
    ),
    "contingencias" => array(
    "title" => "Contingencias",
    "icon" => "fa-life-saver",
    "url" => "ajax/contingencias.php"
    ),
    "vehiculos" => array(
    "title" => "Vehiculos",
    "icon" => "fa-car",
    "url" => "ajax/vehiculos.php"
    ),
    "pases" => array(
    "title" => "Pases",
    "icon" => "fa-ticket",
    "url" => "ajax/pases.php"
    )
    ,
    "usuarios" => array(
    "title" => "Usuarios",
    "icon" => "fa-user",
    "url" => "ajax/usuarios.php"
    )
    ,
    "salir" => array(
    "title" => "Salir",
    "icon" => "fa-sign-out",
    "url" => "ajax/logout.php"
    )
);
/*
Cortes
Recepciones
Contingencias
Vehiculos
Pases
*/

//configuration variables
$page_title = "Siteomat 6";
$page_css = array();
$no_main_header = false; //set true for lock.php and login.php
$page_body_prop = array("class"=>""); //optional properties for <body>
$page_html_prop = array(); //optional properties for <html>
