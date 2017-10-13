<?php include('views/header.php');?>

<div class="container height100">
    <div class="row height100">
        <div class="col-sm-4 col-sm-offset-4 height100">
            <div class="table-parent height80"><div class="table-cell table-cell-vcenter">
                <?php
                    if (!empty($statusMessage)){
                        echo "<div id='' class='alert alert-" . $statusType . " notif-alert' role='alert'>";
                        echo $statusMessage;
                        echo "</div>";
                    }
                ?>
                <h3>Let's Get started</h3>
                <h4>Create an admin user</h4>
                <div class="panel panel-default">
                    <div class="panel-body">

                        <form id="login-form" method="POST" action="<?php echo $baseurl; ?>">
                            <div class="form-group">
                                <label for="newuseremail">Email address</label>
                                <input type="email" name="newuseremail" class="form-control" id="newemail" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="newuserpassword">Password</label>
                                <input type="password" name="newuserpassword" class="form-control" id="newpassword" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="passwordconfirm">Confirm Password</label>
                                <input type="password" name="passwordconfirm" class="form-control" id="confirmpassword" placeholder="Confirm Password">
                            </div>
                            <div class="form-group">
                                <label for="newuserfirstname">First Name</label>
                                <input type="text" name="newuserfirstname" class="form-control" id="newuserfirstname" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <label for="newuserlastname">Last Name</label>
                                <input type="text" name="newuserlastname" class="form-control" id="newuserlastname" placeholder="First Name">
                            </div>
                            <input type="hidden" name="newuseradmin" value="1">
                            <input type="hidden" name="action" value="createuser">
                            <button type="submit" name="submit" class="btn btn-primary pull-right">
                                Create User
                            </button>
                        </form>

                    </div>
                </div> <!--/panel-->
            </div></div> <!--/tables-->
        </div> <!--/col-->
    </div> <!--/row-->
</div> <!--/container-->

<?php include('views/commonjs.php');?>

<?php include('views/ender.php');?>