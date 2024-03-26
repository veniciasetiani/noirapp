<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\cart;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\permission;

use Illuminate\Support\Str;
use App\Jobs\ReduceTimerJob;
use Illuminate\Http\Request;
use App\Models\AvailableTime;
use App\Models\OrderValidation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(User $user){

        $scheduleCount = Schedule::where('buyer_id', auth()->user()->id)->count();
        $availableTimes = AvailableTime::where('user_id', $user->id)->get();
        $availableDays = $availableTimes->pluck('day')->unique()->values()->toArray();
        $schedules = Schedule::where('buyer_id',auth()->user()->id)->get();

        $schedule = Schedule::where('buyer_id', auth()->user()->id)->first();

        // dd($scheduleCount);
        return view('order.index',compact('scheduleCount','availableTimes','availableDays','schedules','schedule'), [
            "title" => "order",
            'active' => 'order',
            'user' => $user,
            'image' => Permission::where('user_id', $user->id)->orderBy('created_at', 'desc')->where('statcode','APV')->first()
            // Post::find($id)

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $user_id = $request->user_id;
    $buyer_id = auth()->user()->id;

    // Check if the product already exists in the cart for the current user
    $existingCartItem = Cart::where('user_id', $user_id)
        ->where('buyer_id', $buyer_id)
        ->first();

    if ($existingCartItem) {
        // If the product exists in the cart, return a message
        return redirect('/game')->with('error', 'Item already in Cart!');
    }

    // Retrieve the schedule_id from the request
    $schedule_id = $request->input('schedule_id');

    // Assuming you have a valid schedule_id, proceed to create a new cart item
    if ($schedule_id) {
        $expiryTime = now()->addMinutes(5)->toDateTimeString();

        $newCartItem = Cart::create([
            'user_id' => $user_id,
            'price' => $request->price,
            'buyer_id' => $buyer_id,
            'schedule_id' => $schedule_id,
            'timer_expiry' => $expiryTime,
        ]);

        // Redirect or perform additional actions as needed
        return redirect('/game')->with('success', 'Added to Cart! Continue Shopping.');
    } else {
        // Handle the case where schedule_id is not provided
        return redirect()->back()->with('error', 'Schedule ID is required to add to cart.');
    }
}

public function saveScheduleAndCart(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required',
        'schedule' => 'required|array',
        'schedule.date' => 'required|date',
        'schedule.time' => 'required'
    ]);

    $user_id = $validated['user_id'];
    $buyer_id = auth()->user()->id;
    $date = $validated['schedule']['date'];
    $time = $validated['schedule']['time'];
    $existingSchedule = Schedule::where('user_id',$user_id)->where('date', $date)
    ->where('start_time', $time)
    ->where('is_active', true)
    ->exists();

    $existingSchedule2 = Schedule::where('buyer_id',$buyer_id)->where('date', $date)
    ->where('start_time', $time)
    ->where('is_active', true)
    ->exists();
    if ($existingSchedule2) {
        return redirect()->back()->with('error', 'You already have a schedule with this date and time.');
    }
    if ($existingSchedule) {
        return redirect()->back()->with('error', 'The selected date and time are not available.');
    }
    try {
        // Save Schedule
        $schedule = new Schedule([
            'user_id' => $user_id,
            'buyer_id' => $buyer_id,
            'date' => $date,
            'start_time' => $time,
            'end_time' => date('H:i', strtotime($time) + 7200),
        ]);

        $schedule->save();

        // Save Cart
        $expiryTime = now()->addMinutes(5)->toDateTimeString();
        $newCartItem = Cart::create([
            'user_id' => $user_id,
            'price' => $request->price,
            'buyer_id' => $buyer_id,
            'schedule_id' => $schedule->id,
            'timer_expiry' => $expiryTime,
        ]);

        return response()->json(['ok' => true]);

    } catch (\Exception $e) {
        Log::error('Error saving schedule and cart: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to save schedule and cart. Please try again.');
    }
}


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Calculate the new subtotal
        $cartItem = cart::findOrFail($id);
        $quantity = $validated['quantity'];
        $subtotal = $cartItem->price * $quantity;

        // Update the quantity and subtotal in the database
        $cartItem->quantity = $quantity;
        $cartItem->subtotal = $subtotal;
        $cartItem->save();

        return response()->json(['message' => 'Quantity updated successfully']);
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function GetCartByUserId(User $user)
    {
        // Retrieve the cart items for the user
        $cart = $user->cart()->with('seller', 'schedule')->get();
        $timerExpiryValues = $cart->pluck('timer_expiry');

        $scheduleCount = Schedule::where('buyer_id', $user->id)->count();
        $cartItems = Cart::where('user_id', $user->id)->get();
        $timerExpiryValues = $cartItems->pluck('timer_expiry');
    // Periksa dan hapus cart jika timer telah habis
    // foreach ($cartItems as $cartItem) {
    //     if ($cartItem->timer_expiry > 0) {
    //         // Kurangi timer_expiry sebanyak 1 detik
    //         $cartItem->timer_expiry -= 1;
    //         $cartItem->save();
    //     } else {
    //         // Jika timer_expiry habis, hapus item
    //         $cartItem->delete(); // Hapus item jika timer telah habis
    //     }
    // }

        return view('order.show',compact('scheduleCount'), [
            "title" => "order show",
            'active' => 'order show',
            'cart' => $cart,
            'cartItems' => $cartItems,
            '$timerExpiryValues' => $timerExpiryValues
             // Pass the cart items to the view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);

    // Retrieve the associated schedule
    $schedule = $cartItem->schedule;
    $schedule->is_active = false;
    $schedule->save();

    // Delete the cart item
    $cartItem->delete();

    // Check if the schedule exists and delete it

        return redirect()->back()->with('success','Item Deleted!');
    }
    public function showOrderPage(Request $request)
{
    // $selectedItems = $request->input('selectedItems'); // Ambil item yang dicek dari request
    // $cart = []; // Inisialisasi array untuk menampung item-item yang dicek
    // $totalPrice = 0; // Inisialisasi total harga

    // // Dapatkan item dari session dan pilih hanya item yang dicek
    // foreach ($request->session()->get('cart') as $item) {
    //     if (in_array($item->id, $selectedItems)) {
    //         $cart[] = $item;
    //         $totalPrice += $item->quantity * $item->price;
    //     }
    // }

    // $user = Auth::user();
    // $points = $user->points;

    // return view('order.orderpage', compact('points', 'totalPrice'), ['active' => 'orderPage']);
}

    public function confirmOrder(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        $cartItems = Cart::whereIn('id', $selectedItems)->get();

        return view('order.orderpage', compact('cartItems'));
    }

    public function placeOrder(Request $request)
    {
        // dd($request->all());
        $selectedItems = $request->input('selectedItems');
        // $selectedSchedule = $request->input('selectedSchedule');

        // dd($selectedItems);

        // Validasi apakah ada item yang dipilih
        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Please select at least one item before placing an order.');
        }
        $cartItems = cart::whereIn('id', $selectedItems)->get();
        // dd($cartItems);

        try {
            $totalPrice = 0; // Inisialisasi total harga

        // Hitung total harga
        foreach ($cartItems as $cartItem) {
            $totalPrice += $cartItem->price;
        }

        // Ambil user yang sedang login
        $user = User::find(Auth::user()->id);

        // Periksa apakah poin pengguna mencukupi
        if ($user->points >= $totalPrice) {
            // Kurangi poin pengguna
            $user->points -= $totalPrice;
            $user->save();

            // Tambahkan pesanan ke database
            foreach ($cartItems as $cartItem) {
                $expiryTime = now()->addMinutes(720)->toDateTimeString();

                $orderValidation = new OrderValidation;
                $orderValidation->buyer_id = $user->id;
                $orderValidation->seller_id = $cartItem->user_id;
                $orderValidation->schedule_id = $cartItem->schedule_id; // Save the selected schedule ID
                $orderValidation->price = $cartItem->price;
                $orderValidation->status = 'REQ';
                $orderValidation->timer_expiry = $expiryTime;
                $orderValidation->save();
            }

            // Hapus item dari Cart
            cart::whereIn('id', $selectedItems)->delete();

            return redirect()->back()->with('success','Place Order Successful');
        } else {
            return redirect()->back()->with('error', 'Insufficient points. Please top up first.');
        }
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['trace' => $e->getTrace()]);

        }
    }
    public function getCartData()
    {
        $cartItems = cart::where('user_id', auth()->user()->id)->get();

        return response()->json(['cartItems' => $cartItems]);
    }
    public function processOrder(Request $request)
    {
        $selectedItems = $request->input('selectedItems');
        // dd($selectedItems);

        // Validasi apakah ada item yang dipilih
        if (empty($selectedItems)) {
            return redirect()->back()->with('error', 'Please select at least one item before placing an order.');
        }
        $cartItems = cart::whereIn('id', $selectedItems)->get();
        // dd($cartItems);

        try {
            // Ambil data dari Cart
            // dd($cartItems);

            foreach ($cartItems as $cartItem) {
                $orderValidation = new OrderValidation;
                $orderValidation->buyer_id = auth()->user()->id; // Pembeli adalah user yang sedang login
                $orderValidation->seller_id = $cartItem->user_id; // Penjual adalah pemilik item di cart
                $orderValidation->price = $cartItem->price;
                $orderValidation->quantity = $cartItem->quantity;
                $orderValidation->total_price = $cartItem->price * $cartItem->quantity;
                $orderValidation->status = 'REQ'; // Status awal adalah request
                $orderValidation->save();
            }
            // dd($orderValidation);
            // Hapus item dari Cart
            // cart::whereIn('id', $itemIds)->delete();

            // Redirect atau kirim respons sesuai kebutuhan aplikasi Anda
            return redirect()->route('home')->with('error', 'An error occurred while processing the order.');
        } catch (\Exception $e) {
            Log::error($e->getMessage(), ['trace' => $e->getTrace()]);

        }
    }

    public function processOrderValidation(Request $request)
{

    $selectedItems = $request->input('selectedItems');
    dd($selectedItems);
    if (empty($selectedItems)) {
        return response()->json(['error' => 'Please select at least one item before placing an order.'], 422);
    }

    try {
        // Proses validasi pesanan dan simpan ke database
        foreach ($selectedItems as $itemId) {
            // Ambil item dari Cart
            $cartItem = Cart::find($itemId);

            if ($cartItem) {
                $orderValidation = new OrderValidation;
                $orderValidation->buyer_id = auth()->user()->id;
                $orderValidation->seller_id = $cartItem->user_id;
                $orderValidation->price = $cartItem->price;
                $orderValidation->quantity = $cartItem->quantity;
                $orderValidation->total_price = $cartItem->price * $cartItem->quantity;
                $orderValidation->status = 'REQ';
                $orderValidation->save();

                // Hapus item dari Cart
                $cartItem->delete();
            }
        }

        return response()->json(['success' => 'Order placed successfully.'], 200);
    } catch (\Exception $e) {
        Log::error($e->getMessage(), ['trace' => $e->getTrace()]);
        return response()->json(['error' => 'An error occurred while processing your order. Please try again later.'], 500);
    }
}

    public function validateOrder(Request $request, $id)
    {
        try {
            $orderValidation = OrderValidation::findOrFail($id);

            // Check if the user has enough points
            $amount = $orderValidation->total_price;

            if ($this->hasEnoughPoints($orderValidation->buyer, $amount)) {
                $orderValidation->status = 'APV';
                $orderValidation->save();
                $slug = Str::slug('TRX'.str::random(3).random_int(1,9999));
                // while($data){
                //     $slug = Str::slug('TRX'.str::random(3).random_int(1,9999));
                // }
                // Masukkan ke transaksi
                Transaction::create([
                    'slug' => $slug,
                    'buyer_id' => $orderValidation->buyer_id,
                    'seller_id' => $orderValidation->seller_id,
                    'price' => $orderValidation->price,
                    'quantity' => $orderValidation->quantity,
                    'total_price' => $orderValidation->total_price,
                    'status' => 'ON_GOING'
                ]);

                // Potong saldo
                $this->deductPoints($orderValidation->buyer, $amount);

                // Kirim notifikasi ke pengguna
            } else {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi.');
            }

            return redirect()->back()->with('success', 'Order validation status updated.');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function orderRequest()
    {
        // Dapatkan daftar OrderValidation yang membutuhkan validasi
        $sellerId = auth()->user()->id;
        $orderValidations = OrderValidation::where('status', 'REQ')
                                          ->where('seller_id', $sellerId)
                                          ->get();

        return view('order.orderrequest', compact('orderValidations'),['active' => 'OrderReq']);
    }

    public function acceptOrder($id)
{
    $orderValidation = OrderValidation::findOrFail($id);
    $orderValidation->status = 'APV';
    $orderValidation->save();
    // dd($schedule);

    $slug = Str::slug('TRX'.str::random(3).random_int(1,9999));

    $data = Transaction::where('slug', $slug);
    // while($data){
    //     $slug = Str::slug('TRX'.str::random(3).random_int(1,9999));
    // }
    Transaction::create([
        'slug' => $slug,
        'buyer_id' => $orderValidation->buyer->id,
        'seller_id' => $orderValidation->seller_id,
        'schedule_id' => $orderValidation->schedule_id,
        'price' => $orderValidation->price,
        'status' => 'ON_GOING'
    ]);
    return redirect()->route('order.request')->with('success', 'Order accepted.');
}

public function rejectOrder($id)
{

    $orderValidation = OrderValidation::findOrFail($id);
    $user = User::find($orderValidation->buyer_id);


    // Retrieve the associated schedule
    $schedule = $orderValidation->schedule;


    $schedule->is_active = false;
    $schedule->save();

    if (!$user) {
        return redirect()->route('order.request')->with('error', 'User not found.');
    }

    $user->points += $orderValidation->price;
    $user->save();

    $orderValidation->status = 'RJC';
    $orderValidation->save();

    return redirect()->route('order.request')->with('success', 'Order rejected.');
}

    private function hasEnoughPoints($user, $amount) {
        return $user->points >= $amount;
    }
    private function deductPoints($user, $amount) {
        return $user->points >= $amount;
    }
}
