<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class InstituteController extends Controller
{
    const INSTITUTE_PROPERTIES = [
        "icon"          => "institute-icon",
        "logo"          => "institute-logo",
        "basic_info"    => "institute-basic-info",
        "address_info"  => "institute-address-info",
        "contact_info"  => "institute-contact-info",
    ];

    public function index()
    {
        $settings = Setting::query()
            ->properties(array_values(self::INSTITUTE_PROPERTIES))
            ->get();

        $institute = [];

        foreach (self::INSTITUTE_PROPERTIES as $key => $property) {
            $institute[$key] = (object) (($settings->firstWhere('property', $property) ?? [])->value ?? []);
        }

        // return $institute;
        
        return response($institute);
    }

    public function show(string $key)
    {
        $property = self::INSTITUTE_PROPERTIES[$key] ?? null;
        
        if($property) {
            $setting = Setting::query()
                ->property($property)
                ->first();
        }

        return response([
            $key => (object) ($setting->value ?? []),
        ]);
    }

    public function update(Request $request, string $key)
    {
        $property = self::INSTITUTE_PROPERTIES[$key] ?? null;
        
        if($property) {
            $setting = Setting::withTrashed()
                ->updateOrCreate(
                    [
                        "property"      => $property,
                    ],
                    [
                        "value"         => $request->$key,
                        "deleted_at"    => null
                    ]
                );
        }

        return response([
            $key => (object) ($setting->value ?? [])
        ]);
    }

    /*
    [
        "icon" => [
            "link"  => "",
        ],
        "logo" => [
            "link"  => "",
        ],
        "basic_info"    => [
            "bengali_name"   => "প্রতিষ্ঠানের নাম বাংলায়",
            "english_name"   => "Institution name in English",
            "arabic_name"    => "اسم المؤسسة باللغة العربية",
        ],
        "address_info"  => [
            "address"       => "House No, Road No, Village",
            "post_office"   => "Post office",
            "area"          => "Area",
            "area_id"       => (int) (0),
            "district"      => "District",
            "division"      => "Division",
        ],
        "contact_info"  => [
            "phone"     => "01234567890",
            "alternative_phone" => "01234567890",
            "whatsapp"  => "01234567890",
            "email"     => "institue@gmail.com",
            "facebook"  => "https://facebook.com/facebookusername",
            "youtube"   => "https://youtube.com/@youtubeusername",
        ],
    ]
    */
}
