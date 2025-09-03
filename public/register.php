<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../public/header.php';
$errors = flash_errors();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create account – Job Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    body {margin:0;font-family:"Segoe UI",Arial,sans-serif;background:#fff;}
    .container {display:flex;justify-content:center;align-items:flex-start;padding:40px 20px;min-height:100vh;}
    .card-wrapper {display:flex;width:100%;max-width:1100px;background:#fff;border-radius:14px;box-shadow:0 2px 16px rgba(0,0,0,0.08);overflow:hidden;}
    .left-section {flex:0 0 40%;padding:60px 40px;background:#fafafa;text-align:center;}
    .left-section img {width:200px;margin-bottom:20px;}
    .left-section h2 {font-size:18px;font-weight:600;color:#333;margin-bottom:20px;}
    .left-section ul {list-style:none;padding:0;margin:0;}
    .left-section li {margin-bottom:15px;font-size:15px;display:flex;align-items:center;color:#333;}
    .left-section li i {color:#28a745;margin-right:10px;font-size:16px;}
    .right-section {flex:0 0 60%;padding:60px 40px;}
    .card header h1 {font-size:22px;margin:0 0 8px;font-weight:600;color:#171616;}
    form .row {margin-bottom:18px;}
    label {font-weight:500;display:block;margin-bottom:6px;font-size:14px;}
    input, select {width:70%;padding:12px 14px;border:1px solid #d9d9d9;border-radius:8px;font-size:14px;outline:none;transition:border .2s ease;}
    input:focus, select:focus {border:1px solid #4a90e2;box-shadow:0 0 0 2px rgba(74,144,226,0.2);}
    .btn {background:#457eff;color:#fff;padding:14px;border:none;border-radius:8px;cursor:pointer;font-size:15px;font-weight:600;width:75%;margin-top:10px;transition:background .2s ease;}
    .btn:hover {background:#2f64d4;}
    .error {background:#ffe0e0;color:#a00;padding:10px;margin-bottom:15px;border-radius:8px;font-size:14px;}
    .footer {margin-top:20px;margin-right:130px;font-size:12px;color:#555;text-align:center;}
    header {display:flex;justify-content:space-between;align-items:center;padding:5px 0;background:#fff;border-bottom:1px solid #ddd;}
    .hidden {display:none;}
  </style>
</head>
<body>
<div class="container">
  <div class="card-wrapper">
    <div class="left-section">
      <img src="assets/image/white-boy.png" alt="Job search">
      <h2>On registering, you can</h2>
      <ul>
        <li><i class="fa fa-check-circle"></i> Build your profile and let recruiters find you</li>
        <li><i class="fa fa-check-circle"></i> Get job postings delivered right to your email</li>
        <li><i class="fa fa-check-circle"></i> Find a job and grow your career</li>
      </ul>
    </div>
    <div class="right-section">
      <div class="card">
        <header><h1>Create your account</h1></header>
        <?php if ($errors): ?>
          <div class="error"><ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form action="process_register.php" method="post" enctype="multipart/form-data" novalidate>
          <input type="hidden" name="csrf" value="<?= csrf_token(); ?>" />
          <div class="row">
            <label>Full Name <span style="color:red">*</span></label>
            <input type="text" name="full_name" value="<?= old('full_name') ?>" required />
          </div>
          <div class="row">
            <label>Email ID <span style="color:red">*</span></label>
            <input type="email" name="email" value="<?= old('email') ?>" required />
          </div>
          <div class="row">
            <label>Password <span style="color:red">*</span></label>
            <input type="password" name="password" minlength="8" required />
          </div>
          <div class="row">
            <label>Mobile Number</label>
            <input type="tel" name="phone" value="<?= old('phone') ?>" placeholder="10 digits" />
          </div>
          <div class="row">
            <label>Work Status</label><br>
            <input type="radio" name="work_status" value="fresher" onclick="toggleExpFields()"> Fresher
            <input type="radio" name="work_status" value="experienced" onclick="toggleExpFields()"> Experienced
          </div>
          <div id="expFields" class="hidden">
            <div class="row"><label>Total Experience (months)</label><input type="number" name="total_exp_months" min="0" /></div>
            <div class="row"><label>Current City</label><input type="text" name="current_city" /></div>
            <div class="row"><label>Current Company</label><input type="text" name="current_company" /></div>
            <div class="row"><label>Current Role</label><input type="text" name="current_role" /></div>
            <div class="row"><label>Current CTC (LPA)</label><input type="number" step="0.01" name="current_ctc_lpa" /></div>
            <div class="row"><label>Notice Period (days)</label><input type="number" name="notice_period_days" /></div>
          </div>
          <div class="row">
            <label>Upload Resume (PDF/DOC/DOCX, ≤ 2 MB)</label>
            <input type="file" name="resume" accept=".pdf,.doc,.docx" />
          </div>
          <div class="row"><button class="btn" type="submit">Create account</button></div>
          <div class="footer">By continuing, you agree to our <a href="#">Terms</a> & <a href="#">Privacy Policy</a></div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
function toggleExpFields() {
  const expFields = document.getElementById("expFields");
  const expRadio = document.querySelector("input[name='work_status'][value='experienced']");
  const cityInput = expFields.querySelector("input[name='current_city']");
  if (expRadio.checked) {
    expFields.classList.remove("hidden");
    cityInput.setAttribute("required", "required");
  } else {
    expFields.classList.add("hidden");
    cityInput.removeAttribute("required");
  }
}
</script>
</body>
</html>
<?php include "footer.php" ?>
