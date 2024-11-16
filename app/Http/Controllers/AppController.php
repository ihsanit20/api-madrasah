<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public static $domain = null;
    public static $client = null;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $data = self::$client
            ? $this->clientAppData()
            : $this->guestAppData();

        return response($data, 200);
    }

    private function commonAppData()
    {
        $data = [];

        $data["is_logged_in"] = auth('sanctum')->check();

        $data["is_active_client"] = (boolean) (self::$client);

        $data["domain"] = self::$domain;

        return $data;
    }

    private function guestAppData()
    {
        $data = $this->commonAppData();
        
        $data["name"] = "Madrasah.cc";
        $data["icon"] = "https://ui-avatars.com/api/?name=M";
        $data["logo"] = "https://ui-avatars.com/api/?name=M";

        return $data;
    }

    private function clientAppData()
    {
        $data = $this->commonAppData();
        
        $data["name"] = self::$client["name"];
        $data["icon"] = "https://ui-avatars.com/api/?name=" . mb_substr(self::$client["name"], 0, 1);
        $data["logo"] = "https://ui-avatars.com/api/?name=" . mb_substr(self::$client["name"], 0, 1);

        return $data;

        // should be remove
        $data["institute"] = [
            "name" => [
                "english"   => "Institute" . " : " . (self::$client["name"] ?? ""),
                "bengali"   => "মাদরাসার নাম",
                "arabic"    => "الاسم عربي",
            ],
            "icon"  => "https://ui-avatars.com/api/?length=1&name=Institute",
            "logo"  => "https://ui-avatars.com/api/?length=1&name=Institute",
            "address"       => "House No, Road No, Village name",
            "post_office"   => "Post office",
            "area"          => "Upazilla or Area",
            "district"      => "District name",
            "phone"         => "0123456789",
            "aulter_phone"  => "0123456789",
            "whatsapp"      => "0123456789",
            "email"         => "youremail@gmail.com",
            "facebook"      => [
                "name"  => "Facebook Page Name",
                "link"  => "https://www.facebook.com/",
            ],
            "youtube"   => [
                "name"  => "YouTube chanel name",
                "link"  => "https://www.youtube.com/",
            ],
        ];

        return $data;
    }
}
