<?php

namespace App\Services;

class VNPayService
{
    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($data)
    {
        $vnp_TxnRef = $data['order_code'];
        $vnp_Amount = $data['total_amount'] * 100;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => config('vnpay.tmn_code'),
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => request()->ip(),
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => "Thanh toan don hang " . $vnp_TxnRef,
            "vnp_OrderType" => "other",
            "vnp_ReturnUrl" => config('vnpay.return_url'),
            "vnp_TxnRef" => $vnp_TxnRef
        );

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = config('vnpay.url') . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, config('vnpay.hash_secret'));
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

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

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
        unset($inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 0) {
                $hashData .= $key . "=" . $value;
            } else {
                $hashData .= '&' . $key . "=" . $value;
            }
            $i++;
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        \Log::info('VNPay Response Validation', [
            'received_data' => $request->all(),
            'processed_data' => $inputData,
            'hash_data' => $hashData,
            'received_hash' => $vnp_SecureHash,
            'calculated_hash' => $secureHash
        ]);

        return $vnp_SecureHash === $secureHash;
    }
}