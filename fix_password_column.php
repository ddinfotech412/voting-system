<?php
require 'common/connect.php';

echo "=== PASSWORD COLUMN FIX SCRIPT ===\n\n";

// Function to check and update password column size
function fixPasswordColumn($conn) {
    echo "🔍 Checking password column size...\n";
    
    // Check current column size
    $query = "SHOW COLUMNS FROM login LIKE 'pw'";
    $result = mysqli_query($conn, $query);
    
    if ($result && $row = mysqli_fetch_assoc($result)) {
        $current_type = $row['Type'];
        echo "   Current column type: $current_type\n";
        
        // Check if it's already varchar(255) or larger
        if (strpos($current_type, 'varchar(255)') !== false || strpos($current_type, 'varchar(500)') !== false) {
            echo "   ✅ Password column size is already adequate\n";
            echo "   📏 Current size: $current_type\n";
            return true;
        } else {
            echo "   ⚠️  Password column needs to be enlarged for bcrypt hashes\n";
            echo "   🔧 Updating column to VARCHAR(255)...\n";
            
            $alter_query = "ALTER TABLE login MODIFY COLUMN pw VARCHAR(255) NOT NULL";
            if (mysqli_query($conn, $alter_query)) {
                echo "   ✅ Password column updated successfully to VARCHAR(255)\n";
                
                // Verify the change
                $verify_query = "SHOW COLUMNS FROM login LIKE 'pw'";
                $verify_result = mysqli_query($conn, $verify_query);
                if ($verify_result && $verify_row = mysqli_fetch_assoc($verify_result)) {
                    echo "   ✅ Verification: Column is now " . $verify_row['Type'] . "\n";
                }
                
                return true;
            } else {
                echo "   ❌ Failed to update password column: " . mysqli_error($conn) . "\n";
                return false;
            }
        }
    } else {
        echo "   ❌ Could not check password column: " . mysqli_error($conn) . "\n";
        return false;
    }
}

// Function to show table structure
function showTableStructure($conn) {
    echo "\n📋 Current login table structure:\n";
    echo str_repeat("-", 60) . "\n";
    
    $query = "DESCRIBE login";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        printf("%-15s | %-20s | %-8s | %-5s | %-10s\n", "Field", "Type", "Null", "Key", "Default");
        echo str_repeat("-", 60) . "\n";
        
        while ($row = mysqli_fetch_assoc($result)) {
            printf("%-15s | %-20s | %-8s | %-5s | %-10s\n", 
                $row['Field'], 
                $row['Type'], 
                $row['Null'], 
                $row['Key'], 
                $row['Default'] ?? 'NULL'
            );
        }
    } else {
        echo "   ❌ Could not retrieve table structure: " . mysqli_error($conn) . "\n";
    }
    echo str_repeat("-", 60) . "\n\n";
}

// Main execution
echo "🚀 Starting password column fix...\n\n";

// Show current structure
showTableStructure($conn);

// Fix the column
if (fixPasswordColumn($conn)) {
    echo "\n🎉 Password column fix completed successfully!\n";
    echo "✅ Your database is now ready for bcrypt password hashes\n";
    echo "📏 Password column can now store up to 255 characters\n";
    echo "🔐 You can now use the password update scripts safely\n\n";
    
    // Show updated structure
    echo "📋 Updated table structure:\n";
    showTableStructure($conn);
    
} else {
    echo "\n❌ Password column fix failed!\n";
    echo "⚠️  Please check database permissions and try again\n";
    echo "🔧 You may need to run this as a database administrator\n\n";
}

echo "💡 Next steps:\n";
echo "   1. Run: php update_passwords.php (to update passwords)\n";
echo "   2. Or use the web interface: admin/update_passwords_web.php\n";
echo "   3. Test login with updated passwords\n\n";

echo "=== SCRIPT COMPLETED ===\n";
?>
