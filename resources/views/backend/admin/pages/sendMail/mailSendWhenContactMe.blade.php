<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You Email</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }
    .email-container {
      max-width: 600px;
      margin: 20px auto;
      background: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .header {
      background-color: #007bff;
      color: #ffffff;
      text-align: center;
      padding: 10px;
      border-radius: 8px 8px 0 0;
    }
    .header h1 {
      margin: 0;
      font-size: 24px;
    }
    .content {
      padding: 20px;
      color: #333333;
      line-height: 1.6;
    }
    .footer {
      text-align: center;
      color: #666666;
      font-size: 14px;
      margin-top: 20px;
    }
    .footer a {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="header">
      <h1>Thank You for Contacting Us</h1>
    </div>
    <div class="content">
      <p>Dear {{$data['name']}},</p>
      <p>Thank you for reaching out to us. We have received your message and will get back to you as soon as possible.</p>
      <p>In the meantime, feel free to explore our <a href="https://biwek.great-site.net/" target="_blank">website</a> for more information or updates.</p>
      <p>If your query is urgent, you can contact us directly at<a href="mailto:biwek1432@gmail.com">biwek1432@gmail.com</a>.</p>
      <p>Best regards,</p>
      <p><strong>The [Biwek Mehta] </strong></p>
    </div>
    <div class="footer">
      <p>© 2025 [Biwek Mehta]. All rights reserved.</p>
      <p> | <a href="https://biwek.great-site.net/" target="_blank">Visit Our Website</a></p>
    </div>
  </div>
</body>
</html>
