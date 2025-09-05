CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(190) NOT NULL UNIQUE,
  `phone` VARCHAR(20) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `work_status` ENUM('experienced','fresher') NOT NULL,
  `total_exp_months` INT UNSIGNED DEFAULT 0,
  `current_city` VARCHAR(120) NOT NULL,
  `current_company` VARCHAR(160) DEFAULT NULL,
  `current_role` VARCHAR(160) DEFAULT NULL,
  `current_ctc_lpa` DECIMAL(8,2) DEFAULT NULL,
  `notice_period_days` INT UNSIGNED DEFAULT NULL,
  `resume_path` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;





CREATE TABLE IF NOT EXISTS `jobs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `company` VARCHAR(150) NOT NULL,
  `location` VARCHAR(120) NOT NULL,
  `description` TEXT,
  `skills` VARCHAR(255),            -- comma-separated keywords
  `rating` DECIMAL(2,1) DEFAULT NULL,
  `posted_days` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `jobs` (title, company, location, description, skills, rating, posted_days) VALUES
('Junior PHP Developer','Calibre Infotech','Chennai','Work on PHP web apps','php,mysql,html,css',4.0,2),
('Test Professional (Graduate)','Siemens','Chennai','Test engineer role','testing,automation',4.0,4),
('Frontend Developer','Passion People','Bengaluru','React developer','javascript,react,html,css',4.2,1),
('Backend Engineer','Acme Soft','Chennai','Node/PHP backend','nodejs,php,mysql',3.9,3),
('Fullstack Developer','TopTech','Chennai','Fullstack role','javascript,php,react,mysql',4.1,6);



-- Profile photo (or keep inside users if you want only 1 photo per user)
ALTER TABLE users ADD COLUMN profile_photo VARCHAR(255) DEFAULT NULL;

CREATE TABLE education (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  degree VARCHAR(120),
  college VARCHAR(200),
  marks VARCHAR(50),
  year_passed YEAR,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  skill_name VARCHAR(100),
  proficiency ENUM('Beginner','Intermediate','Expert') DEFAULT 'Beginner',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(200),
  description TEXT,
  technologies VARCHAR(200),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE accomplishments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  achievement VARCHAR(255),
  description TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE career_profile (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  desired_role VARCHAR(150),
  preferred_location VARCHAR(150),
  expected_ctc VARCHAR(50),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE personal_details (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  dob DATE,
  gender ENUM('Male','Female','Other'),
  marital_status ENUM('Single','Married'),
  address TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);



-- Applications table
-- CREATE TABLE IF NOT EXISTS job_applications (
--   id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--   job_id INT UNSIGNED NOT NULL,
--   user_id INT UNSIGNED NOT NULL,
--   applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--   UNIQUE KEY uniq_job_user (job_id, user_id),
--   CONSTRAINT fk_app_job FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
--   CONSTRAINT fk_app_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
-- );




-- CREATE TABLE recruiter_jobs (
--     job_id INT AUTO_INCREMENT PRIMARY KEY,
--     recruiter_id INT NOT NULL,
--     job_title VARCHAR(255) NOT NULL,
--     job_description TEXT NOT NULL,
--     location VARCHAR(255),
--     salary VARCHAR(100),
--     job_type ENUM('Full-Time', 'Part-Time', 'Internship') NOT NULL,
--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
-- );

CREATE TABLE recruiters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    location VARCHAR(150),
    contact_number VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE recruiter_jobs (
    job_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    recruiter_id INT UNSIGNED NOT NULL,
    job_title VARCHAR(255) NOT NULL,
    job_description TEXT NOT NULL,
    location VARCHAR(255),
    salary VARCHAR(100),
    job_type ENUM('Full-Time', 'Part-Time', 'Internship') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO recruiter_jobs (recruiter_id, job_title, job_description, location, salary, job_type)
VALUES 
(1, 'PHP Developer', 'Looking for an experienced PHP developer', 'Chennai', '4.5 LPA', 'Full-Time'),
(2, 'Frontend Developer', 'React.js developer needed', 'Bangalore', '5 LPA', 'Full-Time');


CREATE TABLE job_applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    job_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_job_user (job_id, user_id),
    CONSTRAINT fk_app_job FOREIGN KEY (job_id) REFERENCES recruiter_jobs(job_id) ON DELETE CASCADE,
    CONSTRAINT fk_app_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
