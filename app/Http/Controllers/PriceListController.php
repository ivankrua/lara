<?php

namespace App\Http\Controllers;

use App\PriceList;
use Illuminate\Http\Request;

class PriceListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json(['data' => PriceList::all()]);
    }
}
