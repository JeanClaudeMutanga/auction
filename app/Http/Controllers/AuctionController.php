<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddAuction;
use App\Http\Requests\AddBid;
use App\Auction;
use App\Bid;
use Carbon\Carbon;
use Auth;
use Image;

class AuctionController extends Controller
{
    public function myAuctions(Request $request) {
        $user = Auth::user();
        $activeAuctions = Auth::user()->auctions->where('status', 'active');
        $expiredAuctions = Auth::user()->auctions->where('status', 'expired');
        $soldAuctions = Auth::user()->auctions->where('status', 'sold');

        return view('my_auctions', compact('activeAuctions', 'expiredAuctions', 'soldAuctions', 'user'));
    }

    public function newAuction(Request $request) {
        return view('new_auction');
    }

    public function finals(Request $request, Auction $auction, $auctionTitle = null) {
        if($auction->status == 'active') {
            $auction->status = 'sold';
            $auction->save();
        }
        $user = Auth::user();

        return view('finals', compact('user','auction', 'isInWatchlist', 'amountOfBids', 'amountOfBidsByCurrentUser'));
    }

    public function addAuction(AddAuction $request) {
        $optionalImagePath = null;
        $endDate = Carbon::createFromFormat('d/m/y', $request->end_date);
        $formattedEndDate = $endDate->format('Y-m-d');
        $imageQuality = 60;

        if($request->product_image->isValid()) {
            $productImagePath = 'storage/uploads/product_images/' . $request->product_image->hashName();
            Image::make($request->product_image)->save($productImagePath, $imageQuality);
        }
        else {
            return redirect()->back();
        }


        if($request->optional_image && $request->optional_image->isValid()) {
            $optionalImagePath = 'storage/uploads/optional_images/' . $request->optional_image->hashName();

            Image::make($request->optional_image)->save($optionalImagePath, $imageQuality);
        }

        Auction::create([
            'user_id' => Auth::id(),
            'cathegory' => $request->cathegory,
            'title' => $request->title,
            'year' => $request->year,
            'width' => $request->width,
            'height' => $request->height,
            'description' => $request->description,
            'condition' => $request->condition,
            'origin' => $request->origin,
            'company' => $request->company,
            'product_image_path' => $productImagePath,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'buyout_price' => $request->buyout_price,
            'end_date' => $formattedEndDate,
        ]);

        return redirect()->route('myAuctions');
    }

    public function auctionDetail(Request $request, Auction $auction, $auctionTitle = null) {
        $user = Auth::user();
        $isInWatchlist = $this->getWatchlistAuctionInfo($auction->id)['isInWatchlist'];
        $amountOfBids = $auction->bids->count();
        $amountOfBidsByCurrentUser = $auction->bids->where('user_id', Auth::id())->count();


        return view('auction_detail', compact('user','auction', 'isInWatchlist', 'amountOfBids', 'amountOfBidsByCurrentUser'));
    }

    public function productDetail(Request $request, Auction $auction, $auctionTitle = null) {
        $user = Auth::user();
        return view('finals', compact('user','auction', 'isInWatchlist', 'amountOfBids', 'amountOfBidsByCurrentUser'));
    }

    public function auctionBuyout(Request $request, Auction $auction, $auctionTitle = null) {
        $user = Auth::user();
        if($auction->status == 'active') {
            $auction->status = 'sold';
            $auction->save();
        }
        if($auction->customer_name == 'juan') {
            $auction->customer_name =$user->name;
            $auction->save();
        }
        return view('thank_you', compact('user','auction', 'isInWatchlist', 'amountOfBids', 'amountOfBidsByCurrentUser'));
    }

    public function auctionBuyoutfinals(Request $request, Auction $auction, $auctionTitle = null) {
        $user = Auth::user();
        return view('finals', compact('user','auction', 'isInWatchlist', 'amountOfBids', 'amountOfBidsByCurrentUser'));
    }

    public function addBid(AddBid $request, Auction $auction, $auctionTitle = null) {
        if($auction->status == 'active') {
            Bid::create([
                'user_id' => Auth::id(),
                'auction_id' => $auction->id,
                'price' => $request->bid_price,
            ]);        $products->save();
         $lastinsertedid=$request->bid_price;
        echo $lastinsertedid;
        }

        return redirect()->back();
    }

    public function thanks(Request $request) {
        return view('thank_you');
    }

}
