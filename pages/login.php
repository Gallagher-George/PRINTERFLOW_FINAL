<div class="login">
    <div class="col-sm-12 col-md-4 bg-white border rounded p-4 shadow-sm">
        <form method="post" action="php/actions.php?login">
            <div class="d-flex justify-content-center">
                <img src="/images/logo1.png" alt="logo" class="mb-4" height="45">
            </div>
            <h1 class="h5 mb-3 fw-normal">Please Sign in</h1>
            <div class="form-floating">
                <input type="text" name="username_email" value="<?=showFormData('username_email')?>" class="form-control rounded-0" placeholder="username/email">
                <label for="floatingInput">Username/Email</label>
            </div>
            <?=showError('username_email')?>
            <div class="form-floating mt-1">
                <input type="password" name="password" class="form-control rounded-0" id="floatingPassword" placeholder="Password">
            </div>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button class="btn btn-primary" type="submit">Sign in</button>
                <a href="?signup" class="text-decoration-none">Create New Account</a>
            </div>
            <a href="?forgotpassword&nbsp" class="text-decoration-none">Forgot password ?</a>
        </form>
    </div>
</div>