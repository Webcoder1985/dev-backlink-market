<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Option;
use App\Models\User;
use App\Models\Review;

class ReviewController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {

  }

  public function index()
    {
        //
        $reviews = \DGvai\Review\Review::all();
        return view('admin.review',compact('reviews'));
    }
  public function reviewPage()
  {
    /*$product = Option::find(1);
    $user = auth()->user();

    //user makes new Review
    $product->makeReview($user,2,'Bad product!');
    exit;*/
    //ini_set('memory_limit', '-1');
    $option=Option::find(1);
    $reviews = \DGvai\Review\Review::active()->paginate(50);              // all reviews
    //var_dump($reviews);
    /*echo "<pre>";
    print_r($reviews);exit;*/
    $user = auth()->user();
    if (!$user){
      $user_review = 0;
    }
    else{
    $user = auth()->user();
    $user_review = Review::where([['user_id','=',$user->id]])->count();
    }

    return view('frontend.reviews', compact('option','reviews','user_review'));
  }

  public function reviewSave(Request $request)
  {
      $data = $request->all();
      $product = Option::find(1);
      $user = auth()->user();
      $reviews = Review::where([['user_id','=',$user->id]])->count();
      if($reviews == 0)
      {
        $review = $product->makeReview($user,$data['rating'],$data['review'],0);
        $review->makeInactive();
        return redirect()->back()->with('message', 'Your review Submitted successfully.');
      }
      else{
        return redirect()->back()->with('message', 'There is some problem to submit review.');
      }


  }

  public function declinereview($id, Request $request)
    {
        if(isset($id))
        {
            $review = Review::where([['id','=',$id]])->first();
            $review->active = 0;
            $review->update();
            return response()->json(['status'=>'success','message' => 'Request Decline'], 200);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }

    public function approvereview($id, Request $request)
    {
        if(isset($id))
        {
            $review = Review::where([['id','=',$id]])->first();
            $review->active = 1;
            $review->update();
            return response()->json(['status'=>'success','message' => 'Request Approved'], 200);

        }
        return response()->json(['status'=>'error', 'message' => 'Something went wrong!'], 422);
    }

    public function deletereview($id, Request $request)
    {
        if(isset($id))
        {
            $review = Review::where([['id','=',$id]])->first();
            $review->delete();
            return response()->json(['status'=>'success','message' => 'Request Deleted'], 200);
        }
        return response()->json(['errors' => ['msg' => 'Something went wrong!']], 422);
    }
}
