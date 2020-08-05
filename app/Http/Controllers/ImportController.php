<?php

namespace App\Http\Controllers;

use App\ImportCache;
use App\PriceList;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file();
        if ($file !== null)
        {
            $f = $file->openFile('r');
            $jsonData = '';
            while (!$f->eof())
            {
                $jsonData .= $f->fgets();
            }
            $json = json_decode($jsonData, true);
            if ($json !== null && array_key_exists('data', $json))
            {
                $this->addNewItems($json['data']);
                $this->findInDB($json['data']);
                PriceList::whereNOTIn('id', function ($query)
                {
                    ImportCache::all(['recordid']);
                })->delete();
            }
        }
        $response = ['data' => PriceList::all()];
        return response($response, 200);
    }

    private function addNewItems($list)
    {
        foreach ($list as $item)
        {
            $query = [];
            foreach ($item as $k => $v)
            {
                if (in_array($k, ['provider', 'brand', 'location', 'cpu', 'drive']))
                {
                    $query[$k] = $v;
                }
            }
            PriceList::updateOrCreate($query, ['price' => array_key_exists('price', $item) ? $item['price'] : 0]);
        }
    }

    private function findInDB($list)
    {
        ImportCache::truncate();
        foreach ($list as $item)
        {
            $query = [];
            foreach ($item as $k => $v)
            {
                if (in_array($k, ['provider', 'brand', 'location', 'cpu', 'drive']))
                {
                    $query[] = [$k, '=', $v];
                }
            }
            $model = PriceList::where($query)->first();
            if ($model !== null)
            {
                $result[] = $model->id;
                $rec = new ImportCache();
                $rec->recordid = $model->id;
                $rec->save();
            }
        }
    }
}
