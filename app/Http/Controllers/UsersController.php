<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class UsersController extends Controller
{

    public function index(Request $request){
        try {
            //code...
            $pageNumber = ( $request->start / $request->length )+1;
            $pageLength = $request->length;
            $skip       = ($pageNumber-1) * $pageLength;

            $query = Users::join('user_activity_histories',"user_activity_histories.user_id","=","users.id")
                            ->join("activities","activities.id","=","user_activity_histories.activity_id")
                            ->select("users.id","users.name","users.user_points as total_points","users.rank")
                            ->where("users.rank",">",3)
                            ->groupBy(["users.id","users.name","users.user_points","users.rank"])->orderBy("users.rank","asc");


                if($request->filled('filter_by')){
                    $filter_by = $request->filter_by;
                    if($filter_by == "day"){
                        $query = $query->where("user_activity_histories.entry_date",date("Y-m-d"));
                    }
                    if($filter_by == "month"){
                        $query = $query->whereBetween("user_activity_histories.entry_date",[date("Y-m-t"),date("Y-m-d")]);
                    }
                    if($filter_by == "year"){
                        $query = $query->whereBetween("user_activity_histories.entry_date",[date("Y")."-01-01",date('Y')."12-31"]);
                    }
                }
                if($request->filled('search_by')){
                    $query = $query->where("users.id",$request->search_by);
                }

                $recordsFiltered = $recordsTotal = count($query->get()->toArray());
                $data = $query->offset($request->start)->limit($request->length != -1 ? $request->length : $recordsTotal)
                            ->get()->toArray();
            return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $data], 200);

        } catch (Throwable $th) {
            return response()->json(["draw"=> $request->draw, "recordsTotal"=> 0, "recordsFiltered" => 0, 'data' => [],"msg" => $th->getMessage()], 422);
        }
    }

    public function dash(){
        return view("dash");
    }

    public function reFilter(){
        try {
            //code...
            $query = Users::join('user_activity_histories',"user_activity_histories.user_id","=","users.id")
                            ->join("activities","activities.id","=","user_activity_histories.activity_id")
                            ->select("users.id","users.name",DB::raw("SUM(activities.points) as total_points"),"users.rank")
                            ->groupBy(["users.id","users.name","users.rank"])->orderBy(DB::raw("SUM(activities.points)"),"desc")->get()->toArray();
            $rank = 1;
            foreach($query as $q){
                Users::where('id',$q['id'])->update(['rank' => $rank,'user_points' => $q['total_points']]);
                $rank++;
            }
            return response()->json(["success" => true,"msg" => "Data updated successfully","data" => $query],200);
        } catch (Throwable $th) {
            //throw $th;
            return response()->json(["success" => false,"msg" => $th->getMessage()],500);
        }
    }

    public function getTop3Users(){
        try {
            //code...
            $query = Users::join('user_activity_histories',"user_activity_histories.user_id","=","users.id")
                            ->join("activities","activities.id","=","user_activity_histories.activity_id")
                            ->select("users.id","users.name","users.user_points as total_points","users.rank")
                            ->where("users.rank","<=",3)
                            ->groupBy(["users.id","users.name","users.user_points","users.rank"])->orderBy("users.rank","asc")->get()->toArray();
            return response()->json(["success" => true,"msg" => "Data retrieved successfully","data" => $query],200);
        } catch (Throwable $th) {
            return response()->json(["success" => false,"msg" => $th->getMessage()],500);
        }
    }
}
