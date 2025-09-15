<?php
require 'connect.php';

session_start();
?>


<?php
if(isset($_POST['userid']) && isset($_POST['password'])){
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES);
        return $data;
    }

    $uid = validate($_POST["userid"]);
    $pwd = validate($_POST["password"]);

    if(empty($uid)){
        ErrorLogger::logAuthError("Login attempt with empty user ID", $uid);
        header("Location:../login.php?error=User ID Required.");
        exit();
    }
    else if(empty($pwd)){
        ErrorLogger::logAuthError("Login attempt with empty password", $uid);
        header("Location:../login.php?error=Password Required.");
        exit();
    }
    else{
        $sql = "SELECT * FROM login WHERE id=?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            ErrorLogger::logDatabaseError($sql, mysqli_error($conn), ['user_id' => $uid]);
            header("Location:../login.php?error=Database error occurred.");
            exit();
        }
        mysqli_stmt_bind_param($stmt, 's', $uid);
        if (!mysqli_stmt_execute($stmt)) {
            ErrorLogger::logDatabaseError($sql, mysqli_stmt_error($stmt), ['user_id' => $uid]);
            header("Location:../login.php?error=Database error occurred.");
            exit();
        }
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result)==1){
            $row = mysqli_fetch_assoc($result);
            
            // Handle admin login (keep plain text for backward compatibility)
            if($row['id']=='admin' && $row['pw']=='admin'){
                ErrorLogger::logAuthError("Admin login successful", $uid);
                $_SESSION['uname']=$row['uname'];
                $_SESSION['id']=$row['id'];
                $_SESSION['loginMessage']="Logged in Successfully";
                header("Location:../admin/admin.php");
                exit();
            }
            
            // Handle regular user login with password verification
            if($row['id']==$uid){
                // Check if password is hashed or plain text
                if(password_verify($pwd, $row['pw']) || $row['pw']==$pwd){
                    ErrorLogger::logAuthError("User login successful", $uid);
                    $_SESSION['uname']=$row['uname'];
                    $_SESSION['id']=$row['id'];
                    $_SESSION['voteStatus']="notVoted";
                    $candStatus="notSubmitted";
                    $_SESSION['loginMessage']="Logged in Successfully";
                    // checking election status
                    $electionSql = "SELECT voteStatus FROM login where id='admin'";
                    $electionResult = mysqli_query($conn,$electionSql);
                    $electionRow = mysqli_fetch_assoc($electionResult);
                    if($electionRow['voteStatus']==1){
                        $_SESSION['votingMessage']='Election has started! You can now cast your votes.';
                    }
                    elseif($electionRow['voteStatus']==2){
                        $_SESSION['votingMessage']='Election has ended! Wait for further notices.';
                    }
                    header("Location:../users/user.php");
                    exit();
                }
                else{
                    ErrorLogger::logAuthError("Login failed - incorrect password", $uid);
                    header("Location:../login.php?error=Incorrect User ID or Password.");
                    exit();
                }
            }
            else{
                ErrorLogger::logAuthError("Login failed - user ID mismatch", $uid);
                header("Location:../login.php?error=Incorrect User ID or Password.");
                exit();
            }
        }
        else{
            ErrorLogger::logAuthError("Login failed - user not found", $uid);
            header("Location:../login.php?error=Incorrect User ID or Password.");
            exit();
        }
        mysqli_stmt_close($stmt);
    }
}
else{
    header("Location:../login.php");
    exit();
}
?>