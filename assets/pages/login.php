    <div class="login">
        <div class="col-sm-12 col-md-4 bg-white border rounded p-4 shadow-sm" style="border-radius:2rem !important ;">
            <!-- LOGIN SIDE LOGO STUFF-->
            <div class="loginPageLogo">
                <img src="assets/images/printerflowLogoColored.png" alt="printerflow_logo" height="300" padding-bottom="10" style="padding-bottom: 10px; border-radius:3rem !important ;">
            </div>
             <!-----------LOGIN FORM START-->
            <form method="post" action="assets/php/actions.php?login">
                <h1 class="h5 mb-3 fw-normal text-dark">Please Sign in</h1>
                <div class="form-floating">
                    <!--username input field-->
                    <input type="text" name="username_email" value="<?=showFormData('username_email')?>" class="form-control rounded-0" placeholder="username/email">
                    <label for="floatingInput" class="text-dark">username/email</label>
                </div>
                <?=showError('username_email')?><!--Required field. Throw ERROR if empty-->
                <div class="form-floating mt-1">
                    <!--password input field-->
                    <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword" class="text-dark">password</label>
                </div>
                <?=showError('password')?><!--Check password field. Throw ERROR if empty-->
                <?=showError('checkuser')?><!--Check for existing user. Throw ERROR if empty-->

                <!-- USER SIGN IN-->
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary text-dark" type="submit">Sign in</button>
                     <!-- CREATE NEW ACCOUNT-->
                    <a href="?signup" class="text-decoration-none text-dark">Create New Account</a>


                </div>
                 <!-- FORGOT PASSWORD STUFF-->
                <a href="?forgotpassword&newfp" class="text-decoration-none text-dark">Forgot password ?</a>
            </form>
              <!--------------LOGIN FORM END-->
        </div>
    </div>

