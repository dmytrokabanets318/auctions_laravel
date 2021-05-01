<?php

namespace App\Http\Controllers;

use App\Auction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\User;

class AuctionsController extends Controller{

    public function index(Request $request){

        if($request->orderBy == 'undefined'){
            $request->orderBy = 'start desc';
        }

        $order = explode(" ", $request->orderBy);

        $auctions = Auction::where('end', null)
            ->orWhere('end', 0)
            ->orderBy($order[0], $order[1])
            ->get();


        if($order[0] == 'last_bid_price'){

            foreach ($auctions as $auction) {

                if($auction->last_bid_price == 0 || $auction->last_bid_price == null){
                    $auction['orderByPrice'] = $auction->min_price;
                }else{
                    $auction['orderByPrice'] = $auction->last_bid_price;
                }

            }

            if($order[1] == 'asc'){
                return $auctions->sortBy('orderByPrice')->values()->all();
            }else{
                return $auctions->sortByDesc('orderByPrice')->values()->all();
            }

        }

        return $auctions;

    }

    public function searchAuction(Request $request){

        if($request->orderBy == 'undefined'){
            $request->orderBy = 'start desc';
        }

        $order = explode(" ", $request->orderBy);

        $auctions = Auction::where('name', 'LIKE', '%'.$request->search.'%')
            ->orderBy($order[0], $order[1])
            ->get();

        if($order[0] == 'last_bid_price'){

            foreach ($auctions as $auction) {

                if($auction->last_bid_price == 0 || $auction->last_bid_price == null){
                    $auction['orderByPrice'] = $auction->min_price;
                }else{
                    $auction['orderByPrice'] = $auction->last_bid_price;
                }

            }

            if($order[1] == 'asc'){
                return $auctions->sortBy('orderByPrice')->values()->all();
            }else{
                return $auctions->sortByDesc('orderByPrice')->values()->all();
            }
          
        }

        if(count($auctions) == 0){
            return ["message" => "There are no auctions available with that name", "code" => 206];
        }

        return $auctions;

    }

    public function store(Request $request){

        $upload_path = public_path('upload');
        if($request->file != null){   
            $file_name = $request->file->getClientOriginalName();
            $generated_new_name = time() . '_' . $request->file->getClientOriginalExtension();
        }else{
            return ["message" => "You must insert an image for your auctions", "code" => 400];
        }

        $validator = $request->validate([

            'name' => 'required',
            'description' => 'required',
            'min_price' => 'required|digits_between:1,10000',
            'file' => 'required|file|image',

        ]);

        $auction = new Auction();

        $user = User::where('email', $request->email)->first();

        $auction->owner_id = $user->id;

        $auction->name = $request->name;
        $auction->description = $request->description;
        $auction->min_price = $request->min_price;

        $request->file->move($upload_path, $generated_new_name);

        $auction->photo_url = $generated_new_name;

        $auction->start = Carbon::now();

        $auction->save();

        return response()->json(['message' => 'You have successfully created the auction "'
            . $auction->name . '"']);

    }

    public function userAuctions(Request $request){

        $user = User::where('email', $request->email)->first();
        $close = true;

        switch ($request->set) {

            case 'active':

                $auctions = Auction::where('owner_id', $user->id)
                    ->where('end', null)
                    ->orderBy('created_at', 'ASC')
                    ->get();

                $message = "You dont have any active auctions yet";

            break;

            case 'closed':

                $auctions = Auction::where('owner_id', $user->id)->where('end', '<>', null)->get();
                $message = "You dont have closed auctions yet";

            break;

            case 'won':

                $auctions = Auction::where('last_bid_user_id', $user->id)->where('end', '<>', null)->get();
                $message = "You havent won any auctions yet";

            break;

            case 'bidded':

                $auctions = Auction::where('last_bid_user_id', $user->id)->where('end', null)->get();
                $message = "You dont have any bidded auctions";
                $close = false;

            break;
            
            default:
                    
                $auctions = Auction::where('owner_id', $user->id)->get();
                $message = "You dont have any auctions yet";

            break;
            
        }

        if(count($auctions) > 0){
            return ["auctions" => $auctions, "close" => $close];
        }else{
            return ["message" => $message,"code" => 206];
        }     

    }

    public function bidAuction(Request $request){

        $auction = Auction::find($request->id);
        $user = User::where('email', $request->email)->first();

        if($user->id == $auction->owner_id){
            return ["message" => "You cant bid your own auctions", "code" => 206];
        }

        if($auction->last_bid_price == null){
            if($request->bidPrice <= $auction->min_price){
                return ["message" => "You must bid greater than " . $auction->min_price . " €", "code" => 206];
            }
        }else{
            if($request->bidPrice <= $auction->last_bid_price){
                return ["message" => "You must bid greater than " . $auction->last_bid_price . " €", "code" => 206];
            }
        }

        $auction->last_bid_price = $request->bidPrice;
        $auction->last_bid_user_id = $user->id;

        $auction->save();

        return ["message" => "Auction ". $auction->name ." bidded with " . $request->bidPrice . " €", "code" => 200];

    }

    public function closeAuction(Request $request){

        $auction = Auction::findOrFail($request->id);

        if($auction->end != null){
            return ["message" => "Auction already closed", "code" => 400];
        }

        $user = User::where('email', $request->email)->first();

        if($auction->owner_id == $user->id){
            $auction->end = Carbon::now();    
        }else{
            return ["message" => "You cant close this auction", "code" => 203];
        }
        
        $auction->save();

        return ["message" => "Auction ended", "code" => 200];

    }

    
}
