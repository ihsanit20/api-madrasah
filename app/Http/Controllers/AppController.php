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
        // Get the domain from the request
        $domain = $request->getHost();

        // Use the domain to fetch data from the database
        $data = $this->getDataForDomain($domain);

        return response($data, 200);
    }

    private function getDataForDomain($domain)
    {
        $data = [];

        $data["domain"] = self::$domain;
        $data["client"] = self::$client;

        $data["institute"] = [
            "name" => [
                "english"   => "MSI Institute" . " : " . (self::$client["name"] ?? ""),
                "bengali"   => "মাদরাসার নাম",
                "arabic"    => "الاسم عربي",
            ],
            "icon"  => "https://ui-avatars.com/api/?name=MSI+Institute",
            "logo"  => "https://ui-avatars.com/api/?name=MSI+Institute",
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

        $data["is_logged_in"] = auth('sanctum')->check();

        return $data;
    }
}
