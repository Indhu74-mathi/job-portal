<?php
// Assumes $user_id, $education, $skills, $projects, $accomplishments, $career, $personal are defined in profile.php
?>

<!-- ===================== Education Modal ===================== -->
<div class="modal fade" id="educationModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="educationForm">
        <div class="modal-header">
          <h5 class="modal-title"><?= $education ? 'Edit Education' : 'Add Education' ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <input type="hidden" name="record_id" value="<?= $education['id'] ?? '' ?>">
          <div class="mb-3">
            <label>Degree</label>
            <input type="text" class="form-control" name="degree" value="<?= $education['degree'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label>College</label>
            <input type="text" class="form-control" name="college" value="<?= $education['college'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label>Year Passed</label>
            <input type="number" class="form-control" name="year_passed" value="<?= $education['year_passed'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label>Marks</label>
            <input type="text" class="form-control" name="marks" value="<?= $education['marks'] ?? '' ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ===================== Skills Modal ===================== -->
<div class="modal fade" id="skillsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="skillsForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <div class="mb-3">
            <label>Skill Name</label>
            <input type="text" class="form-control" name="skill_name" required>
          </div>
          <div class="mb-3">
            <label>Proficiency</label>
            <select class="form-control" name="proficiency" required>
              <option value="Beginner">Beginner</option>
              <option value="Intermediate">Intermediate</option>
              <option value="Expert">Expert</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ===================== Projects Modal ===================== -->
<div class="modal fade" id="projectsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="projectsForm">
        <div class="modal-header">
          <h5 class="modal-title">Add Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <div class="mb-3">
            <label>Title</label>
            <input type="text" class="form-control" name="title" required>
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea class="form-control" name="description" required></textarea>
          </div>
          <div class="mb-3">
            <label>Technologies</label>
            <input type="text" class="form-control" name="technologies">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ===================== Accomplishments Modal ===================== -->
<div class="modal fade" id="accomplishmentsModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="accomplishmentsForm">
        <div class="modal-header">
          <h5 class="modal-title"><?= $accomplishments ? 'Edit Accomplishments' : 'Add Accomplishments' ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <input type="hidden" name="record_id" value="<?= $accomplishments['id'] ?? '' ?>">
          <div class="mb-3">
            <label>Achievement</label>
            <input type="text" class="form-control" name="achievement" value="<?= $accomplishments['achievement'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label>Description</label>
            <textarea class="form-control" name="description" required><?= $accomplishments['description'] ?? '' ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ===================== Career Profile Modal ===================== -->
<div class="modal fade" id="careerModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="careerForm">
        <div class="modal-header">
          <h5 class="modal-title"><?= $career ? 'Edit Career Profile' : 'Add Career Profile' ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <input type="hidden" name="record_id" value="<?= $career['id'] ?? '' ?>">
          <div class="mb-3">
            <label>Desired Role</label>
            <input type="text" class="form-control" name="desired_role" value="<?= $career['desired_role'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label>Preferred Location</label>
            <input type="text" class="form-control" name="preferred_location" value="<?= $career['preferred_location'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label>Expected CTC</label>
            <input type="text" class="form-control" name="expected_ctc" value="<?= $career['expected_ctc'] ?? '' ?>" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ===================== Personal Details Modal ===================== -->
<div class="modal fade" id="personalModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="personalForm">
        <div class="modal-header">
          <h5 class="modal-title"><?= $personal ? 'Edit Personal Details' : 'Add Personal Details' ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="user_id" value="<?= $user_id ?>">
          <input type="hidden" name="record_id" value="<?= $personal['id'] ?? '' ?>">
          <div class="mb-3">
            <label>DOB</label>
            <input type="date" class="form-control" name="dob" value="<?= $personal['dob'] ?? '' ?>" required>
          </div>
          <div class="mb-3">
            <label>Gender</label>
            <select class="form-control" name="gender" required>
              <option value="Male" <?= (isset($personal['gender']) && $personal['gender']=='Male')?'selected':'' ?>>Male</option>
              <option value="Female" <?= (isset($personal['gender']) && $personal['gender']=='Female')?'selected':'' ?>>Female</option>
              <option value="Other" <?= (isset($personal['gender']) && $personal['gender']=='Other')?'selected':'' ?>>Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Marital Status</label>
            <select class="form-control" name="marital_status" required>
              <option value="Single" <?= (isset($personal['marital_status']) && $personal['marital_status']=='Single')?'selected':'' ?>>Single</option>
              <option value="Married" <?= (isset($personal['marital_status']) && $personal['marital_status']=='Married')?'selected':'' ?>>Married</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Address</label>
            <textarea class="form-control" name="address" required><?= $personal['address'] ?? '' ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- ===================== Delete Handling ===================== -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // ===== Skill Delete =====
    $(document).on('click','.skill-delete',function(){
        let badge = $(this).closest('li');
        let id = badge.data('id');

        if(confirm('Are you sure to delete this skill?')){
            $.post('delete_skill.php', {id:id}, function(res){
                badge.fadeOut(300,function(){ $(this).remove(); });
            });
        }
    });

    // ===== Project Delete =====
    $(document).on('click','.project-delete',function(){
        let projDiv = $(this).closest('div[data-id]');
        let id = projDiv.data('id');

        if(confirm('Are you sure to delete this project?')){
            $.post('delete_project.php', {id:id}, function(res){
                projDiv.fadeOut(300,function(){ $(this).remove(); });
            });
        }
    });

});
</script>
