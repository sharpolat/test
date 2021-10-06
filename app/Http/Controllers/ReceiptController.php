<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receipt;
use Illuminate\Support\Carbon;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $thisWeek = 
        $items = Receipt::latest()->whereBetween('created_at',[
            Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()
        ])
        ->paginate(8);
        return view('receipt.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_name' => 'required|max:15|min:3',
            'image_path' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:2048',
        ]);
        $item = new Receipt();
        if($request->file()) {
            $images = $request->file('image_path');
            $fileName = time().'_'.$request->image_path->getClientOriginalName();
            $filePath = 'image';
            $images->move($filePath, $fileName);
            $item->image_path = $fileName;
        }
        $item->user_name = $request->user_name;
        $item->type = (date('h')%2==0) ? 'обычный':'призовой';
        $item->code = (date('h')%2==0) ? null : rand(10000000, 99999999);
        $item->status = (date('s')%2==0) ? 'принят' : 'отклонен';
        $result = $item->save();
        if($result) {
            return back()->withSuccess('Ваш чек принят')->withInput();
        }
        else {
            return back()->withErrors(['msg'=>'Ошибка'])
                         ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
