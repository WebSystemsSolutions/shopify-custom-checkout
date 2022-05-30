<?php

namespace App\Forms;

use Kris\LaravelFormBuilder\Field;
use Kris\LaravelFormBuilder\Form;

class CheckoutForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('email', Field::TEXT, [
                'rules' => 'email'
            ])
            ->add('phone', Field::TEXTAREA, [
                'rules' => 'max:10'
            ])
            ->add('city', Field::TEXT, [
                'rules' => ['required', 'string', 'max:255', 'regex:/^[#.0-9a-zA-Z\s,-]+$/']
            ])
            ->add('submit', Field::BUTTON_SUBMIT);
    }
}
