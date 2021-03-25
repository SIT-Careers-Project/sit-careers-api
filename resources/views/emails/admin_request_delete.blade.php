<html lang="th">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400&display=swap" rel="stylesheet">
  </head>
  <style>
    body {
      font-family: 'Prompt', sans-serif;
    }
    .text-center {
      text-align: center;
    }
    .line-height-6 {
      line-height: 1;
    }
  </style>
  <body style="flex-direction: column; align-items: center; justify-content: center;">
    <div style="height: 100px; background-color: #295B8D; display: flex; align-items: center; justify-content: center;">
      <div style="width: 5%; height: 100%; display: flex; align-items: center; margin-right: 8px;" class="h-full flex items-center mr-8">
        <a href="https://www.sit.kmutt.ac.th/" target="_blank">
          <img src="https://dev.sit-industry.systems/image/sit-logo.png" />
        </a>
      </div>
      <a href="{{ $url }}" target="_blank">
        <p style="color: white;">Logo</p>
      </a>
    </div>
    <div class="text-center">
      <p>สวัสดีคุณ {{ $hello_name }}</p>
      <p class="line-height-6">
        แจ้งการส่งคำขอลบข้อมูลของบริษัท {{ $company_name_th }} - {{ $company_name_en }}
        สามารถตรวจสอบข้อมูลและทำการลบข้อมูลได้ที่ <a href="{{ $url }}/company/update/{{ $company_id }}">คลิก</a>
      </p>
      <hr style="margin-top: 6px; margin-bottom: 3px;"/>
      <p class="text-sm">© SIT-Industry Collaboration Service System</p>
    </div>
  </body>
</html>