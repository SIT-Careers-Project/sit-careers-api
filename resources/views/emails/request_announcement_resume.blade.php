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
      <p>เรียนคุณ {{ $hello_name }}</p>
      <p class="line-height-6">
        ขณะนี้มีการส่งคำขอสมัครงาน {{ $company_name_th }} - {{ $company_name_en }} ในหน้าประกาศ {{ $announcement_title }}
        ผ่านระบบ SIT Career Center ทางบริษัทสามารถเข้าไปตรวจสอบข้อมูลการสมัครงานได้ที่ <a href="{{ $url }}/academic-industry/applications/history">คลิก</a>
      </p>
      <p>
        ทั้งนี้ หากคุณ {{ $hello_name }} มีข้อสงสัยหรือต้องการติดต่อสอบถามข้อมูลเพิ่มเติม สามารถติดต่อได้ที่ คุณรุ่งโรจน์ ขวัญโกมล
        Tel: 66 2470 9887  Email: rungroj@sit.kmutt.ac.th
      </p>
      <hr style="margin-top: 6px; margin-bottom: 3px;"/>
      <p class="text-sm">© SIT Career Center</p>
    </div>
  </body>
</html>
