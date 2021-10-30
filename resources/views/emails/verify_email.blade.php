@component('mail::message')
 <p>เรียนเจ้าของอีเมล: {{ $email }}</p>
@if ($company_name_th)
  <p class="line-height-6">
    {{ $company_name_th }} - {{ $company_name_en }} ได้เพิ่มคุณเป็นผู้ใช้งานในระบบ 
    SIT Career Center สำเร็จ กรุณากด 
    <a target="_blank" href="{{ $url }}/login/verification?urlVerify={{ $code_verify }}">
      SIT Career Center - Verify Email
    </a>
    เพื่อตั้งรหัสผ่านและทำการยืนยันอีเมลของคุณภายในเวลา 7 วัน
  </p>
@else
  <p class="line-height-6">
    คุณถูกเพิ่มให้เป็นผู้ใช้งานในระบบ SIT Career Center คุณสามารถตั้งรหัสผ่านใหม่ของคุณได้ที่ลิงก์นี้ 
    <a target="_blank" href="{{ $url }}/login/verification?urlVerify={{ $code_verify }}">
      SIT Career Center - Verify Email
    </a>
    และเพื่อทำการยืนยันอีเมลของคุณภายในเวลา 7 วัน
  </p>
@endif
@endcomponent