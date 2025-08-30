<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MyGarage</title>
  <link rel="manifest" href="manifest.json">
  <meta name="theme-color" content="#667eea">
  <link rel="apple-touch-icon" href="icon.png">
  <style>
    body {
      min-height: 100vh;
      margin: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      transition: background 0.5s;
    }
    body.ran {
      background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
    }
    .center-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    .run-btn {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background: radial-gradient(circle at 60% 40%, #fff 60%, #e0e0e0 100%);
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border: none;
      outline: none;
      transition: box-shadow 0.3s, background 0.3s;
      position: relative;
      font-size: 3rem;
    }
    .run-btn:active {
      box-shadow: 0 4px 16px 0 rgba(31, 38, 135, 0.37);
    }
    .run-btn svg {
      width: 64px;
      height: 64px;
      fill: #667eea;
      transition: fill 0.3s;
    }
    .run-btn.ran svg {
      fill: #43cea2;
    }
    .run-btn.spin {
      animation: spin360 0.7s cubic-bezier(.68,-0.55,.27,1.55);
    }
    @keyframes spin360 {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .run-label {
      margin-top: 32px;
      font-size: 2rem;
      color: #fff;
      letter-spacing: 2px;
      text-shadow: 0 2px 8px rgba(0,0,0,0.2);
      transition: color 0.5s;
    }
    .run-label.ran {
      color: #43cea2;
    }
  </style>
</head>
<body>
  <div class="center-container" id="mainApp" style="display:none;">
    <button class="run-btn" id="runBtn" aria-label="Run">
      <svg viewBox="0 0 64 64">
        <polygon points="16,12 52,32 16,52" />
      </svg>
    </button>
    <div class="run-label" id="runLabel">Ready to open!</div>
  </div>
  <script>
    const runBtn = document.getElementById('runBtn');
    const runLabel = document.getElementById('runLabel');
    const mainApp = document.getElementById('mainApp');
    const PASSWORD_KEY = 'garage_password';
    const PASSWORD_TIMESTAMP_KEY = 'garage_password_timestamp';
    const PASSWORD_VALIDITY_DAYS = 30;
    const CORRECT_PASSWORD = 'yourpassword'; // <-- Set your website password here

    function getSavedPassword() {
      const pwd = localStorage.getItem(PASSWORD_KEY);
      const ts = localStorage.getItem(PASSWORD_TIMESTAMP_KEY);
      if (!pwd || !ts) return null;
      const now = Date.now();
      if (now - parseInt(ts, 10) > PASSWORD_VALIDITY_DAYS * 24 * 60 * 60 * 1000) {
        localStorage.removeItem(PASSWORD_KEY);
        localStorage.removeItem(PASSWORD_TIMESTAMP_KEY);
        return null;
      }
      return pwd;
    }

    function savePassword(pwd) {
      localStorage.setItem(PASSWORD_KEY, pwd);
      localStorage.setItem(PASSWORD_TIMESTAMP_KEY, Date.now().toString());
    }

    function clearPassword() {
      localStorage.removeItem(PASSWORD_KEY);
      localStorage.removeItem(PASSWORD_TIMESTAMP_KEY);
    }

    function requirePassword() {
      let pwd = getSavedPassword();
      if (pwd === CORRECT_PASSWORD) {
        mainApp.style.display = '';
        return;
      }
      while (true) {
        pwd = prompt('Enter website password:');
        if (pwd === null) {
          mainApp.style.display = 'none';
          return;
        }
        if (pwd === CORRECT_PASSWORD) {
          savePassword(pwd);
          mainApp.style.display = '';
          return;
        } else {
          alert('Incorrect password.');
        }
      }
    }

    // Call on page load
    requirePassword();

    async function sendPressRequest() {
      try {
        const response = await fetch('http://<yourdeviceip>:5000/press', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({})
        });
        if (response.ok) {
          return true;
        } else {
          throw new Error('Request failed');
        }
      } catch (e) {
        throw e;
      }
    }

runBtn.addEventListener('click', async () => {
  if (runBtn.classList.contains('spin')) return;
  runBtn.classList.add('spin');
  try {
    await sendPressRequest();
    setTimeout(() => {
      runBtn.classList.remove('spin');
      runBtn.classList.add('ran');
      document.body.classList.add('ran');
      runLabel.classList.add('ran');
      runLabel.textContent = 'Complete!';

      // 🔄 Reset back after 2 seconds
      setTimeout(() => {
        runBtn.classList.remove('ran');
        document.body.classList.remove('ran');
        runLabel.classList.remove('ran');
        runLabel.textContent = 'Ready to open!';
      }, 2000);

    }, 700);
  } catch (e) {
    runBtn.classList.remove('spin');
    alert('Request failed.');
  }
});

    // Register service worker for PWA
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('sw.js');
      });
    }
  </script>
</body>
</html>
