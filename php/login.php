<?php
use Jajo\JSONDB;
require_once "../vendor/autoload.php";
header('Access-Control-Allow-Origin: *');
class User
{
    public $login, $email, $password, $name;

    function __construct($login, $email, $password, $name)
    {
        $this->name = $name;
        $this->login = $login;
        $this->email = $email;
        $this->password = $password;
    }

    //Проверяем логин на уникальность
    function isLoginUnic()
    {
        $jsdb = new JSONDB('../files/');

    }
    //Проверяем емейл на уникальность
    public function isEmailUnic()
    {
        $jsdb = new JSONDB('../files/');

    }
    //Создаём акк
    function create()
    {
        //соль = имя
        $buf = array("name" => $this->name, "login" => $this->login, "email" => $this->email, "password" => md5($this->name.$this->password));

        $this->isEmailUnic();
        $this->isLoginUnic();

        $jsdb = new JSONDB('../files/');
        $jsdb->insert('db.json',$buf);
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

    }
    //json errors
    function jsonErrors()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                echo 'Ошибок нет';
                break;
            case JSON_ERROR_DEPTH:
                echo 'Достигнута максимальная глубина стека';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                echo 'Некорректные разряды или несоответствие режимов';
                break;
            case JSON_ERROR_CTRL_CHAR:
                echo 'Некорректный управляющий символ';
                break;
            case JSON_ERROR_SYNTAX:
                echo 'Синтаксическая ошибка, некорректный JSON';
                break;
            case JSON_ERROR_UTF8:
                echo 'Некорректные символы UTF-8, возможно неверно закодирован';
                break;
            default:
                echo 'Неизвестная ошибка';
                break;
        }
    }
}

//делаем класс и подлучаем данные с формы
$user = new User($_POST['login'], $_POST['email'], $_POST['password'], $_POST['name']);
$user -> create();
?>