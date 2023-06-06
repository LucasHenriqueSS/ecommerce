<?php

// composer - trazer as dependencias do projeto.
require_once("vendor/autoload.php");

// namespace para trazer as classes desejadas.
use \Slim\Slim;
use \Hcode\Page;

// definindo as rotas
$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function(){

	// instancia na classe page para gerar uma nova pagina
	$page = new Page();

	//carrega o conteudo do template index. que é o arquivo HTMl principal.
	$page->setTpl("index");

});

// dá o "start" para rodar após tudo definido.
$app->run();

?>