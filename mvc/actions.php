<?php
include("functions.php");

if($_GET['action'] == "loginSignup") {
    

    
    
    
// check if user email is already exists 
    
      $in_password = mysqli_real_escape_string($link, $_POST['password']);
      $email = mysqli_real_escape_string($link, $_POST['email']);
      
      
    if($_POST['loginActive'] == "0" ) {
 
         $query = "SELECT * FROM users WHERE email ='$email'";
         $result = mysqli_query($link, $query);
          
       if (mysqli_num_rows($result) > 0 ) {
           
           echo "registerd";
// code END

       }else {
           
           
// hash new password and insert data to db and register user

        $salt =  bin2hex(openssl_random_pseudo_bytes(4));
        $hash =  hash(sha256, $in_password);
        $password_signup = hash('sha256', $salt . $hash);
        $uid = uniqid();
        $sql = "INSERT INTO users (id, email, password, passwd)
        VALUES ('$uid', '$email', '$password_signup', '$salt')";

        if(mysqli_query($link, $sql)){
            echo "1";
             $_SESSION['id'] = login($email);
        }else {
            $error = "can't register now please try again later";
        }
           
      
           
           
       }

    }
// register code END

    else {

// log user IN
   $sql = "SELECT `passwd`,`password` FROM users where `email`='$email'";

       $result = mysqli_query($link, $sql);
       $row = mysqli_fetch_array($result); 
    
 
       $db_password = $row['password'];
      
 
      
        $salt =  $row['passwd'];
       
        
        $hash =  hash(sha256, $in_password);
        $password_login = hash('sha256', $salt . $hash);
          
     
     
    
   if ($password_login == $db_password){
      echo "1";
      
     $_SESSION['id'] = login($email);
    }else {
      echo "login_error";
    }
// login in END
    }}



if($_GET['action'] == "postTweet") {

  $theTweet = $_POST['tweetData'];
  if(!$theTweet){
      echo "you tweet is empty";
  }else if (strlen($theTweet) > 140 ) {
      echo "your tweet is too long 'Ahmed Mouse ..is that you ?'";
  }else {
      // post tweet
      
       $uid = $_SESSION['id'];
     
      
        $sql = "INSERT INTO tweets (tweet, uid, datetime)VALUES ('$theTweet', '$uid', NOW())";

        if(mysqli_query($link, $sql)){
            echo "1";
        }else {
          // echo "error";
        }
      
      // end post tweet
      
      
  }
  
}
?>