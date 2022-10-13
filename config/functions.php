<?php
require_once'config/functions.php';
$db = mysqli_connect("DB_HOST,DB_USER,DB_PASS,DB_NAME") or die ("database is not connected");
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
function sendMessage($user_id)
{
    global $db;
    $current_user_id =$_SESSION['userdata']['id'];
    $query = "INSERT INTO messages (from_user_id,to_user_id,msg) VALUES ($current_user_id,$user_id,'$msg')";
    $run = mysqli_query($db,$query);
    return mysqli_fetch_all($run,true);
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
function updateMessageReadStatus($user_id)
{
    global $db;
    $current_user_id =$_SESSION['userdata']['id'];
    $query = "UPDATE messages SET read_status=1 WHERE to_user_id=$cu_user_id && from_user_id=$user_id";
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
    $query ="INSERT INTO follow_list (follower_id,user_id) VALUES ($current_user, $user_id)";
    $createNotification($cu['id'],$user_id,"Follows You");
    return mysqli_query($db,$query);
}
//Function for Blocking a User
function blockUser($blocked_user_id)
{
    global $db;
    $cu =getUser($_SESSION['userdata']['id']);
    $current_user=$_SESSION['userdata']['id'];
    $query ="INSERT INTO block_list (user_id,blocked_user_id) VALUES ($current_user, $blocked_user_id)";
    $createNotification($cu['id'],$blocked_user_id,"Blocked you");
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
function checkLikeStatus()
{
    global $db;
    $current_user =$_SESSION['userdata']['id'];
    $query ="SELECT count(*) as row FROM likes WHERE user_id= $current_user && post_id=$post_id";
    $run= mysqli_query($db, $query);
    return mysqli_fetch_assoc($run)['row'];
}



if(isset($_GET['newfp']))
{
    unset($_SESSION['auth_temp']);
    unset($_SESSION['forgot_email']);
    unset($_SESSION['forgot_code']);
}
if(isset('Auth'))
{
    $user = getUser($_SESSION['userdata']['id']);
    $posts = filterPosts();
    $follow_suggestions = filterFollowSuggestion();
}

$pagecount = count($_GET);

//*****************************MANAGE PAGES****************************************
if(isset($_SESSION['Auth']) && $user['ac_status']==1 && !$pagecount)
{
    showPage('header',['page_title'=>'Home']);
    showPage('navbar');
    showPage('wall');
}
elseif(($_SESSION['Auth']) && $user['ac_status']==0 && !$pagecount)
{
    // code...
    showPage('header',['page_title'=>'Verify Your Email']);
    showPage('verify_email');
}
elseif(($_SESSION['Auth']) && $user['ac_status']==2 && !$pagecount)
{
    // code...
    showPage('header',['page_title'=>'Blocked']);
    showPage('Blocked');
}
elseif(($_SESSION['Auth']) && isset($_GET['editprofile']) && $user['ac_status']==1 && !$pagecount)
{
    // code...
    showPage('header',['page_title'=>'Edit Profile']);
    showPage('navbar');
    showPage('edit_profile');
}
elseif(($_SESSION['Auth']) && isset($_GET['u']) && $user['ac_status']==1 && !$pagecount)
{
    // code...
    $profile = getUserByUsername($_GET['u']);
    if(!$profile)
    {
        showPage('header',['page_title'=>'User Not Found']);
        showPage('navbar');
        showPage('user_not_found');
    }
    else
    {
        $profile_post = getPostById($profile['id']);
        $profile =['followers']=getFollowers($profile['id']);
        $profile =['following']=getFollowing($profile['id']);
        showPage('header',['page_title'=>$profile['first_name'].' '.$profile['last_name']]);
        showPage('navbar');
        showPage('profile');
    }
}

elseif(isset($_GET['signup']))
{
    showPage('header',['page_title'=>'Login']);
    showPage('login');
}

?>