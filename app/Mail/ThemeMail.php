<?php

namespace App\Mail;

use App\Models\EmailContent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ThemeMail extends Mailable
{
    use SerializesModels;
    public $data, $type, $template,$subject;

    public function __construct($data, $type,$subject=NULL)
    {
        $this->data = $data;
        $this->type = $type;
        $this->subject = $subject;
        $this->template = $this->getTemplateByType($type);
    }
    public function build()
    {
        return $this->view($this->template)
                    ->subject($this->subject)
                    ->with(['data' => $this->data]);
    }
    protected function getTemplateByType($type): string
    {
        return match ($type) {
            'reset' => 'emails.reset',
            'verify' => 'emails.verify',
            default => 'emails.default',
        };
    }

    protected function getDefaultSubject($type): string
    {
        return match ($type) {
            'reset' => 'Khôi phục mật khẩu',
            'verify' => 'Xác minh tài khoản',
            'order' => 'Đơn hàng của bạn',
            default => 'Thông báo từ hệ thống',
        };
    }
}
