# 🔐 Password Update Scripts - Usage Guide

## 📋 **Available Scripts**

### 1. **Command Line Script** (`update_passwords.php`)
- Full-featured command line tool
- Supports all update operations
- **Automatically fixes password column size**
- Can be run from terminal/command prompt

### 2. **Web Interface** (`admin/update_passwords_web.php`)
- User-friendly web interface
- Accessible from admin panel
- **Automatically fixes password column size**
- Real-time feedback and validation

### 3. **Column Fix Script** (`fix_password_column.php`)
- **Standalone database column fixer**
- Updates `pw` column to `VARCHAR(255)`
- Safe to run multiple times
- Shows before/after table structure

## 🚀 **Command Line Usage**

### **Basic Commands**

```bash
# Fix password column size (run this first if needed)
php fix_password_column.php

# Show help and current users
php update_passwords.php

# Update single user password
php update_passwords.php single user001 newpassword123

# Update multiple users with same password
php update_passwords.php multiple "user001,user002,user003" newpassword123

# Reset all users to default password
php update_passwords.php reset_all password123

# Update admin password
php update_passwords.php admin newadminpass
```

### **Examples**

```bash
# Update user 0001 password
php update_passwords.php single 0001 mynewpassword

# Update multiple users
php update_passwords.php multiple "user001,user002,user003" commonpassword

# Reset all users to 'test123'
php update_passwords.php reset_all test123

# Change admin password
php update_passwords.php admin newadmin123
```

## 🌐 **Web Interface Usage**

### **Access the Web Interface**
1. Login as admin
2. Navigate to: `http://localhost/voting-system/admin/update_passwords_web.php`
3. Or add it to your admin panel navigation

### **Available Operations**

#### **1. Update Single User**
- Enter User ID (e.g., `user001`)
- Enter new password
- Click "Update Password"

#### **2. Update Multiple Users**
- Enter User IDs separated by commas (e.g., `user001,user002,user003`)
- Enter new password
- Click "Update All"

#### **3. Reset All Users**
- Enter default password (default: `password123`)
- Click "Reset All Passwords"
- ⚠️ **Warning**: This affects ALL users except admin

#### **4. Update Admin Password**
- Enter new admin password
- Click "Update Admin"
- Note: Admin password remains plain text for compatibility

## 🔧 **Technical Details**

### **Password Hashing**
- **Regular Users**: bcrypt with cost factor 10
- **Admin User**: Plain text (for compatibility)
- **Hash Length**: 60 characters
- **Format**: `$2y$10$[hash]`

### **Database Updates**
- Updates `login` table
- Uses prepared statements for security
- Validates user existence before update
- Provides success/failure feedback

### **Security Features**
- Password hashing with bcrypt
- SQL injection protection
- Input validation
- Confirmation dialogs for destructive operations

## ⚠️ **Important Notes**

### **Before Running**
1. **Backup your database** before making changes
2. **Test with a single user** first
3. **Verify login works** after updates
4. **Keep admin password secure**

### **Best Practices**
- Use strong passwords (8+ characters)
- Different passwords for different users
- Regular password updates
- Monitor login attempts

### **Troubleshooting**
- If login fails after update, check password hash length
- Ensure database column is `varchar(255)`
- Verify user ID exists in database
- Check for typos in user IDs

## 📊 **Current Users Display**

Both scripts show current users with:
- User ID
- Name
- Password type (Hashed/Plain Text)
- Department
- Year

## 🎯 **Quick Start**

1. **For single user update:**
   ```bash
   php update_passwords.php single user001 newpass123
   ```

2. **For web interface:**
   - Go to admin panel
   - Navigate to "Update Passwords"
   - Use the forms provided

3. **For bulk updates:**
   ```bash
   php update_passwords.php multiple "user001,user002" commonpass123
   ```

## 🔍 **Verification**

After updating passwords, test login:
1. Go to login page
2. Use updated credentials
3. Verify successful login
4. Check user permissions

---
*Last Updated: September 17, 2025*

