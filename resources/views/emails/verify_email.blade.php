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
  <body style="flex-direction: column; align-items: center; justify-content: center; width: 100vh; height: 100vh;">
    <div style="height: 100px; background-color: #295B8D; display: flex; align-items: center; justify-content: center;">
    </div>
    <div class="text-center">
      <p>เรียนเจ้าของ E-mail: {{ $email }}</p>
      @if ($company_name_th)
        <p class="line-height-6">
          บริษัท {{ $company_name_th }} - {{ $company_name_en }} ได้เพิ่มคุณเป็นผู้ใช้งานในระบบ 
          SIT-Industry Collaboration Service System สำเร็จ กรุณาไปยัง
          <a href="{{ $url }}/login/verification?urlVerify= {{ $code_verify }}">
            {{ $url }}/login/verification?urlVerify= {{ $code_verify }}
          </a> เพื่อเข้าไปทำการตั้งรหัสผ่านใหม่ของคุณ
        </p>       
      @else
        <p class="line-height-6">
          คุณถูกเพิ่มให้เป็นผู้ใช้งานในระบบ SIT-Industry Collaboration Service System กรุณาไปยัง
          <a href="{{ $url }}/login/verification?urlVerify= {{ $code_verify }}">
            {{ $url }}/login/verification?urlVerify= {{ $code_verify }}
          </a> เพื่อเข้าไปทำการตั้งรหัสผ่านใหม่ของคุณ
        </p> 
      @endif
      <p>หากมีข้อสงสัยหรือสอบถามกรุณาติดต่อ 66 2470 9850 หรือ 
        <a href="https://www.facebook.com/SIT.Family" class="">SIT KMUTT</a>
        Facebook Fanpage
      </p>
      <hr style="margin-top: 6px; margin-bottom: 3px;"/>
      <p class="text-sm">© SIT-Industry Collaboration Service System</p>
    </div>
  </body>
</html>