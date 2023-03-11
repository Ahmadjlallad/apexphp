<?php
/**
 * @var User $user
 */

use Apex\models\User;
$this->title = 'Register';
if ($loginError = session()->getFlash('register-errors')) {
    //@todo handle errors summary
    dd($loginError);
}
?>

<div class="p-0 sm:p-12 mx-auto max-w-md px-6 py-12 bg-white border-0 shadow-lg sm:rounded-3xl register">
    <h1 class="text-2xl font-bold mb-8">Create an Account</h1>
    <form method="post">
        <div class="relative z-0 w-full mb-5">
            <input value="<?= params('name') ?>" type="text" name="name" id="name" class="register-inputs">
            <label for="name" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter name</label>
            <span class="text-sm text-red-600" id="name-error"><?= errors()->first('name') ?></span>
        </div>

        <div class="relative z-0 w-full mb-5">
            <input value="<?= params('email') ?>" type="email" name="email" id="email" class="register-inputs">
            <label for="email" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter email
                address</label>
            <span class="text-sm text-red-600" id="email-error"><?= errors()->first('email') ?></span>
        </div>

        <div class="relative z-0 w-full mb-5">
            <input value="<?= params('password') ?>" type="password" name="password" id="password" class="register-inputs">
            <label for="password" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter
                password</label>
            <span class="text-sm text-red-600" id="error-password"><?= errors()->first('password') ?></span>
        </div>

        <div class="relative z-0 w-full mb-5">
            <input value="<?= params('confirm_password') ?>" type="password" name="confirm_password" id="confirm_password" class="register-inputs">
            <label for="confirm_password" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Confirm
                Password</label>
            <span class="text-sm text-red-600"
                  id="error-confirm-password<?= errors()->first('confirm_password') ?>"></span>
        </div>


        <div class="relative z-0 w-full mb-5">
            <div class="relative z-0 w-full mb-5">
                <input dataformatas="yyyy-MM-dd" value="<?= params('birth_date') ?>" type="date" name="birth_date" id="birth_date" class="register-inputs">
                <label for="birth_date" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Date</label>
                <span class="text-sm text-red-600" id="error-birth_date"><?= errors()->first('birth_date') ?></span>
            </div>
        </div>
        <button
                id="submitBtn"
                class="w-full px-6 py-3 mt-3 text-lg text-white transition-all duration-150 ease-linear rounded-lg shadow outline-none bg-gray-500 hover:bg-gray-800 hover:shadow-lg focus:outline-none"
        >
            register
        </button>
    </form>
</div>
