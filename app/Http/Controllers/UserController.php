<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
// use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index');
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
    public function store(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $user->storeData($request->all());

        return response()->json(['success'=>'¡Usuario agregado satisfactoriamente!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $users)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = new User;
        $data = $user->findData($id);

        $html = '<div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" class="form-control" name="name" id="editName" value="'.$data->name.'">
                </div>
                <div class="form-group">
                    <label for="cellphone">Celular:</label>
                    <input type="text" class="form-control" name="cellphone" id="editCellphone" value="'.$data->cellphone.'">
                </div>
                <div class="form-group">
                    <label for="document">Núm. de Cédula:</label>
                    <input type="text" class="form-control" name="document" id="editDocument" value="'.$data->document.'">
                </div>
                <div class="form-group">
                    <label for="date_of_birth">Fec. de Nac.:</label>
                    <input type="text" class="form-control" name="date_of_birth" id="editDateOfBirth" value="'.date("Y-m-d",strtotime($data->date_of_birth)).'">
                </div>';

        return response()->json(['html'=>$html]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'cellphone' => ['digits:10'],
            'document' => ['required', 'string', 'max:11'],
            'date_of_birth' => ['required', 'date', 'before:18 years ago'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        $user = new User;
        $user->updateData($id, $request->all());

        return response()->json(['success'=>'¡Usuario actualizado satisfactoriamente!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = new User;
        $user->deleteData($id);

        return response()->json(['success'=>'¡Usuario eliminado satisfactoriamente!']);
    }

    // Custom functions


    /**
     * Get the data for listing in table (index.blade.php).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getUsers(Request $request, User $user)
    {
        $data = $user->getData();
        return datatables()->of($data)
            ->addColumn('Actions', function($data) {
                return '<div class="row" style="width: max-content; grid-gap: 10px; margin: 15px 0px;">
                            <button type="button" class="btn btn-success btn-sm" id="getEditUserData" data-id="'.$data->id.'">Editar</button>
                            <button type="button" data-id="'.$data->id.'" data-toggle="modal" data-target="#DeleteUserModal" class="btn btn-danger btn-sm" id="getDeleteId">Eliminar</button>
                        </div>';
            })
            ->rawColumns(['Actions'])
            ->make(true);
    }
}
