<?php


use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Usuario extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait, RemindableTrait;

    protected $table = 'tb_persona';

    protected $primaryKey = 'ci';

    public $timestamps = false;

    public $remember_token = false;




    public function get_nombre_completo()
    {
        return $this->get_attribute('nombre_completo');
    }
    public function set_password($password)
    {
        $this->set_attribute('password', Hash::make($password));
    }

}
?>