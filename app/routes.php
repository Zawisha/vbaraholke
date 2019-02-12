<?php

//для вызова класса. МОЖНО ВЫЗЫВАТЬ КЛАСС ПРЯМО ИЗ КОНСТРУКТОРА БЕЗ СОЗДАНИЯ ЕГО МЕТОДА
use Aura\SqlQuery\QueryFactory;
use DI\Container;
use League\Plates\Engine ;

//так можно запихивать значения в конструктор с помощью DI
$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder ->addDefinitions(
    [
        Engine::class => function(){
            //передача строки при вызове класса
            return new Engine('../app/views');
        },
        PDO::class => function(){
            return new PDO("mysql:host=localhost;dbname=vbaraholkeby","vbaraholkeby","JZV3PSZv1uyt");
        },
        QueryFactory::class => function(){
            return new QueryFactory('mysql');
        }
        ]
);
$container = $containerBuilder->build();
function abort($type)
{
    switch ($type) {
        case 404:
            $templates = new League\Plates\Engine('../app/views');
            echo $templates->render('404');exit;
            break;
    }
}


$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ["App\controllers\HomeController", "index"]);
    $r->addRoute('GET', '/?page={id:\d+}', ["App\controllers\HomeController", "index"]);
    $r->addRoute('GET', '/?page={id:\d+}&city={ci:\d+}&category={cat:\d+}', ["App\controllers\HomeController", "index"]);
    $r->addRoute('GET', '/registration.php', ["App\controllers\RegisterController", "showForm"]);
    $r->addRoute('POST', '/register', ["App\controllers\RegisterController", "RegAndSend"]);
    $r->addRoute('GET', '/users', ["App\controllers\HomeController", "index"]);
    //работа с картинками
    $r->addRoute('GET', '/show/{id}', ["App\controllers\HomeController", "show"]);
    $r->addRoute('POST', '/image', ["App\controllers\HomeController", "image"]);
    //Работа с аватаркой
    $r->addRoute('GET', '/changeava', ["App\controllers\ImageController", "avaimage"]);
    $r->addRoute('POST', '/uploadavaimage', ["App\controllers\ImageController", "uploadAvaImage"]);
    //отправка письма
    $r->addRoute('GET', '/send', ["App\controllers\RegisterController", "SendMail"]);
    $r->addRoute('GET', '/confirm' , ["App\controllers\RegisterController", "confirm"]);
    $r->addRoute('GET', '/login.php' , ["App\controllers\LoginController", "login"]);
    $r->addRoute('POST', '/log' , ["App\controllers\LoginController", "authoriz"]);
    $r->addRoute('GET', '/islog' , ["App\controllers\LoginController", "isLogin"]);
    $r->addRoute('GET', '/logout.php' , ["App\controllers\LoginController", "logout"]);
    $r->addRoute('GET', '/account.php' , ["App\controllers\ProfileController", "userInformation"]);
    $r->addRoute('GET', '/resetpassword' , ["App\controllers\LoginController", "resetpass"]);
    $r->addRoute('POST', '/respassword' , ["App\controllers\LoginController", "reslink"]);
    $r->addRoute('GET', '/respas' , ["App\controllers\LoginController", "respas"]);
    $r->addRoute('POST', '/newpassword' , ["App\controllers\LoginController", "newpassword"]);
    $r->addRoute('GET', '/changepass' , ["App\controllers\LoginController", "ChangePassword"]);
    $r->addRoute('POST', '/newpasslog' , ["App\controllers\LoginController", "NewPassLog"]);
    $r->addRoute('GET', '/showpost' , ["App\controllers\ImageController", "gotoPost"]);
    $r->addRoute('GET', '/showpost?numb{id:\d+}' , ["App\controllers\ImageController", "gotoPost"]);
    $r->addRoute('GET', '/addpost' , ["App\controllers\HomeController", "addPost"]);
    $r->addRoute('POST', '/uplimgfuser' , ["App\controllers\ImageController", "valFormPost"]);
    $r->addRoute('POST', '/uplimgfuserpost' , ["App\controllers\ImageController", "uplImgJs"]);
    $r->addRoute('GET', '/thxmoderate' , ["App\controllers\ImageController", "thxmod"]);
    $r->addRoute('GET', '/error' , ["App\controllers\ImageController", "error"]);
    $r->addRoute('GET', '/admin' , ["App\controllers\ProfileController", "adminInsert"]);
    $r->addRoute('GET', '/delete' , ["App\controllers\HomeController", "gotoDelete"]);
    $r->addRoute('GET', '/delete?numbpost{id:\d+}' , ["App\controllers\HomeController", "gotoDelete"]);
    $r->addRoute('GET', '/addadmin' , ["App\controllers\ProfileController", "addAdmin"]);
    $r->addRoute('POST', '/changeusers' , ["App\controllers\AdminController", "changeusersLikeAdmin"]);
    $r->addRoute('GET', '/adminposts' , ["App\controllers\AdminController", "adminpost"]);
    $r->addRoute('GET', '/adminposts?page{id:\d+}' , ["App\controllers\AdminController", "adminpost"]);
    $r->addRoute('POST', '/changeposts' , ["App\controllers\AdminController", "changePostsLikeAdmin"]);
    $r->addRoute('GET', '/showallusers' , ["App\controllers\AdminController", "showUsers"]);
    $r->addRoute('GET', '/showallusers?page{id:\d+}' , ["App\controllers\AdminController", "showUsers"]);
    $r->addRoute('GET', '/userposts' , ["App\controllers\ProfileController", "showuserposts"]);
    $r->addRoute('GET', '/userposts?page{id:\d+}' , ["App\controllers\ProfileController", "showuserposts"]);
    $r->addRoute('POST', '/test' , ["App\controllers\FindController", "deleteOnePost"]);
    $r->addRoute('GET', '/contacts' , ["App\controllers\ProfileController", "contacts"]);
    $r->addRoute('GET', '/rules' , ["App\controllers\ProfileController", "rules"]);
    $r->addRoute('POST', '/avajsload' , ["App\controllers\ImageController", "avajs"]);
    $r->addRoute('GET', '/user' , ["App\controllers\ProfileController", "users"]);
    $r->addRoute('GET', '/user?id{id:\d+}' , ["App\controllers\ProfileController", "users"]);
    $r->addRoute('GET', '/notmineuserposts' , ["App\controllers\ProfileController", "showNotMineUserPosts"]);
    $r->addRoute('GET', '/notmineuserposts?page{id:\d+}&id{numb:\d+}' , ["App\controllers\ProfileController", "showNotMineUserPosts"]);
    $r->addRoute('POST', '/chat' , ["App\controllers\ProfileController", "chat"]);
    $r->addRoute('POST', '/hismesses' , ["App\controllers\ProfileController", "loadHisMesses"]);
    $r->addRoute('POST', '/allloadmesses' , ["App\controllers\ProfileController", "allloadmesses"]);
    $r->addRoute('POST', '/loadnmess' , ["App\controllers\ProfileController", "loadnmess"]);
    $r->addRoute('GET', '/showmessages.php' , ["App\controllers\ProfileController", "showmessages"]);
    $r->addRoute('POST', '/allloadnmess' , ["App\controllers\ProfileController", "allloadnmess"]);
    $r->addRoute('GET', '/authvk' , ["App\controllers\LoginController", "vklog"]);
    $r->addRoute('POST', '/calluser' , ["App\controllers\HomeController", "getNumber"]);
    $r->addRoute('POST', '/validaddnumberandemail' , ["App\controllers\ValidationController", "validateEmailandNumber"]);
    $r->addRoute('GET', '/validaddnumberandemail' , ["App\controllers\ValidationController", "showValidateEmailandNumber"]);
    $r->addRoute('GET', '/ban' , ["App\controllers\LoginController", "banlogout"]);
});
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

//место вызова функции
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:

       abort(404);
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        abort(404);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($handler, $vars);
        break;
}