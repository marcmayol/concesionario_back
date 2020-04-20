<?php

namespace App\Http\Controllers;


use App\Car;
use Illuminate\Http\Request;


class CarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('jwtAuth')->except('index')->except('show');

    }

    public function index()
    {
        try {
            $cars = Car::all()->load('user');
            return response()->json([
                'cars' => $cars,
                'status' => 'succes'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 'error'
            ], 400.6);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'data' => $request->all(),
                    'status' => 'success',
                ], 400.6);
            }
            $new_car = new Car();
            $new_car->title = $request->input('title');
            $new_car->description = $request->input('description');
            $new_car->price = $request->input('price');
            $new_car->status = $request->input('status');
            $new_car->user_id = $request->user->sub;
            $new_car->save();
            return response()->json([
                'car' => $new_car,
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e,
                'data' => $new_car,
                'status' => 'success',
            ], 400.6);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $cars = Car::find($id)->load('user');
            return response()->json([
                'cars' => $cars,
                'status' => 'succes'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 'error'
            ], 400.6);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'price' => 'required',
                'status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'data' => $request->all(),
                    'status' => 'success',
                ], 400.6);
            }
            $updated = Car::where('id', $id)->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'status' => $request->input('status')
            ]);
            if($updated) {
                $update_car = Car::find($id)->load('user');
                return response()->json([
                    'car' => $update_car,
                    'status' => 'success',
                ], 202);
            }
        } catch (\Exception $e) {
            return response()->json([
                'errors' => $e,
                'data' => $request->all(),
                'status' => 'success',
            ], 400.6);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $delete_car = Car::where('id',$id);
            $delete_car->delete();
            return response()->json([],204);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e,
                'status' => 'error'
            ], 400.6);
        }

    }
}
