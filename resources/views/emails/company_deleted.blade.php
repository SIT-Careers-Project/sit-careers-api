@component('mail::message')
  <p>เรียนคุณ {{ $hello_name }}</p>
  <p class="line-height-6">
    &nbsp;&nbsp;&nbsp;ขณะนี้ผู้ดูแลระบบได้ดำเนินการลบข้อมูลของบริษัท {{ $company_name_th }} - {{ $company_name_en }}
    ตามที่คุณ {{ $hello_name }} แจ้งในระบบ SIT Career Center  เรียบร้อยแล้ว
  </p>
@endcomponent
