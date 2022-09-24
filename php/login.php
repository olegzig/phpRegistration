<?php
class User
{
    public $login, $email, $password, $name;

    function __construct($login, $email, $password, $name)
    {
        $this->$name = $name;
        $this->$login = $login;
        $this->$email = $email;
        $this->$password = $password;
    }

    //Проверяем логин на уникальность
    function isLoginUnic(){
        
    }
    //Проверяем емейл на уникальность
    function isEmailUnic(){
        
    }
    //Создаём акк
    function create(){

    }
    //Удаляем (не используется, но надо по ТЗ)
    function delete(){

    }
    //Изменяем (не используется, но надо по ТЗ)
    function change(){

    }
    //Получаем инфу (не используется, но надо по ТЗ)
    function read(){

    }
    //для шифрования пароля. Мб понадобится
    function passwCrypt(){
        
    }

}

//делаем класс и подлучаем данные с формы
$user = new User($_POST['login'], $_POST['email'], $_POST['password'], $_POST['name']);

?>