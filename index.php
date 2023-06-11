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

	// instancia na classe pageAdmin para gerar uma nova pagina
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


//após o dados validados abre a pagina de acesso Admin.
$app->post('/admin/login', function(){

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /admin");
	exit;

});


// Determina a rota e redireciona o usuario após efetuar o deslogin.
$app->get('/admin/logout', function(){

	User::logout();
	header("Location: /admin/login");
	exit;

});

// READ - Define a rota verifica se o usuario está logado e abre o template.

$app->get("/admin/users", function(){

	User::verifyLogin();

	$users = User::listAll();

	$page = new PageAdmin();

	$page->setTpl("users", array(

		"users"=>$users

	));

});

//CREATE - Define a rota verifica se o usuario está logado e abre p template.

$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("users-create");

});

//DELETE - rota para deletar um usuario utilizando seu ID.
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();
	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: /admin/users");
	exit;

});

//UPDATE - Define a rota utilizando o ID do usuario que será atualizado, verifica se o usuario está logado e abre o template.

$app->get('/admin/users/:iduser', function($iduser){
 
   User::verifyLogin();
 
   $user = new User();
 
   $user->get((int)$iduser);
 
   $page = new PageAdmin();
 
   $page ->setTpl("users-update", array(
        "user"=>$user->getValues()
    ));
 
});

//Rota para salvar os dados no banco do CREATE.
$app->post("/admin/users/create", function () {

 	User::verifyLogin();

	$user = new User();

 	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

 	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		"cost"=>12

 	]);

 	$user->setData($_POST);

	$user->save();

	header("Location: /admin/users");
 	exit;

});


$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: /admin/users");
	exit;

});




// dá o "start" para rodar após tudo definido.
$app->run();

?>