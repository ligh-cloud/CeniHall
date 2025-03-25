<?php
namespace App\Repositories;
use App\Models\movie;
use App\Models\Siege;
use App\Models\User;
use App\Repositories\SiegeRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Repositories\MovieRepositoryInterface;
use Illuminate\Http\Request;


class SiegeRepository implements SiegeRepositoryInterface{
    protected $siegeReposetory;


    public function addSiege($data){
        $validatedData = $data->validate([
            'siege_number' => 'integer|max:100',

        ]);
        return $this->siegeReposetory->create($data);
    }
    public function all(){
        return Siege::with('salle')->get();
    }
    public function find($id){
        return Siege::with('salle')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Siege::create($data);
    }


    public function update(Siege $siege, array $data)
    {
        $siege->update($data);
        return $siege;
    }


    public function delete(Siege $siege)
    {
        return $siege->delete();
    }
}
