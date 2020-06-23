<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\ArticleResource;
use App\Http\Resources\BranchesResource;
use App\Http\Resources\UsersResource;
use App\Http\Resources\BaseAllResource;
use App\Http\Resources\VmContractResource;
use App\Http\Resources\TestResource;
use App\Http\Resources\AddChildrenGroupResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Filters\UsersFilter;
use App\Base;
use App\Branch;
use App\User;
use App\Role;
use App\Journal;
use App\Log;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;


class BaseController extends Controller
{

    public function getTest(Request $request){
        $base = Base::where('branch', '=', $request->id)->get();

        return AddChildrenGroupResource::collection($base);
    }

    public function saveTest(Request $request){

        $base = Base::find($request->id);
        $base->programm_id = $request->programm_id;
        $base->save();
    }

    public function updateTest(Request $request){

        $journal = Journal::create($request->all());
    }

    public function workout(Request $request){
        $base = Base::find($request->base_id);
        $contracts = $base->contracts->where('end_actually', '>', Carbon::today()->toDateString())->flatten()->map->only('id', 'category_time');

        if ($contracts->count() == 1) {
            
        }

        return $contracts->count();

    }







    public function test(Request $request){


        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->bases as $base) {
                $collection->push($base);
            }
        }

        return BaseAllResource::collection($collection->all());
    }

	public function index(){

        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->bases as $base) {
                $collection->push($base);
            }
        }

        return BaseAllResource::collection($collection->all());

	}

	public function addNewUser(Request $request){

        $base = Base::create($request->all());
        
        Log::create(array(
             'user_id' => Auth::id(),
             'channel' => '2', 
             'level_name' => 'success', 
             'message' => 'добавил клиента '.$base->id)
        );
        
        return $base->id;
    }

    public function getInfo(Request $request){

        $base = Base::find($request->id);
        
        return new ArticleResource($base);
    }

   	public function getVmContract(Request $request){

        $base = Base::find($request->id);
        
        return new VmContractResource($base->load(['base_branch']));
    }

    public function getBranches(){

        $user = User::find(Auth::user()->id);

        return new BranchesResource($user);
    }

    public function getManagers(){

        $roles = Role::where('title', 'like' , "%менеджер%")->get();

        $collection = collect();

            foreach ($roles as $role) {
                foreach ($role->rolesUsers as $value) {
                    $collection->push($value);
                }
        }

        return new UsersResource($collection);
    }

    public function getInstructors(){

        $roles = Role::where('title', 'like' , "%тренер%")->get();

        $collection = collect();

            foreach ($roles as $role) {
                foreach ($role->rolesUsers as $value) {
                    $collection->push($value);
                }
        }

        return new UsersResource($collection);
    }

    public function getProgramms(Request $request){

        $base = Base::find($request->id)->base_branch->programms;
        
        return new UsersResource($base);
    }

    public function getUsers(){

        return new UsersResource(User::select('id', 'name', 'surname')->get());
    }

    public function upload(Request $request){

        $path = $request['file']->store('public/avatars');
        $path = str_replace("public","storage", $path); 

        $base = Base::find($request['id']);
        $base->avatar = $path;
        $base->save();

        Log::create(array(
             'user_id' => Auth::id(),
             'channel' => '2', 
             'level_name' => 'success', 
             'message' => 'изменил аватар '.$base->id)
        );

        return $path;
    }

    public function filter(Request $request, UsersFilter $filters)
    {


        $user = User::find(Auth::user()->id);

        $collection = collect();

        foreach ($user->branches as $branch) {
            foreach ($branch->bases as $base) {
                $collection->push($base);
            }
        }

        // Исправить костыль с фильтром

        $users = Base::filter($filters)->get();


        $collectionA = $users->keyBy('id');

        $collectionB = $collection->keyBy('id');

        $collection = $collectionA->intersectByKeys($collectionB);

        if ($request->expectsJson()) {
            return BaseAllResource::collection($collection);
        }

        return view('pages.product', compact('users'));
    }

}
