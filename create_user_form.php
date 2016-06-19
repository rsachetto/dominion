<?php

include 'helpers.php';
$name = "";
$username = "";
$state = "";
$city= "";
$email = "";
$edit=false;

if(isset($_GET['edit']) && $_GET['edit'] == 'true') {

    include('session.php');
    $edit = true;

    $user_id = $_SESSION['user_id'];

    $user_info = get_user_info($dbh, $user_id);

    $username = $user_info['username'];
    $name = $user_info['name'];
    $email = $user_info['email'];
    $state = $user_info['state'];
    $city = $user_info['city'];
}

if (!$edit) {
    echo '<h2>Cadastrar Usuário</h2>';
    /*** begin our session ***/
    session_start();

    /*** set a form token ***/
    $form_token = md5( uniqid('auth', true) );

    /*** set the session form token ***/
    $_SESSION['form_token'] = $form_token;
}
else {
    echo '<h2>Editar Usuário</h2>';

    /*** set a form token ***/
    $form_token = md5( uniqid('auth', true) );

    /*** set the session form token ***/
    $_SESSION['form_token'] = $form_token;
}
?>


<form role="form" id="user-form">
    <div class="form-group">
        <label for="name">Nome de Usuário:</label>
        <input type="text" class="form-control" id="username" placeholder="Nome de Usuário" required value="<?php echo $username; ?>" >
    </div>
    <div class="form-group">
        <label for="name">Nome real:</label>
        <input type="text" class="form-control" id="name" placeholder="Nome real" value="<?php echo $name; ?>" >
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" class="form-control" id="email" placeholder="Email" required value="<?php echo $email; ?>">
    </div>
    <div class="form-group"id="div-password">
        <label for="password">Senha:</label>
        <input type="password" class="form-control" id="password" name="password"  required placeholder="Senha">
    </div>
    <div class="row">
        <div class="col-xs-4">
            <label for="states">Estado: </label><select class="form-control" id="states"></select>
        </div>
        <div class="col-xs-4">
            <label for="cities">Cidade: </label>
            <select class="form-control" id="cities"></select>
        </div>
    </div>
    <br/>
    <button type="submit" id="submit-bnt" class="btn btn-primary">Salvar</button>
</form>
<script type="text/javascript">

    jQuery(document).ready(function() {

        var edit = <?php echo json_encode($edit);?>;

        if (edit) {
            $("#div-password").html('<div class="well"> <button type="button" id="change-password" ' +
                                    'class="btn">Redefinir senha</button></div> <div id="myForm" class="hide">' +
                                    '<form action="#" id="popForm" method="get"><div class="form-group"><label ' +
                                    'for="current-password">Senha atual:</label><input type="password" class="form-control" ' +
                                    'id="current-password" name="password"  required placeholder="Senha"></div><div class="form-group">' +
                                    '<label for="new-password">Nova senha:</label><input type="password" class="form-control" ' +
                                    'id="new-password" name="password2"  required placeholder="Senha"></div>' +
                                    '<div class="form-group"><label for="new-password2">Repetir nova senha:</label>' +
                                    '<input type="password" class="form-control" id="new-password2" name="password3"  ' +
                                    'required placeholder="Senha"></div><button type="button" class="btn btn-primary" >' +
                                    '<em class="icon-ok"></em>Salvar</button></div></form></div>');

            $('#change-password').popover({
                placement: 'bottom',
                title: 'Redefinir senha',
                html:true,
                content:  $('#myForm').html()
            }).on('click', function(){
                // had to put it within the on click action so it grabs the correct info on submit
                $('.btn-primary').click(function(){

                    var post_data = 'current_password='+ $('#current-password').val() + '&new_password='+ $('#new-password').val() + '&new_password2='+ $('#new-password2').val();

                    $.ajax({
                        type: "POST",
                        url: "change_password.php",
                        data: post_data,
                        success: function(data) {
                            if(data.status == 'success'){
                                alert("Senha redefinida com sucesso!");
                                $('#change-password').popover('hide');
                               // window.location = 'login.php'
                            }else {
                                alert(data.status);
                            }
                        }
                    });
                })
            })

        }

        $.getJSON('js/estados_cidades.json', function (data) {
            var items = [];
            var options = '<option value="">escolha um estado</option>';
            $.each(data, function (key, val) {

                var state = <?php if($edit) echo '"'.$state.'"'; else echo "''";?>;
                if(val.nome == state)
                    options += '<option selected="selected" value="' + val.nome + '">' + val.nome + '</option>';
                else
                    options += '<option value="' + val.nome + '">' + val.nome + '</option>';
            });
            $("#states").html(options);

            $("#states").change(function () {

                var options_cidades = '';
                var str = "";

                $("#states option:selected").each(function () {
                    str += $(this).text();
                });

                $.each(data, function (key, val) {
                    if(val.nome == str) {
                        $.each(val.cidades, function (key_city, val_city) {
                            var city = <?php if($edit) echo '"'.$city.'"'; else echo "''";?>;
                            if(val_city == city)
                                options_cidades += '<option selected="selected" value="' + val_city + '">' + val_city + '</option>';
                            else
                                options_cidades += '<option value="' + val_city + '">' + val_city + '</option>';
                        });
                    }
                });
                $("#cities").html(options_cidades);

            }).change();

        });


        $('#submit-bnt').click(function( event ) {
            event.preventDefault();

            <?php
                if($edit) {
                 echo 'var userId ='.$_SESSION['user_id'].';';
                }
                echo 'var token ="'.$form_token.'";';
            ?>
            
            var username = $('#username').val();
            var name = $('#name').val();
            var password = $('#password').val();
            var email = $('#email').val();
            var state = $( "#states option:selected" ).text();
            var city = $( "#cities option:selected" ).text();


            <?php
            if(!$edit) {
                echo 'post_data = "token=" + token + "&role=player" + "&username=" + username + "&name=" + name + "&password=" + password + "&email=" + email + "&city=" + city + "&state=" + state + "&edit=false";';
            }
            else {
                echo "var uid=".$user_id.";";
                echo 'post_data = "token=" + token + "&u_id=" + uid + "&username=" + username + "&name=" + name + "&password=" + password + "&email=" + email + "&city=" + city + "&state=" + state + "&edit=true";';
            }
            ?>

            $.ajax({
                type: "POST",
                url: "adduser_submit.php",
                data: post_data,
                success: function(data) {
                    if(data.status == 'success'){
                        if(!edit) {
                            alert("Usuário adicionado com sucesso. Realize seu login!");
                            window.location = 'login.php'
                        }
                        else {
                            alert("Usuário atualizado com sucesso!");
                        }
                    }else {
                        alert(data.status);
                    }
                }
            });
        })
    });
</script>
