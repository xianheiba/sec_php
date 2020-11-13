   $db_link = mysqli_connect('localhost', 'dbuser', 'dbpassword', 'dbname');
   function can_access_feature($current_user) {
       global $db_link;
       $username = mysqli_real_escape_string($db_link, $current_user->username);
       $res = mysqli_query($db_link, "SELECT COUNT(id) FROM blacklisted_users WHERE username = '$username';");
       $row = mysqli_fetch_array($res);
       if ((int)$row[0] > 0) {
           return false;
       } else {
           return true;
       }
   }
   if (!can_access_feature($current_user)) {
       exit();
   }
   
   // 機能のコードをここに記述します