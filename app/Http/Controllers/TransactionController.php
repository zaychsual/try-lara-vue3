<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $index = Transaction::orderBy('time', 'desc')->get();

        $res = [
            'msg' => 'List Transaction',
            'data' => $index
        ];

        return response()->json($res, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        DB::beginTransaction();
        try {
            $post = Transaction::create($request->all());
            DB::commit();
            $res = [
                'msg' => 'Created Successfully',
                'data' => $post
            ];

            return response()->json($res, Response::HTTP_CREATED);
        } catch (\Exception $th) {
            DB::rollBack();
            $msg = "Failed ".$th->getMessage();
            return response()->json($msg, Response::HTTP_BAD_REQUEST);
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
        $show = Transaction::findOrFail($id);

        $res = [
            'msg' => 'Detail Transaction',
            'data' => $show
        ];

        return response()->json($res, Response::HTTP_OK);
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
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        DB::beginTransaction();
        try {
            $patch = Transaction::findOrFail($id);
            $patch->title = $request->title;
            $patch->amount = $request->amount;
            $patch->type = $request->type;
            $patch->update();

            DB::commit();
            $res = [
                'msg' => 'Updated Successfully',
                'data' => $patch
            ];

            return response()->json($res, Response::HTTP_CREATED);
        } catch (\Exception $th) {
            DB::rollBack();
            $msg = "Failed " . $th->getMessage();
            return response()->json($msg, Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();

        $res = [
            'msg' => 'Deleted Successfully',
        ];

        return response()->json($res, Response::HTTP_OK);
    }
}
