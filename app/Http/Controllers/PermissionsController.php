<?php

namespace App\Http\Controllers;

use App\Models\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionsFormRequest;
use Exception;

class PermissionsController extends Controller
{

    /**
     * Display a listing of the permissions.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $permissionsObjects = Permissions::paginate(10);

        return view('permissions.index', compact('permissionsObjects'));
    }

    /**
     * Show the form for creating a new permissions.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        
        
        return view('permissions.create');
    }

    /**
     * Store a new permissions in the storage.
     *
     * @param App\Http\Requests\PermissionsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(PermissionsFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            Permissions::create($data);

            return redirect()->route('permissions.permissions.index')
                             ->with('success_message', 'Permissions was successfully added!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Display the specified permissions.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $permissions = Permissions::findOrFail($id);

        return view('permissions.show', compact('permissions'));
    }

    /**
     * Show the form for editing the specified permissions.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $permissions = Permissions::findOrFail($id);
        

        return view('permissions.edit', compact('permissions'));
    }

    /**
     * Update the specified permissions in the storage.
     *
     * @param  int $id
     * @param App\Http\Requests\PermissionsFormRequest $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, PermissionsFormRequest $request)
    {
        try {
            
            $data = $request->getData();
            
            $permissions = Permissions::findOrFail($id);
            $permissions->update($data);

            return redirect()->route('permissions.permissions.index')
                             ->with('success_message', 'Permissions was successfully updated!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }        
    }

    /**
     * Remove the specified permissions from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $permissions = Permissions::findOrFail($id);
            $permissions->delete();

            return redirect()->route('permissions.permissions.index')
                             ->with('success_message', 'Permissions was successfully deleted!');

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }



}
