<div class="col-md-3">
    <div class="profile-sidebar">
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                <?php echo $_SESSION['name']; ?>
            </div>
            <div class="profile-usertitle-job">
                Administrador
            </div>
        </div>
        <!-- END SIDEBAR USER TITLE -->
        <!-- SIDEBAR MENU -->
        <div class="profile-usermenu">
            <ul class="nav">
                <li class="active">
                    <a href="#" id="overview">
                        <i class="glyphicon glyphicon-home"></i>
                        Overview </a>
                </li>
                <li>
                    <a href="#" id="add-organizer">
                        <i class="glyphicon glyphicon-plus"></i>
                        Gerenciar organizadores </a>
                </li>
                <li>
                    <a href="#" id="account">
                        <i class="glyphicon glyphicon-user"></i>
                        Minha Conta </a>
                </li>
                <li>
                    <a href="logout.php">
                        <i class="glyphicon glyphicon-remove"></i>
                        Sair </a>
                </li>
            </ul>
        </div>
        <!-- END MENU -->
    </div>
</div>