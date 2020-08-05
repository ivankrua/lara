<?php

namespace App\Http\Controllers;

use App\PriceList;
use Illuminate\Http\Request;

class PriceListAdminController extends PriceListController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $serverList = PriceList::all();
        $freshData = json_decode($request->input('data'), true);
        foreach ($freshData as $item)
        {
            $current = $serverList->find($item['id']);
            if ($current !== null)
            {
                //update
                $this->updateModel($current, json_decode($request->input('data'), true));
            } else
            {
                $record = PriceList::create($item);
                $record->provider = $item['provider'];
                $record->brand = $item['brand'];
                $record->location = $item['location'];
                $record->cpu = $item['cpu'];
                $record->drive = $item['drive'];
                $record->price = $item['price'];
                $record->save();
            }
        }
        $response = ['data' => PriceList::all()];
        return response($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\PriceList $priceList
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(PriceList $priceList, int $id)
    {
        $model = PriceList::all()->find($id);
        $result = $model ? ['data' => $model->toArray()] : ['message' => 'Not found'];
        return response($result, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\PriceList $priceList
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PriceList $priceList, int $id)
    {
        $model = $this->updateModel(
            PriceList::all()->find($id),
            json_decode($request->input('data'), true)
        );
        $response = $model ? ['data' => $model->toArray()] : ['message' => 'Not found'];
        return response($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\PriceList $priceList
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PriceList $priceList, int $id)
    {
        $model = PriceList::all()->find($id);
        if ($model !== null)
        {
            $model->delete();
        }
        $response = ['message' => 'delete ok'];
        return response($response, 200);
    }

    private function updateModel($current, array $data)
    {
        if ($current !== null)
        {
            foreach ($data as $k=>$v)
            {
                switch($k)
                {
                    case 'provider':
                        $current->provider=$v;
                        break;
                    case 'brand':
                        $current->brand=$v;
                        break;
                    case 'location':
                        $current->location=$v;
                        break;
                    case 'cpu':
                        $current->cpu=$v;
                        break;
                    case 'drive':
                        $current->drive=$v;
                        break;
                    case 'price':
                        $current->price=$v;
                        break;
                }
            }
            $current->save();
        }
        return $current;
    }
}
