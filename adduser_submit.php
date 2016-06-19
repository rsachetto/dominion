<?php
require("password.php");
include("config.php");

/*** begin our session ***/
session_start();

$message = '';

$edit = $_POST['edit'];

/*** first check that both the username, password and form token have been sent ***/
if(!isset( $_POST['username'], $_POST['password'], $_POST['token']))
{
    $message = 'Please enter a valid username and password';
}
/*** check the form token is valid ***/
elseif( $_POST['token'] != $_SESSION['form_token'])
{
    $message = 'Erro ao submeter';
}
/*** check the username is the correct length ***/
elseif (strlen( $_POST['username']) > 20 || strlen($_POST['username']) < 4)
{
    $message = 'O nome de usuário deve ter no máximo 20 e no mínimo 4 letras';
}
/*** check the password is the correct length ***/
elseif (strlen( $_POST['password']) > 20 || strlen($_POST['password']) < 4)
{
    $message = 'A senha deve ter no máximo 20 e no mínimo 4 letras';
}
/*** check the username has only alpha numeric characters ***/
elseif (ctype_alnum($_POST['username']) != true)
{
    /*** if there is no match ***/
    $message = 'Nome de usuário inválido!';
}
elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $message = 'Email inválido!';
}
else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $city = filter_var($_POST['city'], FILTER_SANITIZE_STRING);
    $state = filter_var($_POST['state'], FILTER_SANITIZE_STRING);

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);

    /*** now we can encrypt the password ***/
    $password =  sha1( $password);

    if(isset($_POST['role']))
        $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);;

    try
    {
        /*** prepare the insert ***/
        if($edit == 'false') {
            $stmt = $dbh->prepare("INSERT INTO dominion.user (username, password, name, email, city, state, role ) VALUES (:username, :password,  :name, :email, :city, :state, :role )");
            $stmt->bindParam(':password', $password, PDO::PARAM_STR, 40);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR, 40);
        }
        else {
            $u_id = $_POST['u_id'];
            $stmt = $dbh->prepare("UPDATE dominion.user SET username=:username, name=:name, email=:email, state=:state, city=:city WHERE id=:u_id");
            $stmt->bindParam(':u_id', $u_id, PDO::PARAM_STR);
        }

        /*** bind the parameters ***/
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR, 40);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR, 40);
        $stmt->bindParam(':city', $city, PDO::PARAM_STR, 40);
        $stmt->bindParam(':state', $state, PDO::PARAM_STR, 40);


        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** unset the form token session variable ***/
        unset( $_SESSION['form_token'] );


    }
    catch(Exception $e)
    {
        /*** check if the username already exists ***/
        if( $e->getCode() == 23000)
        {
            $message = 'Nome de usuário já cadastrado';
        }
        else
        {
            /*** if we are here, something has gone wrong with the database ***/
            $message = 'Erro ao realizar cadastro. Tente novamente mais tarde';
        }
    }
}

if(empty($message)) {
    $response_array['status'] = 'success';
}
else {
    $response_array['status'] = $message;
}

header('Content-type: application/json');
echo json_encode($response_array);

?>