DROP DATABASE IF EXISTS young_innovators;
CREATE DATABASE young_innovators CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE young_innovators;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE,
  username VARCHAR(80) UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','parent','provider','admin') NOT NULL,
  display_name VARCHAR(120) NOT NULL,
  first_name VARCHAR(80),
  last_name VARCHAR(80),
  phone VARCHAR(40),
  birthdate DATE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_role (role)
) ENGINE=InnoDB;

CREATE TABLE parents (
  user_id INT UNSIGNED PRIMARY KEY,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE students (
  user_id INT UNSIGNED PRIMARY KEY,
  child_id VARCHAR(30) UNIQUE NOT NULL,
  parent_id INT UNSIGNED NOT NULL,
  age TINYINT UNSIGNED,
  daily_streak INT UNSIGNED DEFAULT 0,
  last_login DATE,
  onboarded TINYINT(1) DEFAULT 0,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (parent_id) REFERENCES parents(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(80) UNIQUE NOT NULL,
  badge_name VARCHAR(120),
  badge_icon VARCHAR(20)
) ENGINE=InnoDB;

INSERT INTO skills (name, badge_name, badge_icon) VALUES
('Math','Math Wizard','🧙'),
('Science','Science Star','⭐'),
('Coding','Code Ninja','🥷'),
('Reading','Bookworm','📚'),
('Music','Music Maestro','🎵'),
('Art','Creative Master','🎨');

CREATE TABLE student_skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  skill_id INT UNSIGNED NOT NULL,
  UNIQUE KEY uq_student_skill (student_id, skill_id),
  FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
  FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE providers (
  user_id INT UNSIGNED PRIMARY KEY,
  status ENUM('pending','approved','rejected')
      NOT NULL DEFAULT 'pending',
  bio TEXT,
  cv_path VARCHAR(255),
  org VARCHAR(255),
  qualifications TEXT,
  experience_years TINYINT UNSIGNED,
  portfolio_url VARCHAR(255),
  rejection_reason TEXT,
  applied_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE provider_specialties (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  provider_id INT UNSIGNED NOT NULL,
  specialty VARCHAR(120) NOT NULL,
  FOREIGN KEY (provider_id) REFERENCES providers(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE courses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  provider_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  skill_id INT UNSIGNED,

  game_type VARCHAR(50),
  game_page VARCHAR(255),
  csv_file VARCHAR(255),

  age_min TINYINT UNSIGNED DEFAULT 5,
  age_max TINYINT UNSIGNED DEFAULT 18,

  type ENUM('video','quiz','game') DEFAULT 'game',
  status ENUM('approved','unpublished') DEFAULT 'approved',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (provider_id) REFERENCES providers(user_id) ON DELETE CASCADE,
  FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE modules (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  course_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT,
  video_url VARCHAR(512),
  order_num SMALLINT UNSIGNED DEFAULT 0,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE provider_uploads (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  provider_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED,
  file_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  subject VARCHAR(80),
  status ENUM('uploaded','processing','processed','failed') DEFAULT 'uploaded',
  uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (provider_id) REFERENCES providers(user_id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE enrollments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED NOT NULL,
  status ENUM('enrolled', 'completed') DEFAULT 'enrolled',
  progress TINYINT UNSIGNED DEFAULT 0,
  enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_enrollment (student_id, course_id),
  FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE course_attempts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED NOT NULL,
  accuracy FLOAT DEFAULT 0,
  time_ms INT UNSIGNED DEFAULT 0,
  completed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE student_badges (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id INT UNSIGNED NOT NULL,
  skill_id INT UNSIGNED NOT NULL,
  level TINYINT UNSIGNED DEFAULT 1,
  xp INT UNSIGNED DEFAULT 0,
  UNIQUE KEY uq_badge (student_id, skill_id),
  FOREIGN KEY (student_id) REFERENCES students(user_id) ON DELETE CASCADE,
  FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE activity_log (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  student_id INT UNSIGNED NOT NULL,
  course_id INT UNSIGNED,

  activity VARCHAR(255),

  xp_earned INT DEFAULT 0,

  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (student_id)
    REFERENCES students(user_id)
    ON DELETE CASCADE,

  FOREIGN KEY (course_id)
    REFERENCES courses(id)
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE friends (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  requester_id INT UNSIGNED NOT NULL,
  addressee_id INT UNSIGNED NOT NULL,
  status ENUM('pending','accepted','blocked') DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_friend_pair (requester_id, addressee_id),
  FOREIGN KEY (requester_id) REFERENCES students(user_id) ON DELETE CASCADE,
  FOREIGN KEY (addressee_id) REFERENCES students(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE game_analytics (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

  course_id INT UNSIGNED NOT NULL,
  student_id INT UNSIGNED NOT NULL,

  score INT DEFAULT 0,
  completed TINYINT(1) DEFAULT 0,

  played_at DATETIME DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (course_id)
    REFERENCES courses(id)
    ON DELETE CASCADE,

  FOREIGN KEY (student_id)
    REFERENCES students(user_id)
    ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE parent_chat_rooms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    course_id INT UNSIGNED NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (course_id)
        REFERENCES courses(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE parent_chat_room_members (
    room_id INT UNSIGNED NOT NULL,
    parent_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (room_id, parent_id),

    FOREIGN KEY (room_id)
        REFERENCES parent_chat_rooms(id)
        ON DELETE CASCADE,

    FOREIGN KEY (parent_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE parent_chat_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    room_id INT UNSIGNED NOT NULL,

    parent_id INT UNSIGNED NOT NULL,

    message TEXT NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (room_id)
        REFERENCES parent_chat_rooms(id)
        ON DELETE CASCADE,

    FOREIGN KEY (parent_id)
        REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- First admin account. Change this password after first login.
-- Login: admin@younginnovators.com / Admin@1234
INSERT INTO users (email, username, password_hash, role, display_name, first_name, last_name)
VALUES ('admin@younginnovators.com','admin','$2y$12$tSuQ8d2Hx81YrXM3JPqWAeTRwruEvGaycn/o2FRbYzVDNMf4Bkbuq','admin','Admin','Admin','User');
