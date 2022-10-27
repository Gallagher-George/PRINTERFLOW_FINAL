<?php
require_once 'config/db.php';
$db = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die ("database is not connected");
//function for showing the pages
function showPage($page,$data="")
{
    include("pages/page.php");
}
//for getting ids of chat users
function getActiveChatUserIds()
{
    global $db;
    $current_user_id =$_SESSION['userdata']['id'];
    $query = "SELECT from_user_id,to_user_id FROM messages WHERE to_user_id=$current_user_id || from_user_id=$current_user_id ORDER BY id DESC";
    $run = mysqli_query($db, $query);
    $data = mysqli_fetch_all($run, true);
    $ids=array();
    foreach($data as $ch)
    {
        if($ch['from_user_id'] !=$current_user_id && !in_array($ch['from_user_id'],$ids))
        {
            $ids[]=$ch['from_user_id'];
        }
        if($ch['to_user_id'] !=$current_user_id && !in_array($ch['to_user_id'],$ids))
        {
            $isd[]=$ch['to_user_id'];
        }
    }
    return $ids;
}
//getMessages
function getMessages($user_id)
{
    global $db;
    $current_user_id =$_SESSION['userdata']['id'];//get session for specific user
    $query="SELECT * FROM messages WHERE (to_user_id=$current_user_id && from_user_id=$user_id) || (from_user_id=$current_user_id && to_user_id=$user_id) ORDER BY id DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run, true);
}
//sendMessages
function sendMessage($user_id,$msg){
    global $db;
    $current_user_id = $_SESSION['userdata']['id'];
    $query = "INSERT INTO messages (from_user_id,to_user_id,msg) VALUES($current_user_id,$user_id,'$msg')";
    return mysqli_query($db,$query);
}
//newMsgCount
function newMsgCount($user_id)
{
    global $db;
    $current_user_id =$_SESSION['userdata']['id'];
    $query = "SELECT COUNT(*) as row FROM messages WHERE to_user_id = $current_user_id && read_status =0";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}
//updateMessageReadStatus
function updateMessageReadStatus($user_id){
    $cu_user_id = $_SESSION['userdata']['id'];
    global $db;
    $query="UPDATE messages SET read_status=1 WHERE to_user_id=$cu_user_id && from_user_id=$user_id";
    return mysqli_query($db,$query);
}
//gettime
function gettime($date)
{
    return date('H:i - (F jS, Y)',strtotime($date));
}
//getAllMessages
function getAllMessages()
{
    $active_chat_ids = getActiveChatUserIds();
    $conversion=array();
    foreach($active_chat_ids as $index=> $id)
    {
        $conversion[$index]['user_id']=$id;//[KEY][VALUE]
        $conversion[$index]['messages']=getMessages($id);
    }
    return $conversion;
}
//function for following a user
function followUser ($user_id)
{
    global $db;
    $cu =getUser($_SESSION['userdata']['id']);
    $current_user=$_SESSION['userdata']['id'];
    $query ="INSERT INTO follow_list(follower_id,user_id) VALUES($current_user, $user_id)";

    createNotification($cu['id'],$user_id,"started Following You");
    return mysqli_query($db,$query);
}
//Function for Blocking a User
function blockUser($blocked_user_id)
{
    global $db;
    $cu =getUser($_SESSION['userdata']['id']);
    $current_user=$_SESSION['userdata']['id'];
    $query ="INSERT INTO block_list (user_id,blocked_user_id) VALUES ($current_user, $blocked_user_id)";
    createNotification($cu['id'],$blocked_user_id,"Blocked you");
    $query2="DELETE FROM follow_list WHERE follower_id=$current_user && user_id=$blocked_user_id";
    return mysqli_query($db,$query2);
    $query3="DELETE FROM follow_list WHERE follower_id=$blocked_user_id && user_id=$current_user ";
    return mysqli_query($db, $query3);
    return mysqli_query($db, $query);
}
//function for unblocking
function unblockUser($user_id)
{
    global $db;
    $current_user =$_SESSION['userdata']['id'];
    $query ="DELETE FROM block_list WHERE user_id=$current_user && blocked_user_id=$user_id";
    createNotification($current_user, $user_id,"Unblocked you!");
    return mysqli_query($db, $query);
}
//LIKE BUTTON
function checkLikeStatus($post_id)
{
    global $db;
    $current_user =$_SESSION['userdata']['id'];
    $query ="SELECT count(*) as row FROM likes WHERE user_id= $current_user && post_id=$post_id";
    $run= mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}

//function for liking a post
function like($post_id)
{
    global $db;
    $current_user =$_SESSION['userdata']['id'];
    $query="INSERT INTO likes(post_id,user_id) VALUES($post_id,$current_user)";
    $poster_id = getPosterId($post_id);
    if($post_id!=$current_user)
    {
        createNotification($current_user,$poster_id, "liked your post !", $poster_id);
    }
    return mysqli_query($db,$query);
}

//ADD COMMENTS

function addComment($post_id,$comment)
{
    global $db;
    $comment = mysqli_real_escape_string($db,$comment);
    $current_user=$_SESSION['userdata']['id'];
    $query="INSERT INTO comments(user_id,post_id,comment) VALUES ($current_user,post_id,'$comment')";
    $poster_id=getPosterId($post_id);
    if($post_id!=$current_user)
    {
        createNotification($current_user,$poster_id, "commented your post !", $poster_id);
    }
    return mysqli_query($db,$query);
}

//more comments stuff
function createNotification($from_user_id, $to_user_id,$msg,$post_id=0)
{
    global $db;
    $query="INSERT INTO notifications(from_user_id,to_user_id,message,post_id) VALUES ($from_user_id,$to_user_id,$msg,$post_id)";
    mysqli_query($db,$query);

}

//like count function
function getComments($post_id)
{
    global $db;
    $query= "SELECT * FROM comments WHERE post_id=$post_id ORDER BY DESC";
    $run=mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

//get notifications
function getNotifications()
{
    $cu_user_id = $_SESSION['userdata']['id'];
    global $db;
    $query="SELECT * FROM notifications WHERE to_user_id=$cu_user_id ORDER_BY DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

//get unread notifications count

function getUnreadNotificationsCount()
{
    $cu_user_id = $_SESSION['userdata']['id'];
    global $db;
    $query = "SELECT count(*) as row FROM notifications WHERE to_user_id = $cu_user_id && read_status=0 ORDER BY DESC";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_assoc($run)['row'];
}

//show time
function show_time($time)
{
    return '<time style="font-size:small" class="timeago text-muted text-small" datetime="'.$time.'"></time>';
}

//set notification status to read
function setNotificationStatusAsRead()
{
    $cu_user_id = $_SESSION['userdata']['id'];
    global $db;
    $query ="UPDATE notifications SET read_status=1 WHERE to_user_id=$cu_user_id";
    return mysqli_query($db,$query);
}

//getting likes count
function getLikes($post_id)
{
    global $db;
    $query = "SELECT * FROM likes WHERE post_id=post_id";
    $run =mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
}

//function for unlike post
function unlike($post_id)
{
    global $db;
    $current_user = $_SESSION['userdata']['id'];
    $query="DELETE FROM likes WHERE user_id=$current_user && post_id=$post_id";
    $poster_id=getPosterId($post_id);
    if($poster_id!=$current_user)
    {
        createNotification($current_user,$poster_id,"unliked you post!",$post_id);
    }
    return mysqli_query($db,$query);
}

//unfollow User

function unfollowUser($user_id){
    global $db;
    $current_user=$_SESSION['userdata']['id'];
    $query="DELETE FROM follow_list WHERE follower_id=$current_user && user_id=$user_id";



   createNotification($current_user,$user_id,"Unfollowed you !");
    return mysqli_query($db,$query);

    
}

//function to show errors
function showError ($field)
{
    if(isset($_SESSION['error']))
    {
        $error = $_SESSION['error'];
        if(isset($error['field']) && $field==$error['field'])
        {
            ?>
                <div class="alert alert-danger my-2" role="alert">
                    <?=$error['msg'];?>
                </div>
            <?php
        }
    }
}

//FUNCTION FOR SHOWING PERVFORMDATA
function showFormData($field){
    if(isset($_SESSION['formdata']))
    {
        $formdata=$_SESSION['formdata'];
        return $formdata[$field];
    }
}

//checking for duplicate email
function isEmailRegistered($email)
{
    global $db;
    $query="SELECT count(*) as row FROM users WHERE email='$email'";
    $run=mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//checking for duplicate username
function isUsernameRegistered($username){
    global $db;
    $query="SELECT count(*) as row FROM users WHERE username='$username'";
    $run=mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//checking for duplicate username
function isUsernameRegisteredByOther($username)
{
    global $db;
    $user_id=$_SESSION['userdata']['id'];
    $query="SELECT count(*) as row FROM users WHERE username='$username' && id!=$user_id";
    $run=mysqli_query($db,$query);
    $return_data = mysqli_fetch_assoc($run);
    return $return_data['row'];
}

//validating- the signup form
function validateSignupForm($form_data)
{
    $response=array();
    $response['status']=true;

        if(!$form_data['password'])
        {
            $response['msg']="password is not given";
            $response['status']=false;
            $response['field']='password';
        }
        if(!$form_data['username'])
        {
            $response['msg']="username is not given";
            $response['status']=false;
            $response['field']='username';
        }
        if(!$form_data['email'])
        {
            $response['msg']="email is not given";
            $response['status']=false;
            $response['field']='email';
        }
        if(!$form_data['last_name'])
        {
            $response['msg']="last name is not given";
            $response['status']=false;
            $response['field']='last_name';
        }
        if(!$form_data['first_name'])
        {
            $response['msg']="first name is not given";
            $response['status']=false;
            $response['field']='first_name';
        }
        if(isEmailRegistered($form_data['email']))
        {
            $response['msg']="this email is registered already";
            $response['status']=false;
            $response['field']='email';
        }
        if(isUsernameRegistered($form_data['last_name']))
        {
            $response['msg']="username is already registered";
            $response['status']=false;
            $response['field']='username';
        }

        return $response;

}



// if(isset($_GET['newfp']))
// {
//     unset($_SESSION['auth_temp']);
//     unset($_SESSION['forgot_email']);
//     unset($_SESSION['forgot_code']);
// }
// if(isset($_SESSION['Auth']))
// {
//     $user = getUser($_SESSION['userdata']['id']);
//     $posts = filterPosts();
//     $follow_suggestions = filterFollowSuggestion();
// }

// $pagecount = count($_GET);

//*****************************MANAGE PAGES****************************************
// if(isset($_SESSION['Auth']) && $user['ac_status']==1 && !$pagecount)
// {
//     showPage('header',['page_title'=>'Home']);
//     showPage('navbar');
//     showPage('wall');
// }
// elseif(($_SESSION['Auth']) && $user['ac_status']==0 && !$pagecount)
// {
//     // code...
//     showPage('header',['page_title'=>'Verify Your Email']);
//     showPage('verify_email');
// }
// elseif(($_SESSION['Auth']) && $user['ac_status']==2 && !$pagecount)
// {
//     // code...
//     showPage('header',['page_title'=>'Blocked']);
//     showPage('Blocked');
// }
// elseif(($_SESSION['Auth']) && isset($_GET['editprofile']) && $user['ac_status']==1 && !$pagecount)
// {
//     // code...
//     showPage('header',['page_title'=>'Edit Profile']);
//     showPage('navbar');
//     showPage('edit_profile');
// }
// elseif(($_SESSION['Auth']) && isset($_GET['u']) && $user['ac_status']==1 && !$pagecount)
// {
//     // code...
//     $profile = getUserByUsername($_GET['u']);
//     if(!$profile)
//     {
//         showPage('header',['page_title'=>'User Not Found']);
//         showPage('navbar');
//         showPage('user_not_found');
//     }
//     else
//     {
//         $profile_post = getPostById($profile['id']);
//         $profile =['followers']=getFollowers($profile['id']);
//         $profile =['following']=getFollowing($profile['id']);
//         showPage('header',['page_title'=>$profile['first_name'].' '.$profile['last_name']]);
//         showPage('navbar');
//         showPage('profile');
//     }
// }

// elseif(isset($_GET['signup']))
// {
//     showPage('header',['page_title'=>'Login']);
//     showPage('login');
// }

?>