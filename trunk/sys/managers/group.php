<?php if(!defined('KLONDIKE_VER')) die("Access denied!"); ?>
<?php
    class Groups {
        public function Create ( $groupName ) {
            $groupName = addslashes($groupName);
            if( 1 != MySQLdb::Insert("groups", "groupname", array("'$groupName'")) ) return FALSE;
            return TRUE;
        }
        
        public function Delete ( $groupName ) {
            $groupName = addslashes($groupName);
            MySQLdb::Delete("group_users", "groupname='$groupName'");
            MySQLdb::Delete("groups", "groupname='$groupName'");
            return TRUE;
        }
        
        public function ListAll () {
            return MySQLdb::Select("*", "groups");
        }
        
        public function AddUser ( $groupName, $userName ) {
            $groupName = addslashes($groupName);
            $userName = addslashes($userName);
            return MySQLdb::Insert("group_users", "groupname,username", array("'$groupName'", "'$userName'"));
        }
        
        public function RemoveUser ( $groupName, $userName ) {
            $groupName = addslashes($groupName);
            $userName = addslashes($userName);
            return MySQLdb::Delete("group_users", "groupname='$groupName' AND username='$userName'");
        }
        
        public function ListUsers ( $groupName ) {
            $groupName = addslashes($groupName);
            
            return MySQLdb::Select ("username", "group_users", "groupname='$groupName'");
        }
        
        public function HasUser ( $groupName, $userName) {
            $groupName = addslashes($groupName);
            $userName = addslashes($userName);
            
            return (1 == count(MySQLdb::Select("username", "group_users", "groupname='$groupName' AND username='$userName'")));
        }
    }
?>