<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // Hiển thị trang checkout
    public function checkout(Request $request)
{
    $user = auth()->user();
    $cartIds = $request->input('cart_ids');

    if ($cartIds) {
        $cartItems = CartItem::whereIn('id', explode(',', $cartIds))->get();
    } else {
        $cartItems = $user->carts;
    }

    return view('checkout', compact('cartItems'));
}


    // Thanh toán VNPay
    public function vnpayPayment(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $userId = Auth::id();

        // Xử lý single product
        if ($request->has('single') && $request->single == 1) {
            $product = Product::find($request->product_id);
            if (!$product) {
                return redirect()->back()->with('error', 'Sản phẩm không tồn tại!');
            }

            $quantity = $request->quantity ?? 1;
            $total = $product->price * $quantity;

            $orderInfo = "Thanh_toan_san_pham_" . $product->id;
            $txnRef = "product_" . $product->id . "_" . time();
        } else {
            // Giỏ hàng
            $cartIds = $request->cart_ids ?? [];
            $carts = Cart::with('product')->whereIn('id', $cartIds)->get();
            if ($carts->isEmpty()) {
                return redirect()->back()->with('error', 'Giỏ hàng trống!');
            }

            $total = $carts->sum(fn($c) => $c->product->price * $c->quantity);
            $orderInfo = "Thanh_toan_don_hang_" . $userId;
            $txnRef = "cart_" . time();
        }

        $vnp_TmnCode = env('VNPAY_TMN_CODE');
        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnp_Url = env('VNPAY_URL');
        $vnp_ReturnUrl = env('VNPAY_RETURN_URL');

        if (empty($vnp_TmnCode) || empty($vnp_HashSecret) || empty($vnp_Url) || empty($vnp_ReturnUrl)) {
            Log::error('Cấu hình VNPay không hợp lệ', compact('vnp_TmnCode', 'vnp_HashSecret', 'vnp_Url', 'vnp_ReturnUrl'));
            return redirect()->back()->with('error', 'Cấu hình VNPay không hợp lệ!');
        }

        $orderInfo = preg_replace('/[^A-Za-z0-9_]/', '_', $orderInfo);

        // Tạo params VNPay
        $vnp_Params = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => intval($total * 100),
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_TxnRef"     => $txnRef,
            "vnp_OrderInfo"  => $orderInfo,
            "vnp_OrderType"  => "other",
            "vnp_ReturnUrl"  => $vnp_ReturnUrl,
            "vnp_IpAddr"     => $request->ip() === '::1' ? '127.0.0.1' : $request->ip(),
            "vnp_Locale"     => "vn"
        ];

        ksort($vnp_Params);

        // Tạo chuỗi hash với rawurlencode
        $hashData = [];
        foreach ($vnp_Params as $key => $value) {
            $hashData[] = $key . '=' . rawurlencode($value);
        }
        $hashString = implode('&', $hashData);
        $vnpSecureHash = hash_hmac('sha512', $hashString, $vnp_HashSecret);

        // Tạo URL redirect
        $query = [];
        foreach ($vnp_Params as $key => $value) {
            $query[] = "$key=" . urlencode($value);
        }
        $vnp_UrlFinal = $vnp_Url . '?' . implode('&', $query) . '&vnp_SecureHash=' . $vnpSecureHash;

        Log::info('Yêu cầu thanh toán VNPay', [
            'vnp_Params' => $vnp_Params,
            'hashString' => $hashString,
            'vnpSecureHash' => $vnpSecureHash,
            'vnp_UrlFinal' => $vnp_UrlFinal,
        ]);

        return redirect()->away($vnp_UrlFinal);
    }

    // Callback VNPay
    public function vnpayReturn(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_HashSecret = env('VNPAY_HASH_SECRET');
        $vnpSecureHash = $request->get('vnp_SecureHash');
        $vnpData = $request->except(['vnp_SecureHashType', 'vnp_SecureHash']);
        ksort($vnpData);

        // Tạo chuỗi hash kiểm tra với rawurlencode
        $hashData = [];
        foreach ($vnpData as $key => $value) {
            $hashData[] = $key . '=' . rawurlencode($value);
        }
        $hashString = implode('&', $hashData);
        $hashCheck = hash_hmac('sha512', $hashString, $vnp_HashSecret);

        Log::info('VNPay Callback Data', $request->all());
        Log::info('VNPay Hash Check', ['hashCheck' => $hashCheck, 'vnpSecureHash' => $vnpSecureHash]);

        if ($hashCheck === $vnpSecureHash && $request->get('vnp_ResponseCode') === "00") {
            Cart::where('user_id', Auth::id())->delete();
            return redirect('/')->with('success', 'Thanh toán VNPay thành công!');
        }

        Log::error('VNPay Payment Failed', [
            'ResponseCode' => $request->get('vnp_ResponseCode'),
            'HashMatch' => $hashCheck === $vnpSecureHash ? 'Yes' : 'No'
        ]);

        return redirect('/')->with('error', 'Thanh toán VNPay thất bại hoặc bị hủy!');
    }
}
