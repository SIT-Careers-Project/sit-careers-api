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
        <div style="width: 30%; height: 100%; display: flex; align-items: center; margin-right: 8px;" class="flex items-center h-full mr-8">
        <a href="https://www.sit.kmutt.ac.th/" target="_blank">
            <img src="{{ $url }}/image/sit-logo.png" />
        </a>
        </div>
        <a href="{{ $url }}" target="_blank">
            <img src="https://avatars.githubusercontent.com/u/70281896?s=96&v=4" />
        </a>
    </div>
    <div class="text-center">
      <p>เรียนเจ้าของ E-mail: {{ $email }}</p>
      @if ($company_name_th)
        <p class="line-height-6">
          {{ $company_name_th }} - {{ $company_name_en }} ได้เพิ่มคุณเป็นผู้ใช้งานในระบบ
          SIT Career Center สำเร็จ กรุณาไปยัง
          <a href="{{ $url }}/login/verification?urlVerify= {{ $code_verify }}">
            {{ $url }}/login/verification?urlVerify= {{ $code_verify }}
          </a> เพื่อเข้าไปทำการตั้งรหัสผ่านใหม่ของคุณ
        </p>
      @else
        <p class="line-height-6">
          คุณถูกเพิ่มให้เป็นผู้ใช้งานในระบบ SIT Career Center กรุณาไปยัง
          <a href="{{ $url }}/login/verification?urlVerify= {{ $code_verify }}">
            {{ $url }}/login/verification?urlVerify= {{ $code_verify }}
          </a> เพื่อเข้าไปทำการตั้งรหัสผ่านใหม่ของคุณ
        </p>
      @endif
      <p>ทั้งนี้ หากมีข้อสงสัยหรือต้องการติดต่อสอบถามข้อมูลเพิ่มเติม สามารถติดต่อได้ที่ คุณรุ่งโรจน์ ขวัญโกมล Tel: 66 2470 9889 E-mail:
        <a href="mailto:rungroj@sit.kmutt.ac.th">rungroj@sit.kmutt.ac.th</a>
      </p>
      <hr style="margin-top: 6px; margin-bottom: 3px;"/>
      <p class="text-sm">© SIT Career Center</p>
    </div>
  </body>
</html>
