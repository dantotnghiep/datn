<?php

namespace App\Services;

class VNPayService
{
    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($data)
    {
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = config('vnpay.url');
        $vnp_ReturnUrl = url(config('vnpay.return_url'));

        $vnp_TxnRef = $data['order_code']; // Mã đơn hàng
        $vnp_OrderInfo = $data['order_desc'];
        $vnp_Amount = $data['total_amount'] * 100;
        $vnp_IpAddr = request()->ip();
        $vnp_CreateDate = date('YmdHis');

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => (int)$vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => date('YmdHis', strtotime('+15 minutes', strtotime($vnp_CreateDate)))
        ];

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($value == "" || str_contains($key, 'vnp_SecureHash')) {
                continue;
            }
            $encodedValue = urlencode($value);
            if ($query != "") {
                $query .= '&';
                $hashdata .= '&';
            }
            $query .= urlencode($key) . "=" . $encodedValue;
            $hashdata .= $key . "=" . $encodedValue;
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= "?" . $query;
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

        \Log::info('VNPay Request Details', [
            'input_data' => $inputData,
            'hash_data' => $hashdata,
            'secure_hash' => $vnpSecureHash,
            'final_url' => $vnp_Url,
            'hash_secret' => $vnp_HashSecret
        ]);

        return $vnp_Url;
    }

    /**
     * Kiểm tra tính hợp lệ của response từ VNPay
     */
    public function validateResponse($request)
    {
        $vnp_HashSecret = config('vnpay.hash_secret');
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($hashData != "") {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . "=" . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        \Log::info('VNPay Response Validation', [
            'received_data' => $request->all(),
            'processed_data' => $inputData,
            'hash_data' => $hashData,
            'received_hash' => $request->vnp_SecureHash,
            'calculated_hash' => $secureHash
        ]);

        return $request->vnp_SecureHash === $secureHash;
    }
}