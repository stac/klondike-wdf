<?php
    function user_create($username, $password, $name) {
        global $_SETTINGS;
        
        $username = addslashes($username);
        $password = addslashes($password);
        $name = addslashes($name);
        
        $query=db_fetch_all("SELECT * FROM " . $_SETTINGS['database']['prefix'] . "users where username='$username'");
        if(count($query) > 0)
            return FALSE;
        $time = md5(time());
        $password = $time.$password;
        $enc_pass = $time . ":" . md5($password);
        $query = "INSERT INTO " . $_SETTINGS['database']['prefix'] . "users (username,password,name,createdOn) values('$username', '$enc_pass', '$name', NOW())"; 
        
        db_update_all($query);
        user_authenticate($username, $password);
    }
    
    function user_delete($username) {
        global $_SETTINGS;
        $username = addslashes($username);
        $query = db_update_all("DELETE FROM " . $_SETTINGS['database']['prefix'] . "users where username='$username'");
        if($query != 1)
            return FALSE;
        db_update_all($query);
        return true;
    }
    
    function user_change_password($username, $password, $new_pass)    {
        global $_SETTINGS;
        
        $username = addslashes($username);
        $password = addslashes($password);
        $new_pass = addslashes($new_pass);

        $query = db_fetch_all("SELECT * FROM " . $_SETTINGS['database']['prefix'] . "users WHERE username='$username'");
        
        if(count($query) == 0)
            return false;
        
        $enc_pass = $query[0]['password'];
        $check = split(":",$enc_pass);
        $time = $check[0];
        $password = $time.$password;
        $password = md5($password);
        
        if($password == $check[1]) {
            $time = md5(time());
            $new_pass = $time . $new_pass;
            $enc_pass = $time . ":" . md5($new_pass);
            $query = "UPDATE " . $_SETTINGS['database']['prefix'] . "users SET (password='$enc_pass') WHERE username='$username'"; 
            db_update_all($query);
        }
        else {
            return FALSE;
        }
    }
    
    function user_authenticate($username, $password) {
        global $_SETTINGS;
        
        $username = addslashes($username);
        $password = addslashes($password);
        
        $query=db_fetch_all("SELECT * FROM " . $_SETTINGS['database']['prefix'] . "users WHERE username='$username'");
        
        if(count($query) == 0)
            return false;
        
        $enc_pass = $query[0]['password'];
        $check = split(":",$enc_pass);
        $time = $check[0];
        $password = $time.$password;
        $password = md5($password);
        if($password != $check[1])
            return false;
        $_SESSION['authenticated'] = "YES";
        $_SESSION['authenticated_user'] = "$username";
        return true;
    }
    
    function user_logout() {
        $_SESSION['authenticated'] = "NO";
        unset($_SESSION['authenticated_user']);
    }
?>
