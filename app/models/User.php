<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'email');

    static function findAllUser()
    {
        $users = static::all();
        return $users;
    }

    static function saveUser($postData = array())
    {
        if(empty($postData)) return;

        $user = new User();

        $user->name       = $postData['name'];
        $user->email      = $postData['email'];
        $user->password   = Hash::make(input::get('password'));
        $user->birth_date = date('Y-m-d', strtotime($postData['birth_date']));
        $user->gender     = $postData['gender'];
        $user->created_at = date('Y-m-d H:i:s');

        $user->save();

        $insertedId = $user->id;
    }

    static function findById($id)
    {
        $data = static::where('id', '=', $id)->get();
        if (count($data)) {
            return $data[0];
        } else {
            return FALSE;
        }
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }

}
