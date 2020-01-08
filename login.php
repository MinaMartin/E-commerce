<?php
include "initial.php";
include  $tmpl . "Header.php" ;

    


?>
<div class="container login-page text-center mt-5">
    <div class="row">
        <div class="col-12">
            <form action="" method="POST">
                <P class="display-4" ><?php if($_GET['Action']=="Login") {echo "Login";}else{echo "Sign Up";} ?> Form</P>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" class="form-control" 
                    name="username" autocomplete="off" required />
                </div>
                <br>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control" name="password" autocomplete="new-password" required/>
                </div>
                <div class="form-group" style="display:<?php if($_GET['Action']=="Login") {echo "none;";} ?>">
                    <label for="email">Email</label>
                    <input type="email" id="email" class="form-control" name="password" autocomplete="off"/>
                </div>
                <button type="submit" class="btn btn-primary">
                    <?php
                        if(isset($_GET['Action'])){
                            $method=$_GET['Action'];
                            if($method == "Login") {echo "Login";}else{echo "Sign Up";} 
                        }else{
                            echo 'Login / Sign up';
                        }
                    ?>
                </button>
            </form>
        </div>
    </div>       
</div>
<?php
include $tmpl . "footer.php" ;
?>
