<div class="col-md-3">
    <div class="profile-sidebar">
        <div>
            <img alt="Dominion" src="img/logo.gif" class="img-responsive"  style="width:250px;margin:auto;">
        </div>
        <!-- SIDEBAR USER TITLE -->
        <div class="profile-usertitle">
            <div class="profile-usertitle-name">
                <?php echo $_SESSION['name']; ?>
            </div>
            <div class="profile-usertitle-job">
                Organizador
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
                    <a href="#" id="add-champ">
                        <i class="glyphicon glyphicon-plus"></i>
                        Criar novo campeonato </a>
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