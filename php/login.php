<?php
session_start();
require_once "../vendor/autoload.php";
header('Access-Control-Allow-Origin: *');


use Jajo\JSONDB;
class User
{
    public $login, $email, $password, $name;
    private $jsdb;

    function __construct($login, $email, $password, $name)
    {
        $this->name = $name;
        $this->login = $login;
        $this->email = strtolower($email);
        $this->password = $password;
        $this->jsdb = new JSONDB('../files/');
    }

    //Проверяем логин на уникальность. если уникально, возвращает true
    function isLoginUnic()
    {
        //получем из бд значение

        $loginLIstArray = $this->jsdb->select("login")->from('db.json')->get();
        $loginLIst = array();

        for ($i = 0; $i < count($loginLIstArray); ++$i) {
            $loginLIst[] = $loginLIstArray[$i]["login"];
        }

        //если запись есть, вернуть false
        if (in_array($this->login, $loginLIst)) {
            return false;
        } else {
            return true;
        }
    }
    //Проверяем емейл на уникальность. если уникально, возвращает true
    public function isEmailUnic()
    {
        //получем из бд значение
        $emailLIstArr = $this->jsdb->select('email')->from('db.json')->get();
        $emailLIst = array();

        for ($i = 0; $i < count($emailLIstArr); ++$i) {
            $emailLIst[] = $emailLIstArr[$i]["email"];
        }

        //если запись есть, вернуть false
        if (in_array($this->email, $emailLIst) == true) {
            return false;
        } else {
            return true;
        }
    }
    function isUnic()
    {
        $field = null;
        if (!$this->isEmailUnic()) {
            $field[] = 'email';
        }
        if (!$this->isLoginUnic()) {
            $field[] = 'login';
        }

        if ($field != null) {
            $response = [
                "status" => false,
                "fields" => $field
            ];

            echo json_encode($response);
            die();
        }
    }
    //Создаём акк
    function create()
    {
        //соль = имя
        $buf = array("name" => $this->name, "login" => $this->login, "email" => $this->email, "password" => $this->passwCrypt());

        $this->isUnic();

        $jsdb = new JSONDB('../files/');
        $jsdb->insert('db.json', $buf);
    }
    //Удаляем (не используется, но надо по ТЗ)
    function delete()
    {
        //удалять тоже по логину будем
        $this->jsdb->delete()->from('db.json')->where(['login' => $this->login])->trigger();
    }
    //Изменяем (не используется, но надо по ТЗ)
    function change()
    {
        //менять будем по логину
        $buf = array("name" => $this->name, "login" => $this->login, "email" => $this->email, "password" => $this->passwCrypt());
        $this->jsdb->update($buf)->from('db.json')->where(['login' => $this->login])->trigger();
    }
    //Получаем инфу (не используется, но надо по ТЗ)
    function read()
    {
        //получем из бд значение
        $usersSaved = $this->jsdb->select('login, password, name')->from('db.json')->get();
        $varOnce = true;
        for ($i = 0; $i < count($usersSaved); ++$i) {
            $userPassword = $usersSaved[$i]["password"];
            $userLogin = $usersSaved[$i]["login"];
            if ($userLogin == $this->login &&  $userPassword == $this->passwCrypt()) {
                $this->name = $usersSaved[$i]["name"];
                $varOnce = false;
                break;
            }
        }
        if ($varOnce) {
            $response = [
                "status" => false,
            ];
            echo json_encode($response);
            die();
        }
    }
    //для шифрования пароля. Мб понадобится
    function passwCrypt()
    {
        return md5($this->login . $this->password);
    }
    public function saveCookie()
    {
        setcookie("email", $this->email, time() + (86400 * 30), '/', null, true);
        setcookie("password", $this->password, time() + (86400 * 30), '/', null, true);
        setcookie("login", $this->login, time() + (86400 * 30), '/', null, true);
        setcookie("name", $this->name, time() + (86400 * 30), '/', null, true);
    }
}

//делаем класс и подлучаем данные с формы
//проверям пришёл запрос через ajax или нет
if (@$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
    $user = new User($_POST['login'], $_POST['email'], $_POST['password'], $_POST['name']);
    $purpose = $_POST['purpose'];
    if ($purpose == "register") {
        $user->create();
    } else {
        $user->read();
    }

    //сессия

    $_SESSION['user'] = [
        "name" => $user->name
    ];
    $user->saveCookie();

    //возвращаем
    $response = [
        "status" => true,
    ];
    echo json_encode($response);
}
