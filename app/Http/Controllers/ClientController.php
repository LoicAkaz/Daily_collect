<?php

namespace App\Http\Controllers;


use App\Models\client as Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Client::all();
        return response()->json($client, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        return response()->json($request, 200);
        $fields = $request->validate([
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'string|max:255',
            'addresse_client' => 'required|string|max:255',
            'sexe_client' => 'required|string|max:1',
            'age' => 'required|integer|max:100',
            'cni_client' => 'required|string||max:15|unique:client',
            'telephone_client' => 'required|string||max:15|unique:client',
            'id_user' => 'required|integer|exists:users,id_user',
        ]);
//        $fields['password'] = bcrypt($fields['password']);
        $photofile = $request->file('photo_client');

        $fields = array_merge($fields, ["id_client" => $this->generateID(), "photo_client" => trim($request->nom_client)
            . "_" . $request->prenom_client . "." . $photofile->extension()]);
//        $client = Client::create($fields);
        $client = Client::create($fields);
        $document_extension = ["jpg", "png"];
        if ($client) {
            $path = "public/profils";
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0777, true);
            }
            if (($photofile != null) && (in_array($photofile->extension(), $document_extension))) {
                $photofile->storeAs($path, "{$request->nom_client}_{$request->prenom_client}.{$photofile->extension()}");
            }
        }
        return response()->json($client, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        return response()->json($client, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
//        return response()->json($request, 200);
        $fields = $request->validate([
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'string|max:255',
            'addresse_client' => 'required|string|max:255',
            'sexe_client' => 'required|string|max:1',
            'age' => 'required|integer|max:100',
            'cni_client' => 'string||max:15',
            'telephone_client' => 'string||max:15',
        ]);

        $photofile = $request->file('photo_client');
//       echo printf("error is here : ");

        $fields = array_merge($fields, ["photo_client" => trim($request->nom_client)
            . "_" . $request->prenom_client . "." . $photofile->extension()]);
//        $fields['password'] = bcrypt($fields['password']);
        $client->update($fields);
        $document_extension = ["jpg", "png"];
        if ($client) {
            $path = "public/profils";
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0777, true);
            }
            if (($photofile != null) && (in_array($photofile->extension(), $document_extension))) {
                $photofile->storeAs($path, "{$request->nom_client}_{$request->prenom_client}.{$photofile->extension()}");
            }
        }
        return $client;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return ["message" => "Client has been deleted"];
    }

    function generateID()
    {
        $id = 'C' . rand(100, 999) . 'T';
        $client = Client::where('id_client', $id)->first();
        if ($client) {
            return $this->generateID();
        }
        return $id;
    }
}
