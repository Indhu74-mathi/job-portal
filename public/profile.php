<?php
session_start();
require_once __DIR__ . "/../public/header.php";
require_once __DIR__ . "/../config/db.php";

$user_id = 1; // replace with $_SESSION['user_id'] for actual login

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch Education, Skills, Projects, Accomplishments, Career, Personal
$education = $pdo->prepare("SELECT * FROM education WHERE user_id=? LIMIT 1");
$education->execute([$user_id]);
$education = $education->fetch();

$skills = $pdo->prepare("SELECT * FROM skills WHERE user_id=?");
$skills->execute([$user_id]);
$skills = $skills->fetchAll();

$projects = $pdo->prepare("SELECT * FROM projects WHERE user_id=?");
$projects->execute([$user_id]);
$projects = $projects->fetchAll();

$accomplishments = $pdo->prepare("SELECT * FROM accomplishments WHERE user_id=? LIMIT 1");
$accomplishments->execute([$user_id]);
$accomplishments = $accomplishments->fetch();

$career = $pdo->prepare("SELECT * FROM career_profile WHERE user_id=? LIMIT 1");
$career->execute([$user_id]);
$career = $career->fetch();

$personal = $pdo->prepare("SELECT * FROM personal_details WHERE user_id=? LIMIT 1");
$personal->execute([$user_id]);
$personal = $personal->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body { background: #f4f5f7; }
    .sidebar { background: #fff; padding: 20px; border-radius: 8px; }
    .sidebar a { display: block; margin-bottom: 12px; text-decoration: none; color: #007bff; cursor: pointer; }
    .card { margin-bottom: 20px; border-radius: 10px; }
    .profile-pic { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; }
    .cancel-icon { cursor:pointer; color:red; font-weight:bold; margin-left:8px; }
  </style>
</head>
<body>
<div class="container mt-4">
  <div class="row">

    <!-- Sidebar -->
    <div class="col-md-3">
      <div class="sidebar shadow-sm">
        <h5>Manage Profile</h5>
        <a data-bs-toggle="modal" data-bs-target="#educationModal"><?= $education ? '✏️ Edit Education' : '+ Add Education' ?></a>
        <a data-bs-toggle="modal" data-bs-target="#skillsModal">+ Add Skills</a>
        <a data-bs-toggle="modal" data-bs-target="#projectsModal">+ Add Projects</a>
        <a data-bs-toggle="modal" data-bs-target="#accomplishmentsModal"><?= $accomplishments ? '✏️ Edit Accomplishments' : '+ Add Accomplishments' ?></a>
        <a data-bs-toggle="modal" data-bs-target="#careerModal"><?= $career ? '✏️ Edit Career Profile' : '+ Add Career Profile' ?></a>
        <a data-bs-toggle="modal" data-bs-target="#personalModal"><?= $personal ? '✏️ Edit Personal Details' : '+ Add Personal Details' ?></a>
      </div>
    </div>

    <!-- Profile Content -->
    <div class="col-md-9">

      <!-- Profile Header -->
      <div class="card p-4 shadow-sm">
        <div class="d-flex align-items-center">
          <form id="uploadForm" enctype="multipart/form-data" method="post" action="upload_photo.php">
            <label for="profilePicUpload" style="cursor:pointer;">
              <img src="<?= $user['profile_photo'] ?: 'https://via.placeholder.com/120' ?>" class="profile-pic me-3">
            </label>
            <input type="file" id="profilePicUpload" name="profile_photo" style="display:none" onchange="document.getElementById('uploadForm').submit();">
          </form>
          <div>
            <h4><?= htmlspecialchars($user['full_name']) ?></h4>
            <p class="mb-1"><?= htmlspecialchars($user['email']) ?> | <?= htmlspecialchars($user['phone']) ?></p>
            <p class="text-muted"><?= htmlspecialchars($user['current_role'] ?? 'Not Updated') ?> @ <?= htmlspecialchars($user['current_company'] ?? 'N/A') ?></p>
          </div>
        </div>
      </div>

      <!-- Education -->
      <div class="card p-3 shadow-sm">
        <h5>Education</h5>
        <div id="educationList">
          <?php if ($education): ?>
            <p><b><?= $education['degree'] ?></b> - <?= $education['college'] ?> (<?= $education['year_passed'] ?>) | Marks: <?= $education['marks'] ?></p>
          <?php else: ?>
            <p class="text-muted">No education added yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Skills -->
      <div class="card p-3 shadow-sm">
        <h5>Key Skills</h5>
        <div id="skillsList">
          <?php if ($skills): foreach ($skills as $skill): ?>
            <span class="badge bg-primary me-1" data-id="<?= $skill['id'] ?>">
              <?= htmlspecialchars($skill['skill_name']) ?> (<?= htmlspecialchars($skill['proficiency']) ?>)
              <span class="cancel-icon skill-delete">&times;</span>
            </span>
          <?php endforeach; else: ?>
            <p class="text-muted">No skills added yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Projects -->
      <div class="card p-3 shadow-sm">
        <h5>Projects</h5>
        <div id="projectsList">
          <?php if ($projects): foreach ($projects as $proj): ?>
            <div class="mb-2 p-2 border rounded" data-id="<?= $proj['id'] ?>">
              <b><?= htmlspecialchars($proj['title']) ?></b>
              <span class="cancel-icon project-delete">&times;</span>
              <p class="mb-1"><?= htmlspecialchars($proj['description']) ?></p>
              <small>Technologies: <?= htmlspecialchars($proj['technologies']) ?></small>
            </div>
          <?php endforeach; else: ?>
            <p class="text-muted">No projects added yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Accomplishments -->
      <div class="card p-3 shadow-sm">
        <h5>Accomplishments</h5>
        <div id="accomplishmentsList">
          <?php if ($accomplishments): ?>
            <p><b><?= $accomplishments['achievement'] ?>:</b> <?= $accomplishments['description'] ?></p>
          <?php else: ?>
            <p class="text-muted">No accomplishments added yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Career Profile -->
      <div class="card p-3 shadow-sm">
        <h5>Career Profile</h5>
        <div id="careerList">
          <?php if ($career): ?>
            <p><b>Desired Role:</b> <?= $career['desired_role'] ?></p>
            <p><b>Preferred Location:</b> <?= $career['preferred_location'] ?></p>
            <p><b>Expected CTC:</b> <?= $career['expected_ctc'] ?></p>
          <?php else: ?>
            <p class="text-muted">No career profile added yet.</p>
          <?php endif; ?>
        </div>
      </div>

      <!-- Personal Details -->
      <div class="card p-3 shadow-sm">
        <h5>Personal Details</h5>
        <div id="personalList">
          <?php if ($personal): ?>
            <p><b>DOB:</b> <?= $personal['dob'] ?></p>
            <p><b>Gender:</b> <?= $personal['gender'] ?></p>
            <p><b>Marital Status:</b> <?= $personal['marital_status'] ?></p>
            <p><b>Address:</b> <?= $personal['address'] ?></p>
          <?php else: ?>
            <p class="text-muted">No personal details added yet.</p>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ===================== Modals ===================== -->
<?php include "modals.php"; ?>

<script>
function ajaxFormSubmit(formId, url, listId){
  $('#' + formId).on('submit', function(e){
    e.preventDefault();
    $.post(url, $(this).serialize(), function(response){
      $('#' + listId).html(response);
      $('#' + formId).closest('.modal').modal('hide');
    });
  });
}

ajaxFormSubmit('personalForm','personal_handler.php','personalList');
ajaxFormSubmit('careerForm','career_handler.php','careerList');
ajaxFormSubmit('educationForm','education_handler.php','educationList');
ajaxFormSubmit('accomplishmentsForm','accomplishments_handler.php','accomplishmentsList');
ajaxFormSubmit('skillsForm','add_skills.php','skillsList');
ajaxFormSubmit('projectsForm','add_projects.php','projectsList');

// Delete skill
$(document).on('click','.skill-delete',function(){
  let badge = $(this).closest('span[data-id]');
  let id = badge.data('id');
  $.post('delete_skill.php',{id:id},function(){
    badge.fadeOut(300,function(){ $(this).remove(); });
  });
});

// Delete project
$(document).on('click','.project-delete',function(){
  let div = $(this).closest('div[data-id]');
  let id = div.data('id');
  $.post('delete_project.php',{id:id},function(){
    div.fadeOut(300,function(){ $(this).remove(); });
  });
});
</script>
</body>
</html>
