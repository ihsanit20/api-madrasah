<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
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

        $data["institute"] = [
            "name" => "MSI Institute",
            "logo" => "https://ui-avatars.com/api/?name=MSI+Institute",
        ];

        $data["is_logged_in"] = auth('sanctum')->check();

        return $data;
    }
}
