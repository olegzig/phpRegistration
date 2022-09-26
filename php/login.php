<?php
require_once "../vendor/autoload.php";

use Jajo\JSONDB;

header('Access-Control-Allow-Origin: *');
class User
{
    public $login, $email, $password, $name;
    private $jsdb;

    function __construct($login, $email, $password, $name)
    {
        $this->name = $name;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
        $this->jsdb = new JSONDB('../files/');
    }

    //Проверяем логин на уникальность. если уникально, возвращает true
    function isLoginUnic()
    {
        //получем из бд значение
        $jsdb = new JSONDB('../files/');
        $loginLIstArray = $jsdb->select("login")->from('db.json')->get();
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
    }
    //Изменяем (не используется, но надо по ТЗ)
    function change()
    {
    }
    //Получаем инфу (не используется, но надо по ТЗ)
    function read()
    {
    }
    //для шифрования пароля. Мб понадобится
    function passwCrypt()
    {
        return md5($this->name . $this->password);
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
$user = new User($_POST['login'], $_POST['email'], $_POST['password'], $_POST['name']);
$user->create();

//сессия
session_start();
$_SESSION['user'] = [
    "name" => $_POST['name']
];
$user->saveCookie();

//возвращаем
$response = [
    "status" => true,
];
echo json_encode($response);