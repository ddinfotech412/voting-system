<?php
require 'common/connect.php';

echo "=== PASSWORD UPDATE SCRIPT ===\n\n";

// Function to check and update password column size
function ensurePasswordColumnSize($conn) {
    echo "🔍 Checking password column size...\n";
    
    // Check current column size
    $query = "SHOW COLUMNS FROM login LIKE 'pw'";
    $result = mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $current_type = $row['Type'];
        echo "   Current column type: $current_type\n";
        
        // Check if it's already varchar(255) or larger
        if (strpos($current_type, 'varchar(255)') !== false || strpos($current_type, 'varchar(500)') !== false) {
            echo "   ✅ Password column size is adequate\n\n";
            return true;
        } else {
            echo "   ⚠️  Password column needs to be enlarged for bcrypt hashes\n";
            echo "   🔧 Updating column to VARCHAR(255)...\n";
            
            $alter_query = "ALTER TABLE login MODIFY COLUMN pw VARCHAR(255) NOT NULL";
            if (mysqli_query($conn, $alter_query)) {
                echo "   ✅ Password column updated successfully to VARCHAR(255)\n\n";
                return true;
            } else {
                echo "   ❌ Failed to update password column: " . mysqli_error($conn) . "\n\n";
                return false;
            }
        }
    } else {
        echo "   ❌ Could not check password column: " . mysqli_error($conn) . "\n\n";
        return false;
    }
}

// Function to generate hashed password
function generateHashedPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to test password verification
function testPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Function to update single user password
function updateUserPassword($conn, $userid, $new_password) {
    $hashed_password = generateHashedPassword($new_password);
    
    $query = "UPDATE login SET pw = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $userid);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "✅ Updated password for user: $userid\n";
        
        // Test the new password
        $test_result = testPassword($new_password, $hashed_password);
        echo "   Password verification: " . ($test_result ? 'SUCCESS' : 'FAILED') . "\n";
        
        return true;
    } else {
        echo "❌ Failed to update password for user: $userid\n";
        echo "   Error: " . mysqli_error($conn) . "\n";
        return false;
    }
    
    mysqli_stmt_close($stmt);
}

// Function to update multiple users with same password
function updateMultipleUsersPassword($conn, $userids, $new_password) {
    $hashed_password = generateHashedPassword($new_password);
    $success_count = 0;
    
    foreach ($userids as $userid) {
        $query = "UPDATE login SET pw = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ss', $hashed_password, $userid);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "✅ Updated password for user: $userid\n";
            $success_count++;
        } else {
            echo "❌ Failed to update password for user: $userid\n";
        }
        
        mysqli_stmt_close($stmt);
    }
    
    echo "\n📊 Summary: $success_count/" . count($userids) . " users updated successfully\n";
    return $success_count;
}

// Function to reset all users to default password
function resetAllUsersToDefault($conn, $default_password = 'password123') {
    echo "🔄 Resetting all users to default password: $default_password\n\n";
    
    // Get all users except admin
    $query = "SELECT id, uname FROM login WHERE id != 'admin'";
    $result = mysqli_query($conn, $query);
    
    $userids = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $userids[] = $row['id'];
    }
    
    return updateMultipleUsersPassword($conn, $userids, $default_password);
}

// Function to show current users
function showCurrentUsers($conn) {
    echo "📋 Current users in database:\n";
    echo str_repeat("-", 50) . "\n";
    
    $query = "SELECT id, uname, pw, department, year FROM login ORDER BY id";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $password_type = (strlen($row['pw']) > 20) ? 'Hashed' : 'Plain Text';
        echo sprintf("%-10s | %-20s | %-12s | %s\n", 
            $row['id'], 
            $row['uname'], 
            $password_type,
            $row['department'] ?? 'N/A'
        );
    }
    echo str_repeat("-", 50) . "\n\n";
}

// Main script execution
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure password column is large enough
    if (!ensurePasswordColumnSize($conn)) {
        echo "❌ Cannot proceed without proper password column size!\n";
        exit(1);
    }
    
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'single':
            $userid = $_POST['userid'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if (empty($userid) || empty($password)) {
                echo "❌ User ID and password are required!\n";
            } else {
                updateUserPassword($conn, $userid, $password);
            }
            break;
            
        case 'multiple':
            $userids = explode(',', $_POST['userids'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($userids) || empty($password)) {
                echo "❌ User IDs and password are required!\n";
            } else {
                $userids = array_map('trim', $userids);
                updateMultipleUsersPassword($conn, $userids, $password);
            }
            break;
            
        case 'reset_all':
            $default_password = $_POST['default_password'] ?? 'password123';
            resetAllUsersToDefault($conn, $default_password);
            break;
            
        case 'admin':
            $password = $_POST['password'] ?? '';
            if (empty($password)) {
                echo "❌ Admin password is required!\n";
            } else {
                // Admin password stays plain text for compatibility
                $query = "UPDATE login SET pw = ? WHERE id = 'admin'";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $password);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo "✅ Admin password updated successfully!\n";
                } else {
                    echo "❌ Failed to update admin password!\n";
                }
                mysqli_stmt_close($stmt);
            }
            break;
    }
} else {
    // Show current users
    showCurrentUsers($conn);
    
    // Show menu
    echo "🔧 PASSWORD UPDATE OPTIONS:\n\n";
    echo "1. Update single user password\n";
    echo "2. Update multiple users with same password\n";
    echo "3. Reset all users to default password\n";
    echo "4. Update admin password\n";
    echo "5. Show current users\n\n";
    
    echo "📝 USAGE EXAMPLES:\n\n";
    echo "Single user update:\n";
    echo "php update_passwords.php single user001 newpassword123\n\n";
    
    echo "Multiple users update:\n";
    echo "php update_passwords.php multiple 'user001,user002,user003' newpassword123\n\n";
    
    echo "Reset all users:\n";
    echo "php update_passwords.php reset_all password123\n\n";
    
    echo "Update admin:\n";
    echo "php update_passwords.php admin newadminpass\n\n";
}

// Command line interface
if (isset($argv[1])) {
    // Ensure password column is large enough
    if (!ensurePasswordColumnSize($conn)) {
        echo "❌ Cannot proceed without proper password column size!\n";
        exit(1);
    }
    
    $action = $argv[1];
    
    switch ($action) {
        case 'single':
            if (isset($argv[2]) && isset($argv[3])) {
                updateUserPassword($conn, $argv[2], $argv[3]);
            } else {
                echo "Usage: php update_passwords.php single <userid> <password>\n";
            }
            break;
            
        case 'multiple':
            if (isset($argv[2]) && isset($argv[3])) {
                $userids = explode(',', $argv[2]);
                $userids = array_map('trim', $userids);
                updateMultipleUsersPassword($conn, $userids, $argv[3]);
            } else {
                echo "Usage: php update_passwords.php multiple 'user001,user002' <password>\n";
            }
            break;
            
        case 'reset_all':
            $default_password = $argv[2] ?? 'password123';
            resetAllUsersToDefault($conn, $default_password);
            break;
            
        case 'admin':
            if (isset($argv[2])) {
                $query = "UPDATE login SET pw = ? WHERE id = 'admin'";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $argv[2]);
                
                if (mysqli_stmt_execute($stmt)) {
                    echo "✅ Admin password updated successfully!\n";
                } else {
                    echo "❌ Failed to update admin password!\n";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Usage: php update_passwords.php admin <password>\n";
            }
            break;
            
        default:
            echo "Invalid action. Use: single, multiple, reset_all, or admin\n";
    }
}

echo "\n🎉 Password update script completed!\n";
?>
