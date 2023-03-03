<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();
// Create Twig
$twig = Twig::create('./templates', ['cache' => false]);
// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));

// new AgendamentoController().listar();

$app->get('/listar_usuarios', function (Request $request, Response $response, $args) {
    $mysqli = new mysqli("localhost", "root", "root", "agendamento_users");
    $result = $mysqli->query("SELECT * FROM cms_usuario LIMIT 10");
    // printf("Select returned %d rows.\n", $result->num_rows);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $response->getBody()->write($row["id_usuario"].",".$row["nome"]."<br>");
    }
    return $response;
});
/*

$app->get('/listar_agendamentos', function (Request $request, Response $response, $args) {
    $mysqli = new mysqli("localhost", "root", "root", "agendamento");
    $result = $mysqli->query("SELECT * FROM agendamentos LIMIT 10");
    // printf("Select returned %d rows.\n", $result->num_rows);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $response->getBody()->write($row["id"].",".$row["data"]."<br>");
    }
    return $response;   
    
});*/

$app->get('/listar_agendamentos', function (Request $request, Response $response, $args) {
    $mysqli = new mysqli("localhost", "root", "root", "agendamento");
    $result = $mysqli->query("SELECT * FROM agendamentos LIMIT 10");
    // printf("Select returned %d rows.\n", $result->num_rows);
    $vetAgendamento = $result->fetch_all(MYSQLI_ASSOC);
    /*while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $response->getBody()->write($row["id"].",".$row["data"]."<br>");
    }*/
    $view = Twig::fromRequest($request);
    return $view->render($response, 'agendamentos/listar.html', [
        'vetAgendamento' => $vetAgendamento,
    ]);
})->setName('listar_agendamentos');



$app->get('/listar_laboratorios', function (Request $request, Response $response, $args) {
    $mysqli = new mysqli("localhost", "root", "root", "agendamento");
    $result = $mysqli->query("SELECT * FROM laboratorios LIMIT 10");
    // printf("Select returned %d rows.\n", $result->num_rows);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $response->getBody()->write($row["id"].",".$row["nome"]."<br>");
    }
    return $response;
});

$app->get('/listar_horarios', function (Request $request, Response $response, $args) {
    $mysqli = new mysqli("localhost", "root", "root", "agendamento");
    $result = $mysqli->query("SELECT * FROM horarios LIMIT 10");
    // printf("Select returned %d rows.\n", $result->num_rows);
    while ($row = $result->fetch_array(MYSQLI_ASSOC)){
        $response->getBody()->write($row["id"].",".$row["hora_inicio"].",".$row["hora_fim"]."<br>");
    }
    return $response;
});

$app->get('/tela_adicionar_agendamento', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'agendamentos/tela_adicionar.html'/*, [
        'name' => $args['name'],
    ]*/);
})->setName('tela_adicionar_agendamento');

$app->post('/adicionar_agendamento', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});


$app->get('/tela_editar_agendamento', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'tela_editar.html'/*, [
        'name' => $args['name'],
    ]*/);
})->setName('tela_editar_agendamento');

$app->post('/editar_agendamento/{id}', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/delete_agendamento/{id}', function (Request $request, Response $response, $args) {
    $id = $args['id'];
    $mysqli = new mysqli("localhost", "root", "root", "agendamento");
    $result = $mysqli->query("DELETE FROM agendamentos WHERE id = $id");
    $response->getBody()->write("ok");
    return $response;
});

$app->post('/add_lab', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->post('/editar_lab', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/delete_lab/{id}', function (Request $request, Response $response, $args) {
    // $response->getBody()->write("Hello world!");
    $id = $args['id'];
    $mysqli = new mysqli("localhost", "root", "root", "agendamento");
    $result = $mysqli->query("DELETE FROM laboratorios WHERE id = $id");
    $response->getBody()->write("ok");
    return $response;
});

$app->get('/', function (Request $request, Response $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'index.html'/*, [
        'name' => $args['name'],
    ]*/);
})->setName('index');

/*
//  ex. templates
$app->get('/hello/{name}', function ($request, $response, $args) {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'profile.html', [
        'name' => $args['name'],
    ]);
})->setName('profile');*/

$app->run();
