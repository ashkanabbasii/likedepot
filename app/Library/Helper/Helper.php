<?php namespace App\Library\Helper;

use App\Models\Transaction\Transaction;
use App\Models\User\User;


/**
 * Helper methods
 */
class Helper
{
    public static function uploadFile($image , $id , $type , $used = null)
    {
        $name = $id;
        if($type == "user")
        {
            $part = '/uploads/' . $type . '/' . $id;
        }
        elseif($type == "services"){
            if($used == "gallery")
            {
                $name = time();
            }
            $part = '/uploads/' . $type . '/'. $id . '/' . $used ;
        }
        else{
            $part =  '/uploads/' . $type ;
        }

        $folder = public_path() . $part;
        if (!\File::exists($folder))
            $path = \File::makeDirectory($folder, 0777, true);

        $image->move($folder, $name . ".png");

        $url = $folder . "/" . $name . ".png";


        return  $part. '/' . $name . ".png";
    }

    public static function hasSourceImage($id)
    {
        return file_exists(public_path("uploads/sources/$id.png"));
    }

    public static function IsUniqueUser($username)
    {
        $user = User::where(["username" => $username])->first();

        if ($user) {
            return false;
        } else {
            return true;
        }

    }
    public static function IsUniqueEmail($email)
    {
        $user = User::where(["email" => $email])->first();

        if ($user) {
            return false;
        } else {
            return true;
        }

    }

    //withdraw , deposit
    public static function addTransaction($ref_type , $ref_id , $type , $amount , $user_id)
    {

//'user_id','type','ref_id','ref_type','amount'
        Transaction::create([
            "user_id" => $user_id,
            "amount" => $amount,
            "type" => $type,
            "ref_id" => $ref_id,
            "ref_type" => $ref_type
        ]);

    }
}