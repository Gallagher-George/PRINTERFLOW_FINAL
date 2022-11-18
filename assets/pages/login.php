
    <div class="login">
        <div class="col-sm-12 col-md-4 bg-white border rounded p-4 shadow-sm">
            <form method="post" action="assets/php/actions.php?login">
                <div class="d-flex justify-content-center">

                    <img class="mb-4" src="assets/images/pictogram.png" alt="" height="45">
                </div>
                <h1 class="h5 mb-3 fw-normal text-dark">Please sign in</h1>

                <div class="form-floating">
                    <input type="text" name="username_email" value="<?=showFormData('username_email')?>" class="form-control rounded-0" placeholder="username/email">
                    <label for="floatingInput" class="text-dark">username/email</label>
                </div>
                <?=showError('username_email')?>
                <div class="form-floating mt-1">
                    <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword" class="text-dark">password</label>
                </div>
                <?=showError('password')?>
                <?=showError('checkuser')?>


                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <button class="btn btn-primary text-dark" type="submit">Sign in</button>
                    <a href="?signup" class="text-decoration-none text-dark">Create New Account</a>


                </div>
                <a href="?forgotpassword&newfp" class="text-decoration-none text-dark">Forgot password ?</a>
            </form>
        </div>
    </div>

