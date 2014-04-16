<?php
namespace SlimForm\Validator;

interface ValidatorInterface {
    public function validate();

    public function getCode();

    public function getMessage();
}