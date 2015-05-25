<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful'] 	  	 = 'Akun Berhasil Dibuat';//'Account Successfully Created';
$lang['account_creation_unsuccessful'] 	 	 = 'Tidak Dapat Membuat Akun';//'Unable to Create Account';
$lang['account_creation_duplicate_email'] 	 = 'Email Sudah Digunakan atau Salah';//'Email Already Used or Invalid';
$lang['account_creation_duplicate_username'] = 'Username Sudah Digunakan atau Salah';//'Username Already Used or Invalid';

// Password
$lang['password_change_successful'] 	 	 = 'Password Berhasil Diubah';//'Password Successfully Changed';
$lang['password_change_unsuccessful'] 	  	 = 'Tidak Dapat Merubah Password';//'Unable to Change Password';
$lang['forgot_password_successful'] 	 	 = 'Email Pengembalian Password Terkirim';//'Password Reset Email Sent';
$lang['forgot_password_unsuccessful'] 	 	 = 'Tidak Dapat Melakukan Pengembalian Password';//'Unable to Reset Password';

// Activation
$lang['activate_successful'] 		  	     = 'Akun diaktifkan';//'Account Activated';
$lang['activate_unsuccessful'] 		 	     = 'Tidak Dapat Mengaktifkan Akun';//'Unable to Activate Account';
$lang['deactivate_successful'] 		  	     = 'Akun Di Non-aktifkan';//'Account De-Activated';
$lang['deactivate_unsuccessful'] 	  	     = 'Tidak dapat Me-non-aktifkan Akun';//'Unable to De-Activate Account';
$lang['activation_email_successful'] 	  	 = 'Email Aktifasi Terkirim';//'Activation Email Sent';
$lang['activation_email_unsuccessful']   	 = 'Tidak Dapat Mengirim Email Aktifasi';//'Unable to Send Activation Email';

// Login / Logout
$lang['login_successful'] 		  	         = 'Berhasil Login';//'Logged In Successfully';
$lang['login_unsuccessful'] 		  	     = 'Login Salah';//'Incorrect Login';
$lang['login_unsuccessful_not_active'] 		 = 'Akun tidak aktif';//'Account is inactive';
$lang['login_timeout']                       = 'Terkunci. Silakan coba lagi.';//'Temporarily Locked Out.  Try again later.';
$lang['logout_successful'] 		 	         = 'Berhasil Logout';//'Logged Out Successfully';

// Account Changes
$lang['update_successful'] 		 	         = 'Informasi Akun Berhasil Diperbarui';//'Account Information Successfully Updated';
$lang['update_unsuccessful'] 		 	     = 'Informasi Akun Gagal Diperbarui';//'Unable to Update Account Information';
$lang['delete_successful']               = 'Pengguna Terhapus';//'User Deleted';
$lang['delete_unsuccessful']           = 'Pengguna Gagal Terhapus';//'Unable to Delete User';

// Groups
$lang['group_creation_successful']  = 'Grup Berhasil Dibuat';//'Group created Successfully';
$lang['group_already_exists']       = 'Nama grup sudah tersedia';//'Group name already taken';
$lang['group_update_successful']    = 'Detail grup berhasil diperbarui';//'Group details updated';
$lang['group_delete_successful']    = 'Grup terhapus';//'Group deleted';
$lang['group_delete_unsuccessful'] 	= 'Tidak dapat menghapus grup';//'Unable to delete group';
$lang['group_name_required'] 		= 'Kolom nama grup wajib diisi';//'Group name is a required field';

// Activation Email
$lang['email_activation_subject']            = 'Aktifasi Akun';//'Account Activation';
$lang['email_activate_heading']    = 'Mengaktifkan akun %s';//'Activate account for %s';
$lang['email_activate_subheading'] = 'Silakan klik tautan ini untuk %s';//'Please click this link to %s.';
$lang['email_activate_link']       = 'Aktifasi Akun Anda';//'Activate Your Account';

// Forgot Password Email
$lang['email_forgotten_password_subject']    	= 'Verifikasi Lupa Password';//'Forgotten Password Verification';
$lang['email_forgot_password_heading']    		= 'Reset Password untuk';//'Reset Password for %s';
$lang['email_forgot_password_subheading'] 		= 'Silakan klik tautan ini untuk %s';//'Please click this link to %s.';
$lang['email_forgot_password_link']       		= 'Reset Password Anda';//'Reset Your Password';

// New Password Email
$lang['email_new_password_subject']          	= 'Password Baru';//'New Password';
$lang['email_new_password_heading']    			= 'Password Baru untuk %s';//'New Password for %s';
$lang['email_new_password_subheading'] 			= 'Password Anda Berhasil di reset menjadi: %s';//'Your password has been reset to: %s';
