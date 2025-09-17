# FCRIT Voting System - Login Credentials

## 🔐 **Login Issue Fixed!**

The password column in the database was too short (varchar(8)) to store bcrypt password hashes (60 characters). This has been fixed by updating the column to varchar(255).

## 📋 **Available Login Credentials**

### **Admin Account**
- **User ID:** `admin`
- **Password:** `admin`
- **Access:** Full admin panel access

### **Dummy User Accounts**
All dummy users now have properly hashed passwords and can be used for testing:

| User ID | Name | Password | Department |
|---------|------|----------|------------|
| `user001` | John Smith | `pass123` | Computer Science |
| `user002` | Sarah Johnson | `pass456` | Information Technology |
| `user003` | Michael Brown | `pass789` | Mechanical Engineering |
| `user004` | Emily Davis | `pass101` | Electrical Engineering |
| `user005` | David Wilson | `pass202` | EXTC Engineering |
| `user006` | Lisa Anderson | `pass303` | Computer Science |
| `user007` | Robert Taylor | `pass404` | Information Technology |
| `user008` | Jennifer Martinez | `pass505` | Mechanical Engineering |
| `user009` | Christopher Lee | `pass606` | Electrical Engineering |
| `user010` | Amanda Garcia | `pass707` | EXTC Engineering |
| `user011` | Daniel Rodriguez | `pass808` | Computer Science |
| `user012` | Jessica White | `pass909` | Information Technology |
| `user013` | Matthew Harris | `pass010` | Mechanical Engineering |
| `user014` | Ashley Clark | `pass111` | Electrical Engineering |
| `user015` | Andrew Lewis | `pass222` | EXTC Engineering |
| `user016` | Samantha Walker | `pass333` | Computer Science |
| `user017` | James Hall | `pass444` | Information Technology |

### **Your Test User**
- **User ID:** `0001`
- **Password:** `12345678`
- **Name:** Test User
- **Department:** Computer Science

## ✅ **What Was Fixed**

1. **Database Schema:** Updated `pw` column from `varchar(8)` to `varchar(255)`
2. **Password Hashing:** All dummy users now have proper bcrypt password hashes
3. **Login System:** Password verification now works correctly
4. **SQL Files:** Updated both `voting_system.sql` and `voting_system_with_dummy_data.sql`

## 🚀 **How to Test**

1. Go to the login page: `http://localhost/voting-system/login.php`
2. Try logging in with any of the credentials above
3. All logins should now work properly!

## 🔧 **Technical Details**

- **Password Hashing:** bcrypt with cost factor 10
- **Hash Length:** 60 characters
- **Database Column:** `varchar(255)` to accommodate full hashes
- **Backward Compatibility:** Admin account still uses plain text for compatibility

---
*Last Updated: September 17, 2025*

