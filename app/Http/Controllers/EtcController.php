<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auction;
use Auth;

class EtcController extends Controller
{
    public function home(Request $request) {
        $activeAuctions = Auction::where('status', 'active')->get();
        return view('home', compact('user','activeAuctions'));
    }

    public function product(Request $request) {
        $paginate = 12;
        $endingSoonest = Auction::where('status', 'active')->orderBy('end_date')->paginate($paginate);
        $endingLatest = Auction::where('status', 'active')->orderByDesc('end_date')->paginate($paginate);
        $new = Auction::where('status', 'active')->orderByDesc('created_at')->paginate($paginate);
        $popular = Auction::withCount('bids')->where('status', 'active')->orderByDesc('bids_count')->paginate($paginate);

        $orderedAuctions = [$endingSoonest, $endingLatest, $new, $popular];
        $orderedAuctionTypes = ['ending_soonest', 'ending_latest', 'new', 'popular'];

        return view('product', compact('request', 'orderedAuctions', 'orderedAuctionTypes'));
    }

    public function profile(Request $request,Auction $auction) {
        $user = Auth::user();
        $purchaseAuctions = Auction::where('customer_name', $user->name)->get();
        $activeAuctions = Auction::where('status', 'active')->get();

        return view('profile', compact('user', 'activeAuctions', 'purchaseAuctions', 'auction'));
    }
    public function homepage(Request $request) {
        return view('firstpage');
    }

    public function iSearch(Request $request) {
        $query = $request->input('query');
        $searchResults = Auction::where([['title', 'like', "%{$query}%"], ['status', 'active']])->paginate(8);

        return view('isearch', compact('searchResults'));
    }

    public function redirectHome(Request $request) {
        return redirect()->route('home');
    }
    
}
