@component('mail::message')
  <p>เรียน ผู้ดูแลระบบ</p>
  <p class="line-height-6">
    &nbsp;&nbsp;&nbsp;{{ $company_name_th }} - {{ $company_name_en }} ได้ทำการส่งคำขอเพื่อลบข้อมูลของทางบริษัทในระบบ SIT Career Center
    โดยทางผู้ดูแลระบบสามารถตรวจสอบข้อมูลและดำเนินการลบข้อมูลได้ที่ <a href="{{ $url }}/company/update/{{ $company_id }}">คลิก</a>
  </p>
@endcomponent

