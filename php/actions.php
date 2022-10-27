<?php
require_once 'functions.php';
require_once 'send_code.php';

if(isset($_GET['test'])){


}

if(isset($_GET['block'])){
    $user_id = $_GET['block'];
    $user = $_GET['username'];
        if(blockUser($user_id)){
            header("location:../../?u=$user");
        }else{
            echo "something went wrong";
        }
}

if(isset($_GET['deletepost'])){
    $post_id = $_GET['deletepost'];
        if(deletepost($post_id)){
            header("location:{$_SERVER['HTTP_REFERER']}");
        }else{
            echo "something went wrong";
        }
}

//for managing signup
if(isset($_GET['signup'])){
    $response=validateSignupForm($_POST);
    if($response['status']){
        if(createUser($_POST)){
            header('location:../..?login&newuser');
        }else{
            echo "<script>alert('something is wrong')</script>";
        }



    }else{
        $_SESSION['error']=$response;
        $_SESSION['formdata']=$_POST;
        header("location:../../?signup");
    }
}

//for managing login
If(isset($_GET['login']))
{

    $response=validateLoginForm($_POST);

    if($response['status'])
    {
        $_SESSION['Auth']= true;
        $_SESSION['userdata']=$response['user'];

        if($response['user']['ac_status']==0)
        {
            $_SESSION['code'] = $code = rand(111111,999999);
            sendCode($response['user']['email'],'Verify Your Email', $code);
        }

        header("location:../../");
    }
        
        
        else
        {
            $_SESSION['error']=$response;
            $_SESSION['formdata']=$_POST;
            header("location:../../?login");
        }


    if(isset($_GET['resend_code'])){

        $_SESSION['code']=$code = rand(111111,999999);
        sendCode($_SESSION['userdata']['email'],'Verify Your Email', $code);
        header('location:../..?resended');
    }

    if(isset($_GET['verify_email'])){
        $user_code = $_POST['code'];
        $code = $_SESSION['code'];
        if($code==$user_code)
        {
            if(verifyEmail($_SESSION['userdata']['email']))
            {
                header('location:../../');
            }
            else
            {
                echo "something is wrong";
            }
        }
        else
        {
            $response['$msg']='incorrect verification code!';
            if(!$_POST['code']){
                $response['msg']='enter 6 digit code !';
            }
            $response['field']='email_verify';
            $_SESSION['error']=$response;
            header('location:../../');
        }
    }


}


if(isset($_GET['forgotpassword']))
{
    if(!$_POST['email'])
    {
        $response['msg']="enter your email id !";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword&resended');
    }
    elseif(!isEmailRegistered($_POST['email']))
    {
        $response['msg']="email id is not registered";
        $response['field']='email';
        $_SESSION['error']=$response;
        header('location:../../?forgotpassword&resended');
    }





}



//logout for user
if(isset($_GET['logout'])){
    session_destroy();
    header('location:../../');
}

//for verify forgot code
if(isset($_GET['verifycode']))
{
    $user_code = $_POST['code'];
    $code = $_SESSION['forgot_code'];
    if($code==$user_code)
    {
        $_SESSION['auth_temp']=true;
        header('location:../../?forgotpassword');
    }
    else
    {
        $response['msg']='incorrect verification code';
        if(!$_POST['code'])
        {
            $response['msg']='enter 6 digit code !';

        }
        $response['field']='email_verify';
        $_SESSION['error']=$response;
        header('location:../..?forgotpassword');
    }
}

if(isset($_GET['updateprofile']))
{
    $response=validateUpdateForm($_POST,$_FILES['profile_pic']);

    if($response['status'])
    {
        If(updateProfile($_POST,$_FILES['profile_pic']))
        {
            header("location:../../?editprofile&success");
        }
        else
        {
            echo "something is wrong";
        }
    }
    else{
        $_SESSION['error']=$response;
        header("location:../../?editprofile");
    }
}

// for managing add posts
if(isset($_GET['addpost']))
{
    $response = validatePostImage($_FILES['post_img']);

    if($response['status'])
    {
        if(createPost($_POST,$_FILES['post_img']))
        {
            header('location:../../?new_post_added');
        }
        elseif()
        {
            echo "something went wrong";
        }
        else
        {
            $_SESSION['error']=$response;
            header("location:../../");
        }
    }
}

?>