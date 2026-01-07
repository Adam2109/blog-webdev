<?php

class LoginCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amOnPage('?r=site/login');
    }

    public function checkLoginWorking(\FunctionalTester $I)
    {

        $I->see('Вхід', 'h1');
    }

    public function checkWrongPassword(\FunctionalTester $I)
    {

        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong_password_12345',
        ]);


        $I->see('Incorrect username or password');
    }
}