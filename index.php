<?php

session_start();
// composer - trazer as dependencias do projeto.
require_once("vendor/autoload.php");

// namespace para trazer as classes desejadas.
use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use Hcode\Model\User;

// definindo as rotas
$app = new \Slim\Slim();

$app->config('debug', true);

$app->get('/', function(){

	// instancia na classe page para gerar uma nova pagina
	$page = new Page();

	//carrega o conteudo do template index. que é o arquivo HTMl principal.
	$page->setTpl("index");

});

$app->get('/admin/', function(){

	User::verifyLogin();

	// instancia na classe page para gerar uma nova pagina
	$page = new PageAdmin();

	//carrega o conteudo do template index. que é o arquivo HTMl principal.
	$page->setTpl("index");

});


$app->get('/admin/login', function(){

	// instancia na classe page para gerar uma nova pagina
	$page = new PageAdmin([

		"header"=>false,
		"footer"=>false
	]);

	//carrega o conteudo do template login. 
	$page->setTpl("login");

});


$app->post('/admin/login', function(){

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;

});

$app->get('/admin/logout', function(){

	User::logout();
	header("Location: /admin/login");
	exit;

});

// dá o "start" para rodar após tudo definido.
$app->run();

?>