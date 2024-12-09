<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use Carbon\Carbon;
use http\Params;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transaction =transaction::with('client')->get();
        return response()->json($transaction, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $fields=$request->validate([
            'montant' => 'required|integer|min:100',
            'longitude' => 'required|string',
            'latitude' => 'required|string',
            'id_user' => 'required|integer|exists:users,id_user',
            'id_client' => 'required|string|exists:client,id_client',
        ]);
//        $fields['password'] = bcrypt($fields['password']);
        $fields = array_merge($fields,["id_transaction" => $this ->generateID(), "type_trans"=>"Contrib"]);
        $transaction = transaction::create($fields);
        return $transaction;
    }

    /**
     * Display the specified resource.
     */
    public function show(transaction $transaction)
    {
        return response()->json($transaction, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, transaction $transaction)
    {
        $fields=$request->validate([
            'montant' => 'integer|min:100',
        ]);
        $longitude = $request->input("longitude");
        $latitude = $request->input("latitude");
        $fields = array_merge($fields,["longitude" => $longitude, "latitude"=>$latitude]);
//        $fields['password'] = bcrypt($fields['password']);
        $transaction->update($fields);
        return $transaction;
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(transaction $transaction)
    {
        $transaction->delete();
        return ["message"=> "Client has been deleted"];
    }


    public function clientTransactions(Request $request){
        $client = $request->input("client");
        $transactions = DB::table('transactions')->where("id_client",$client)->get();
        return response()->json($transactions, 200);
    }
    public function highestTransaction(Request $request){
        $client = $request->input("client");
        $transactions = DB::table('transactions')->where("id_client","=",$client)->max("montant");
        return response()->json($transactions, 200);
    }
    public function totalTransaction(Request $request){
        $client = $request->input("client");
        $transactions = DB::table('transactions')->where("id_client","=",$client)->sum("montant");
        return response()->json($transactions, 200);
    }
    public function averageTransaction(Request $request){
        $client = $request->input("client");
        $transactions = DB::table('transactions')->where("id_client","=",$client)->average("montant");
        return response()->json($transactions, 200);
    }

    public function latestTransactions(Request $request){
        $transactions = DB::table('transactions')->orderBy("created_at","desc")->take(3)->get();
        return response()->json($transactions, 200);
    }

    public function TotalTransactions(Request $request){
        $transactions = DB::table('transactions')->sum("montant");
        return response()->json($transactions, 200);
    }

    public function getSemestralTransactions(Request $request){
        $startOfLastSemester = Carbon::now()->startOfMonth()->subMonths(6); // First day of 6 months ago
        $endOfLastSemester = Carbon::now()->endOfMonth()->subMonth();       // Last day before this month

        // Fetch the sum of transactions grouped by month
        $data = DB::table('transactions')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(montant) as total')
            ->whereBetween('created_at', [$startOfLastSemester, $endOfLastSemester])
            ->groupBy('month')
            ->pluck('total', 'month'); // Pluck into an associative array [month => total]

        // Ensure all months within the last semester are included
        $monthlySums = [];
        for ($date = $startOfLastSemester->copy(); $date->lte($endOfLastSemester); $date->addMonth()) {
            $formattedMonth = $date->format('Y-m');
            $monthlySums[$formattedMonth] = $data->get($formattedMonth, 0); // Default to 0 if no transactions
        }

        return $monthlySums;
    }

    public function getMonthlyTransactions(Request $request){
//        $previousMonday = Carbon::now()->startOfWeek()->subWeek();
//        $previousSunday = Carbon::now()->endOfWeek()->subWeek();
//
//        $data = transaction::whereBetween('created_at', [$previousMonday, $previousSunday])->get();
//
//
//        return response()->json([$data, $previousMonday, $previousSunday], 200);

//        $previousMonday = Carbon::now()->startOfWeek()->subWeek();
//        $previousSunday = Carbon::now()->endOfWeek()->subWeek();
//
//        // Fetch data for the previous week
//        $data = transaction::whereBetween('created_at', [$previousMonday, $previousSunday])
//            ->get()
//            ->groupBy(function ($item) {
//                return Carbon::parse($item->created_at)->format('d-m-Y'); // Group by date
//            });
//
//        // Create an array for all days of the previous week
//        $weekDays = [];
//        for ($date = $previousMonday->copy(); $date->lte($previousSunday); $date->addDay()) {
//            $formattedDate = $date->format('d-m-Y');
//            $weekDays[$formattedDate] = $data->has($formattedDate) ? $data[$formattedDate] : collect(); // Ensure each day is present
//        }
//
//        return $weekDays;

        $startOfLastMonth = Carbon::now()->startOfMonth()->subMonth(); // First day of last month
        $endOfLastMonth = Carbon::now()->endOfMonth()->subMonth();    // Last day of last month

        // Fetch the sum of transactions grouped by week
        $data = DB::table('transactions')
            ->selectRaw('YEARWEEK(created_at, 1) as week, SUM(montant) as total')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->groupBy('week')
            ->pluck('total', 'week'); // Pluck into an associative array [week => total]

        // Format the weekly data for clarity
        $weeklySums = [];
        foreach ($data as $week => $sum) {
            $year = substr($week, 0, 4); // Extract year
            $weekNumber = substr($week, 4); // Extract week number
            $startOfWeek = Carbon::now()->setISODate($year, $weekNumber)->startOfWeek();
            $endOfWeek = Carbon::now()->setISODate($year, $weekNumber)->endOfWeek();

            // Ensure the week is within the last month range
            if ($startOfWeek->lte($endOfLastMonth) && $endOfWeek->gte($startOfLastMonth)) {
                $weeklySums[] = [
                    'week_start' => $startOfWeek->format('Y-m-d'),
                    'week_end' => $endOfWeek->format('Y-m-d'),
                    'total' => $sum,
                ];
            }
        }

        return [$weeklySums, $endOfLastMonth];
    }
    public function getWeeklyTransactionStats(Request $request){
//

        $previousMonday = Carbon::now()->startOfWeek()->subWeek();
        $previousSunday = Carbon::now()->endOfWeek()->subWeek();

        // Fetch the sum of transactions grouped by day
        $data = DB::table('transactions')
            ->selectRaw('DATE(created_at) as day, SUM(montant) as total')
            ->whereBetween('created_at', [$previousMonday, $previousSunday])
            ->groupBy('day')
            ->pluck('total', 'day'); // Pluck into an associative array [day => total]

        // Create an array with all days of the previous week
        $weekDays = [];
        for ($date = $previousMonday->copy(); $date->lte($previousSunday); $date->addDay()) {
            $formattedDate = $date->format('Y-m-d');
            $weekDays[$formattedDate] = $data->get($formattedDate, 0); // Default to 0 if no transactions
        }

        return $weekDays;
    }

    function generateID()
    {
        $datejour = Carbon::now()->format('ymd');
        $id = 'T'.rand(100,999).$datejour;
        $transaction= Transaction::where('id_transaction', $id)->first();
        if($transaction){
            return $this->generateID();
        }
        return $id;
    }
}
