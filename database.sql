-- ============================================================
--  Student Course Hub – Full Database Setup
--  Import this file directly: mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS student_course_hub
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE student_course_hub;

-- ── Drop existing tables (safe re-import) ───────────────────
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS interest_registrations;
DROP TABLE IF EXISTS programme_modules;
DROP TABLE IF EXISTS modules;
DROP TABLE IF EXISTS programmes;
DROP TABLE IF EXISTS admins;
SET FOREIGN_KEY_CHECKS = 1;

-- ── Tables ───────────────────────────────────────────────────

CREATE TABLE programmes (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    level        ENUM('Undergraduate','Postgraduate') NOT NULL,
    description  TEXT NOT NULL,
    image_url    VARCHAR(500) DEFAULT NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE modules (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title         VARCHAR(255) NOT NULL,
    description   TEXT NOT NULL,
    year_of_study TINYINT UNSIGNED NOT NULL DEFAULT 1,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE programme_modules (
    programme_id  INT UNSIGNED NOT NULL,
    module_id     INT UNSIGNED NOT NULL,
    PRIMARY KEY (programme_id, module_id),
    CONSTRAINT fk_pm_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE CASCADE,
    CONSTRAINT fk_pm_module    FOREIGN KEY (module_id)    REFERENCES modules(id)    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE interest_registrations (
    id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name     VARCHAR(100) NOT NULL,
    last_name      VARCHAR(100) NOT NULL,
    email          VARCHAR(255) NOT NULL,
    programme_id   INT UNSIGNED NOT NULL,
    withdraw_token CHAR(64) NOT NULL UNIQUE,
    registered_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_ir_programme FOREIGN KEY (programme_id) REFERENCES programmes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE admins (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username      VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Seed Data ────────────────────────────────────────────────

-- Admin account: username=admin  password=Admin1234!
-- Hash generated with: password_hash('Admin1234!', PASSWORD_BCRYPT)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$Y5B6dFmHMxqe9JnZBpAZP.nWeTQ1eVv.LKO2KHjQS9a.HV74q9LAm');

-- Programmes
INSERT INTO programmes (id, title, level, description, is_published) VALUES
(1, 'BSc Computer Science',    'Undergraduate', 'Build expertise in programming, algorithms, and software engineering over three years. Graduates go on to careers at leading tech firms worldwide.',       1),
(2, 'BSc Business Management', 'Undergraduate', 'Develop leadership, marketing, and finance skills for a global career. Includes a year-long placement opportunity.',                                   1),
(3, 'MSc Data Science',        'Postgraduate',  'Advanced study in machine learning, statistics, and big data technologies. Ideal for graduates looking to specialise in data-driven roles.',           1),
(4, 'MSc Cyber Security',      'Postgraduate',  'Gain cutting-edge skills in network security, ethical hacking, and digital forensics. Accredited by the National Cyber Security Centre.',             0);

-- Modules
INSERT INTO modules (id, title, description, year_of_study) VALUES
(1,  'Introduction to Programming',          'Core programming concepts using Python, covering variables, control flow, functions, and basic data structures.',      1),
(2,  'Mathematics for Computing',            'Essential mathematics including logic, sets, discrete structures, and introductory calculus for computer scientists.',  1),
(3,  'Data Structures & Algorithms',         'Fundamental algorithms, complexity analysis, sorting, searching, trees, and graphs.',                                   2),
(4,  'Software Engineering',                 'Agile methodologies, design patterns, version control, testing, and professional software development practices.',      2),
(5,  'Final Year Project',                   'Independent research and development project supervised by a member of academic staff.',                                3),
(6,  'Principles of Management',             'Introduction to organisational behaviour, leadership styles, and management theory.',                                   1),
(7,  'Marketing Fundamentals',               'Core marketing concepts including the marketing mix, consumer behaviour, and digital marketing.',                       1),
(8,  'Financial Accounting',                 'Fundamentals of financial reporting, balance sheets, income statements, and basic ratio analysis.',                     2),
(9,  'Machine Learning',                     'Supervised and unsupervised learning, neural networks, model evaluation, and scikit-learn/TensorFlow practicals.',     1),
(10, 'Big Data Technologies',                'Hadoop, Spark, and cloud-based data pipelines for processing and analysing large-scale datasets.',                      1),
(11, 'Statistical Methods',                  'Probability, hypothesis testing, regression analysis, and Bayesian inference applied to real-world datasets.',          1),
(12, 'Network Security Fundamentals',        'TCP/IP security, firewalls, intrusion detection, VPNs, and cryptography basics.',                                      1),
(13, 'Ethical Hacking & Penetration Testing','Practical penetration testing methodologies, tools (Metasploit, Burp Suite), and responsible disclosure.',             2);

-- Programme <-> Module assignments
INSERT INTO programme_modules (programme_id, module_id) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),
(2,6),(2,7),(2,8),
(3,9),(3,10),(3,11),
(4,12),(4,13);
