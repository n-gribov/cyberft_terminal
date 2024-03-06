<?php

namespace common\models\form;

use common\base\Model;
use common\models\User;

abstract class UserPasswordForm extends Model
{
    abstract public function getUser(): ?User;
}
