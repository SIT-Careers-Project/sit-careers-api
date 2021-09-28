@component('mail::message')
  <p>เรียนคุณ {{ $hello_name }}</p>
  <p class="line-height-6">
    &nbsp;&nbsp;&nbsp;ขณะนี้มีการส่งคำขอสมัครงาน {{ $company_name_th }} - {{ $company_name_en }} ในหน้าประกาศ {{ $announcement_title }}
    ผ่านระบบ SIT Career Center ทางบริษัทสามารถเข้าไปตรวจสอบข้อมูลการสมัครงานได้ที่ <a href="{{ $url }}/academic-industry/applications/history" target="_blank">SIT Career Center - Application History</a>
  </p>
@endcomponent