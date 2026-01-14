# TODO: Implement Login by Email or Phone Number

- [x] Create migration to add 'mobile' column to users table (string, nullable, unique)
- [x] Update User model: add 'mobile' to fillable and hidden arrays
- [x] Modify vendor/tcg/voyager/resources/views/users/edit-add.blade.php to include mobile input field with validation
- [x] Create custom VoyagerAuthController extending \TCG\Voyager\Http\Controllers\VoyagerAuthController, override login method to find user by email or mobile
- [x] Update routes/web.php to use custom controller for login route before Voyager::routes()
- [x] Update Voyager user BREAD (if needed) to include mobile field
- [x] Run migration to add mobile column
- [x] Test login with email and phone
- [x] Ensure Voyager admin can edit mobile field
