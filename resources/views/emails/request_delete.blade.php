@component('mail::message')
  <p>เรียนคุณ {{ $user_req }}</p>
  <p>
    &nbsp;&nbsp;&nbsp;ขณะนี้ได้ดำเนินการส่งคำขอ<span style="font-weight: bold;">ลบข้อมูล</span>ของบริษัท {{ $company_name_th }} - {{ $company_name_en }}
    ตามที่คุณ {{ $user_req }} แจ้งในระบบ SIT Career Center ไปยังผู้ดูแลระบบเรียบร้อยแล้ว
  </p>
  <p>กรุณารอผู้ดูแลระบบดำเนินการลบข้อมูลของบริษัท</p>
@endcomponent