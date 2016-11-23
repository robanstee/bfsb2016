<?php
error_reporting(E_ALL);



?>
<html>
    <head>
        <link href="../css/jquery-ui.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-1.10.2.js"></script>

    </head>
    <body>
  
           
                <section class="container">
                    <div id="login_results"></div>
                    <div class="login" id="login_form">
                        <h1>Login</h1>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <?php echo $sess->loginresult; ?>
                            <p><input type="text" id="login" name="login" value="" placeholder="Username or Email"></p>
                            <p><input type="password" id="password"name="password" value="" placeholder="Password"></p>
                            <p class="remember_me">
                                <label>
                                    <input type="checkbox" name="remember_me" id="remember_me" value="true">
                                    Remember me on this computer
                                </label>
                            </p>
                            <p class="submit"><input type="submit"  name="commit" value="Login"></p>
                        </form>
                    </div>

                    <div class="login-help">
                        <p>Forgot your password? <a href="resetpassword">Click here to reset it</a>.</p>
                        <p>New user? <a href="newuser">Click here to set up an account!</a>.</p>
                    </div>
                </section>

        



         
    </body>



</html>
