<?php
/**
 * @var User $user
 */

use Apex\models\User;
use Apex\src\Views\Forms\Form;

?>
<div class="p-0 sm:p-12 mx-auto max-w-md px-6 py-12 bg-white border-0 shadow-lg sm:rounded-3xl register">
    <h1 class="text-2xl font-bold mb-8">Create an Account</h1>
    <?php $form = Form::begin(['method' => 'post', 'options' => ['novalidate' => true, 'class' => 'test']]) ?>
    <div class="relative z-0 w-full mb-5">
        <?= $form->field($user, 'name', ['class' => 'register-inputs', 'required' => true, 'type' => 'text', 'id' => 'name']) ?>
        <label for="name" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter name</label>
        <span class="text-sm text-red-600" id="error"><?= $user->errorBag->first('name') ?></span>
    </div>

    <div class="relative z-0 w-full mb-5">
        <?= $form->field($user, 'email', ['class' => 'register-inputs', 'required' => true, 'type' => 'email', 'id' => 'email']) ?>
        <label for="email" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter email
            address</label>
        <span class="text-sm text-red-600" id="error"><?= $user->errorBag?->first('email') ?></span>
    </div>

    <div class="relative z-0 w-full mb-5">
        <?= $form->field($user, 'password', ['class' => 'register-inputs', 'required' => true, 'type' => 'password', 'id' => 'password']) ?>
        <label for="password" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Enter
            password</label>
        <span class="text-sm text-red-600" id="error"><?= $user->errorBag?->first('password') ?></span>
    </div>

    <div class="relative z-0 w-full mb-5">
        <?= $form->field($user, 'confirm_password', ['class' => 'register-inputs', 'required' => true, 'type' => 'password', 'id' => 'confirm-password']) ?>
        <label for="confirm-password" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Confirm
            Password</label>
        <span class="text-sm text-red-600" id="error"><?= $user->errorBag?->first('confirm_password') ?></span>
    </div>


    <div class="relative z-0 w-full mb-5">
        <div class="relative z-0 w-full mb-5">
            <?= $form->field($user, 'birth_date', ['class' => 'register-inputs', 'required' => true, 'type' => 'date', 'id' => 'date']) ?>
            <label for="date" class="absolute duration-300 top-3 -z-1 origin-0 text-gray-500">Date</label>
            <span class="text-sm text-red-600" id="error"><?= $user->errorBag?->first('birth_date') ?></span>
        </div>
    </div>
    <button
            id="submitBtn"
            class="w-full px-6 py-3 mt-3 text-lg text-white transition-all duration-150 ease-linear rounded-lg shadow outline-none bg-gray-500 hover:bg-gray-800 hover:shadow-lg focus:outline-none"
    >
        register
    </button>
    <?= Form::end() ?>
</div>
