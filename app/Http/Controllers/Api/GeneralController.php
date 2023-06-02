<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralController extends Controller {
    public function place(Request $request) {

        $lat  = $request->lat;
        $long = $request->long;

        $data = Place::select("places.*"
                , DB::raw("6371 * acos(cos(radians(" . $lat . "))
                * cos(radians(places.lat))
                * cos(radians(places.long) - radians(" . $long . "))
                + sin(radians(" . $lat . "))
                * sin(radians(places.lat))) AS distance"))
            ->orderBy('distance', 'asc')
            ->paginate();

        return response()->json([
            'status' => true,
            'data'   => $data,
        ]);
    }

}
