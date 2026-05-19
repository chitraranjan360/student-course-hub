-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2026 at 11:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_course_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$Y5B6dFmHMxqe9JnZBpAZP.nWeTQ1eVv.LKO2KHjQS9a.HV74q9LAm'),
(2, 'Chitraranjan', '$2y$10$ictXo3/apKjwJpT7SiFwD.E5FR2fAtsZicZNCGvWsY/K.xqyANaj2');

-- --------------------------------------------------------

--
-- Table structure for table `interest_registrations`
--

CREATE TABLE `interest_registrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `programme_id` int(10) UNSIGNED NOT NULL,
  `withdraw_token` char(64) NOT NULL,
  `registered_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interest_registrations`
--

INSERT INTO `interest_registrations` (`id`, `first_name`, `last_name`, `email`, `programme_id`, `withdraw_token`, `registered_at`) VALUES
(1, 'Emily', 'Carter', 'emily.carter@example.com', 1, '6f3e2893fbdc9052a8e17c463644351c751234a25b2e7c184d1a5198438ebd94', '2026-05-13 20:21:42'),
(2, 'James', 'Walker', 'james.walker@example.com', 3, '52680d0d71dc488cb4178578c4c3c3fc35d756590cb61bbb4e6e103b4ede8894', '2026-05-13 20:21:42'),
(3, 'Aisha', 'Khan', 'aisha.khan@example.com', 5, 'e2ac95bfe381b8eb49a3dc977536de6e6814e2cd036182394bde226fb16b4dad', '2026-05-13 20:21:42'),
(4, 'Daniel', 'Smith', 'daniel.smith@example.com', 9, '53c788b75a9f30e93def369717fb07a20d4adaf336570f95bb738dca11b15483', '2026-05-13 20:21:42'),
(5, 'Sofia', 'Ali', 'sofia.ali@example.com', 12, '8a038a3083bdc338b774a4e5e9a56b6b84e9bfdb85bd06c10f37fa9678f7beee', '2026-05-13 20:21:42'),
(6, 'Chitraranjan', 'Yadav', 'chitra123@gmail.com', 1, 'd4b86886b0c7561c010613997f1a27b39dfc1e5cf2cabd01f2b89518d53f6599', '2026-05-13 21:23:42'),
(7, 'Chitraranjan', 'Yadav', 'chitraranjanyadavr360@gmail.com', 9, 'a9e3d22945c3cd1332d5fb2863f9be6186e9ee622efde5c7b1574708b7d0502f', '2026-05-17 00:54:15');

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `year_of_study` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `title`, `description`, `photo`, `year_of_study`, `created_at`) VALUES
(1, 'Introduction to Programming', 'This module introduces students to the foundations of programming using Python. It covers essential concepts such as variables, data types, control flow, functions, and basic data structures. Students learn how to write simple programs, solve computational problems, and understand how programming logic works. The module builds confidence in coding and prepares learners for more advanced software development topics.', NULL, 1, '2026-05-13 20:06:26'),
(2, 'Mathematics for Computing', 'This module provides the mathematical foundations required in computer science. Students explore logic, sets, discrete structures, and introductory calculus. The focus is on developing analytical thinking and understanding how mathematical principles support algorithms, data structures, and computational models. The module strengthens problem‑solving skills essential for technical subjects and prepares learners for more advanced computing modules.', NULL, 1, '2026-05-13 20:06:26'),
(3, 'Data Structures & Algorithms', 'Students learn core data structures such as arrays, linked lists, stacks, queues, trees, and graphs, along with fundamental algorithms for sorting, searching, and traversal. The module emphasises algorithmic thinking and complexity analysis, helping students evaluate performance and efficiency. Practical exercises reinforce understanding and prepare learners for advanced programming, system design, and technical interviews.', NULL, 2, '2026-05-13 20:06:26'),
(4, 'Software Engineering', 'This module introduces professional software development practices, including agile methodologies, design patterns, version control, and testing strategies. Students learn how to plan, design, build, and maintain software systems collaboratively. The module emphasises quality, maintainability, documentation, and real‑world workflows used in modern engineering teams, preparing learners for industry‑level development environments.', NULL, 2, '2026-05-13 20:06:26'),
(5, 'Final Year Project', 'Students undertake an independent research and development project supervised by academic staff. The module encourages creativity, critical thinking, and technical depth. Learners define a problem, conduct research, design a solution, and produce a final deliverable demonstrating their skills and knowledge. It represents a major capstone experience and showcases the student’s academic and practical abilities.', NULL, 3, '2026-05-13 20:06:26'),
(6, 'Principles of Management', 'This module introduces key management concepts including organisational behaviour, leadership styles, motivation, and decision‑making. Students explore how managers coordinate people and resources to achieve organisational goals. The module builds understanding of workplace dynamics, communication, and effective leadership practices essential for business environments.', NULL, 1, '2026-05-13 20:06:26'),
(7, 'Marketing Fundamentals', 'Students learn core marketing principles such as the marketing mix, segmentation, consumer behaviour, and digital marketing. The module explains how organisations create value, communicate with audiences, and build effective marketing strategies across different channels. It provides a strong foundation for further study in marketing and business.', NULL, 1, '2026-05-13 20:06:26'),
(8, 'Financial Accounting', 'This module covers the fundamentals of financial accounting, including balance sheets, income statements, and ratio analysis. Students learn how organisations record, summarise, and interpret financial information to support decision‑making and communicate performance. The module builds essential skills for business, finance, and accounting roles.', NULL, 2, '2026-05-13 20:06:26'),
(9, 'Machine Learning', 'Students explore supervised and unsupervised learning, neural networks, model evaluation, and practical machine learning using tools such as scikit‑learn and TensorFlow. The module focuses on building, training, and assessing predictive models for real‑world applications. Learners gain hands‑on experience with modern machine learning workflows.', NULL, 1, '2026-05-13 20:06:26'),
(10, 'Big Data Technologies', 'This module introduces large‑scale data processing using Hadoop, Spark, and cloud‑based data pipelines. Students learn how distributed systems handle massive datasets and how to design workflows for analytics and processing at scale. The module prepares learners for working with modern big data technologies.', NULL, 1, '2026-05-13 20:06:26'),
(11, 'Statistical Methods', 'Students learn probability, hypothesis testing, regression analysis, and Bayesian inference. The module focuses on applying statistical techniques to real datasets and interpreting results to support data‑driven decisions. It builds strong analytical and quantitative reasoning skills essential for data‑focused roles.', NULL, 1, '2026-05-13 20:06:26'),
(12, 'Network Security Fundamentals', 'This module covers essential security concepts including TCP/IP security, firewalls, intrusion detection systems, VPNs, and basic cryptography. Students learn how networks are protected, how attacks occur, and how common threats are mitigated. It provides a strong foundation for further study in cybersecurity.', NULL, 1, '2026-05-13 20:06:26'),
(13, 'Ethical Hacking & Penetration Testing', 'Students gain hands‑on experience with penetration testing tools and methodologies, including Metasploit and Burp Suite. The module covers vulnerability assessment, exploitation techniques, reporting, and responsible disclosure. It prepares learners for ethical hacking roles and strengthens practical cybersecurity skills.', NULL, 2, '2026-05-13 20:06:26'),
(14, 'Web Development', 'This module teaches frontend and backend development using HTML, CSS, JavaScript, PHP, APIs, and responsive design. Students learn how to build functional, user‑friendly websites and web applications. The module emphasises practical development skills and modern web standards.', 'module_1779134128_d2640a2b.jpg', 1, '2026-05-13 20:18:53'),
(15, 'Object-Oriented Programming', 'Students learn object‑oriented principles including classes, inheritance, abstraction, and encapsulation. The module focuses on designing modular, reusable, and maintainable software systems. Learners develop strong programming habits and gain experience applying OOP concepts to real projects.', NULL, 1, '2026-05-13 20:18:53'),
(16, 'Database Systems', 'This module covers relational database design, SQL querying, normalization, indexing, and transaction management. Students learn how to build efficient and reliable database‑driven applications. The module emphasises practical SQL skills and sound database architecture.', NULL, 2, '2026-05-13 20:18:53'),
(17, 'Requirements Engineering', 'Students learn techniques for eliciting, analysing, documenting, and validating software requirements. The module emphasises communication with stakeholders, producing high‑quality requirement specifications, and ensuring that systems meet user needs. It builds strong analytical and documentation skills.', NULL, 2, '2026-05-13 20:18:53'),
(18, 'DevOps and Deployment', 'This module introduces DevOps practices including continuous integration, delivery pipelines, containerisation, monitoring, and automated deployment. Students learn modern workflows used in cloud‑native development and how to streamline software delivery processes.', NULL, 3, '2026-05-13 20:18:53'),
(19, 'Corporate Finance', 'Students explore capital structure, investment appraisal, financial planning, and organisational financial decision‑making. The module explains how financial strategies support business objectives and how organisations manage financial resources effectively.', NULL, 2, '2026-05-13 20:18:53'),
(20, 'Taxation Principles', 'This module covers personal and corporate taxation, including compliance, tax calculations, and legal responsibilities. Students learn how tax systems operate and how organisations meet regulatory requirements. It builds practical understanding of tax processes.', NULL, 2, '2026-05-13 20:18:53'),
(21, 'Auditing', 'Students learn internal and external auditing concepts, including assurance, audit evidence, controls, and ethical considerations. The module explains how auditors evaluate financial accuracy and organisational integrity, preparing learners for auditing roles.', NULL, 3, '2026-05-13 20:18:53'),
(22, 'Digital Content Strategy', 'This module teaches how to plan, create, and manage digital content for websites, campaigns, email, and social platforms. Students learn how to align content with audience needs and organisational goals, improving digital communication effectiveness.', NULL, 1, '2026-05-13 20:18:53'),
(23, 'Social Media Analytics', 'Students learn how to measure social media performance using engagement, reach, conversion, and reporting metrics. The module focuses on analysing and optimising digital campaigns to improve results and audience impact.', NULL, 2, '2026-05-13 20:18:53'),
(24, 'Consumer Behaviour', 'This module explores how consumers think, decide, and respond to marketing messages across digital and traditional channels. Students learn psychological and behavioural influences on purchasing decisions and how marketers use these insights.', NULL, 2, '2026-05-13 20:18:53'),
(25, 'Campaign Planning', 'Students learn how to design and execute integrated marketing campaigns, including budgeting, scheduling, and performance evaluation. The module emphasises strategic planning and multi‑channel coordination to achieve marketing objectives.', NULL, 3, '2026-05-13 20:18:53'),
(26, 'Engineering Mathematics', 'This module covers applied mathematics for engineering, including calculus, matrices, differential equations, and numerical methods. Students develop problem‑solving skills essential for engineering analysis and design.', NULL, 1, '2026-05-13 20:18:53'),
(27, 'Statics and Dynamics', 'Students learn principles of forces, motion, equilibrium, and mechanical system behaviour. The module focuses on analysing static and dynamic engineering problems and applying physics to real‑world systems.', NULL, 1, '2026-05-13 20:18:53'),
(28, 'Thermodynamics', 'This module introduces thermodynamic principles including heat, energy, work, and system behaviour. Students apply these concepts to engineering scenarios and problem‑solving in mechanical and thermal systems.', NULL, 2, '2026-05-13 20:18:53'),
(29, 'Materials and Manufacturing', 'Students explore engineering materials, their properties, manufacturing processes, and production systems. The module explains how material selection affects design, performance, and manufacturing efficiency.', NULL, 2, '2026-05-13 20:18:53'),
(30, 'Machine Design', 'This module covers mechanical component design, stress analysis, fatigue, and safety considerations. Students learn how to design reliable and efficient mechanical systems using engineering principles.', NULL, 3, '2026-05-13 20:18:53'),
(31, 'Deep Learning', 'Students explore neural networks, backpropagation, convolutional networks, transformers, and practical deep learning model development. The module focuses on building advanced AI systems and understanding modern architectures.', NULL, 1, '2026-05-13 20:18:53'),
(32, 'Natural Language Processing', 'This module teaches text preprocessing, language models, classification, and information extraction. Students learn how to build NLP applications for real‑world tasks such as sentiment analysis and text classification.', NULL, 1, '2026-05-13 20:18:53'),
(33, 'Computer Vision', 'Students learn image processing, feature extraction, object detection, segmentation, and visual recognition. The module explains how machines interpret visual data and how computer vision systems are built.', NULL, 1, '2026-05-13 20:18:53'),
(34, 'AI Ethics and Governance', 'This module explores fairness, bias, transparency, accountability, privacy, and regulation in AI systems. Students learn how to design responsible and ethical AI solutions and understand governance frameworks.', NULL, 1, '2026-05-13 20:18:53'),
(35, 'Project Planning and Control', 'Students learn project scheduling, milestones, work breakdown structures, and performance tracking. The module focuses on planning and controlling successful projects using structured project management techniques.', NULL, 1, '2026-05-13 20:18:53'),
(36, 'Risk and Quality Management', 'This module covers identifying project risks, planning mitigation strategies, ensuring quality, and applying governance practices. Students learn how to maintain project reliability and deliver successful outcomes.', NULL, 1, '2026-05-13 20:18:53'),
(37, 'Agile Project Delivery', 'Students learn Scrum, Kanban, sprint planning, and stakeholder communication. The module focuses on iterative, collaborative project delivery and modern agile practices used in industry.', NULL, 1, '2026-05-13 20:18:53'),
(38, 'Procurement and Contracts', 'This module teaches procurement processes, supplier management, contract administration, and legal considerations. Students learn how organisations acquire goods and services effectively and manage supplier relationships.', NULL, 1, '2026-05-13 20:18:53'),
(39, 'International Commercial Contracts', 'Students explore legal principles governing international business agreements and contract enforcement. The module explains how cross‑border contracts are structured, negotiated, and regulated.', NULL, 1, '2026-05-13 20:18:53'),
(40, 'Trade Law and Regulation', 'This module covers regulatory frameworks affecting international trade, market access, and compliance. Students learn how laws shape global business operations and international market participation.', NULL, 1, '2026-05-13 20:18:53'),
(41, 'Corporate Governance', 'Students learn governance principles including board structures, accountability, ethics, and compliance. The module explains how organisations maintain responsible oversight and effective governance.', NULL, 1, '2026-05-13 20:18:53'),
(42, 'International Dispute Resolution', 'This module teaches arbitration, mediation, litigation strategy, and enforcement across jurisdictions. Students learn how international disputes are resolved and how legal processes differ globally.', NULL, 1, '2026-05-13 20:18:53'),
(43, 'Distributed Systems', 'Students explore scalability, consistency, fault tolerance, messaging, and distributed application design. The module explains how large‑scale systems operate reliably across multiple nodes.', NULL, 1, '2026-05-13 20:18:53'),
(44, 'Cloud Infrastructure', 'This module covers compute, storage, networking, cloud service models, and architecture design. Students learn how cloud platforms support modern applications and scalable infrastructure.', NULL, 1, '2026-05-13 20:18:53'),
(45, 'Containers and Kubernetes', 'Students learn containerisation, orchestration, scaling, service discovery, and deployment using Kubernetes. The module focuses on cloud‑native application delivery and modern deployment workflows.', NULL, 1, '2026-05-13 20:18:53'),
(46, 'Cloud Security', 'This module covers identity, access control, encryption, compliance, threat modelling, and cloud‑native security practices. Students learn how to secure cloud environments effectively and manage risks.', NULL, 1, '2026-05-13 20:18:53');

-- --------------------------------------------------------

--
-- Table structure for table `programmes`
--

CREATE TABLE `programmes` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `level` enum('Undergraduate','Postgraduate') NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programmes`
--

INSERT INTO `programmes` (`id`, `title`, `level`, `description`, `image_url`, `is_published`, `created_at`) VALUES
(1, 'BSc Computer Science', 'Undergraduate', 'This programme develops strong technical and problem-solving skills through programming, algorithms, databases, software engineering, and systems design. Students learn how modern software is built, tested, and maintained, with practical projects that prepare them for careers in software development, IT, and technology-related roles.', '/uploads/programmes/1778703456_7f45b5a7abe1.jpg', 1, '2026-05-13 20:06:26'),
(2, 'BSc Business Management', 'Undergraduate', 'This programme introduces the main areas of business, including leadership, strategy, marketing, finance, and operations. Students gain an understanding of how organisations work and how managers make decisions in competitive environments, preparing them for careers in management, consultancy, entrepreneurship, and business support roles.', 'uploads/programmes/1778703878_35937bafa98d.jpg', 1, '2026-05-13 20:06:26'),
(3, 'MSc Data Science', 'Postgraduate', 'This programme focuses on collecting, analysing, and interpreting data to support decision-making in business, technology, and research. Students study machine learning, statistics, big data tools, and data visualisation, building the analytical and technical skills needed for careers in data science, analytics, and research-focused roles.', 'uploads/programmes/1778703902_2c6354afea68.jpg', 1, '2026-05-13 20:06:26'),
(4, 'MSc Cyber Security', 'Postgraduate', 'This programme develops practical and theoretical knowledge of protecting systems, networks, and data from digital threats. Students study ethical hacking, network security, digital forensics, and secure system design, preparing for roles in cyber defence, security analysis, and information protection.', 'uploads/programmes/1778703941_226b7b171a74.jpg', 1, '2026-05-13 20:06:26'),
(5, 'BSc Software Engineering', 'Undergraduate', 'This programme concentrates on the full software development lifecycle, from requirements and design through testing, deployment, and maintenance. Students learn modern development practices such as version control, agile methods, DevOps, and scalable system design, preparing them for professional software engineering careers.', 'uploads/programmes/1778703966_bdd4eb9632d6.jpg', 1, '2026-05-13 20:17:54'),
(6, 'BSc Accounting and Finance', 'Undergraduate', 'This programme builds a strong foundation in financial reporting, auditing, taxation, corporate finance, and business analysis. Students develop the knowledge and numerical skills needed to understand financial performance and support decision-making in accounting, banking, finance, and consultancy roles.', 'uploads/programmes/1778703990_73d5ba553293.jpg', 1, '2026-05-13 20:17:54'),
(7, 'BA Digital Marketing', 'Undergraduate', 'This programme explores how businesses promote products and services through digital channels such as social media, content marketing, search, and analytics. Students learn branding, campaign planning, consumer behaviour, and performance analysis, preparing them for careers in digital marketing, advertising, and communications.', 'uploads/programmes/1778704014_efe0d9743a18.jpg', 1, '2026-05-13 20:17:54'),
(8, 'BEng Mechanical Engineering', 'Undergraduate', 'This programme combines engineering theory with practical design and problem-solving in areas such as mechanics, thermodynamics, materials, manufacturing, and machine design. Students gain the technical knowledge and analytical skills needed for careers in engineering, product development, manufacturing, and technical design.', 'uploads/programmes/1778704039_12a274a4e63b.jpg', 1, '2026-05-13 20:17:54'),
(9, 'MSc Artificial Intelligence', 'Postgraduate', 'This programme examines how intelligent systems can learn, reason, and make decisions using data. Students study machine learning, neural networks, computer vision, natural language processing, and AI ethics, preparing them for advanced roles in AI development, research, and applied intelligent systems.', 'uploads/programmes/1778704055_9f3733f4aa7b.jpg', 1, '2026-05-13 20:17:54'),
(10, 'MSc Project Management', 'Postgraduate', 'This programme develops the skills needed to plan, deliver, and control projects in a wide range of industries. Students study project planning, risk management, budgeting, quality, leadership, and agile delivery, preparing them for roles in project coordination, management, and business operations.', 'uploads/programmes/1778704087_ef876b79ea04.jpg', 1, '2026-05-13 20:17:54'),
(11, 'LLM International Business Law', 'Postgraduate', 'This programme explores the legal frameworks that shape global business, including trade law, commercial contracts, dispute resolution, and corporate governance. Students develop advanced legal understanding and analytical skills for careers in international law, legal consultancy, compliance, and business regulation.', 'uploads/programmes/1778704103_657773d3dde4.jpg', 1, '2026-05-13 20:17:54'),
(12, 'MSc Cloud Computing', 'Postgraduate', 'This programme focuses on modern cloud technologies used to build and run scalable digital services. Students study distributed systems, cloud infrastructure, virtualization, containers, cloud security, and deployment practices, preparing them for careers in cloud engineering, infrastructure, and systems administration.', 'uploads/programmes/1778704119_d1cb480ba40c.jpg', 1, '2026-05-13 20:17:54');

-- --------------------------------------------------------

--
-- Table structure for table `programme_modules`
--

CREATE TABLE `programme_modules` (
  `programme_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL,
  `year_of_study` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programme_modules`
--

INSERT INTO `programme_modules` (`programme_id`, `module_id`, `year_of_study`) VALUES
(1, 1, 2),
(1, 2, 2),
(1, 3, 1),
(1, 4, 1),
(1, 5, 3),
(2, 6, 1),
(2, 7, 1),
(2, 8, 1),
(3, 9, 1),
(3, 10, 1),
(3, 11, 1),
(4, 12, 1),
(4, 13, 1),
(5, 14, 1),
(5, 15, 1),
(5, 16, 1),
(5, 17, 1),
(5, 18, 1),
(6, 8, 1),
(6, 19, 1),
(6, 20, 1),
(6, 21, 1),
(7, 7, 1),
(7, 22, 1),
(7, 23, 1),
(7, 24, 1),
(7, 25, 1),
(8, 26, 1),
(8, 27, 1),
(8, 28, 1),
(8, 29, 1),
(8, 30, 1),
(9, 9, 1),
(9, 31, 1),
(9, 32, 1),
(9, 33, 1),
(9, 34, 1),
(10, 35, 1),
(10, 36, 1),
(10, 37, 1),
(10, 38, 1),
(11, 39, 1),
(11, 40, 1),
(11, 41, 1),
(11, 42, 1),
(12, 16, 1),
(12, 43, 1),
(12, 44, 1),
(12, 45, 1),
(12, 46, 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `role` enum('instructor','coordinator','admin') NOT NULL DEFAULT 'instructor',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `username`, `password_hash`, `email`, `full_name`, `role`, `is_active`, `created_by`, `created_at`) VALUES
(1, 'Niranjan', '$2y$10$rac3ucVuVNVz9WRP9opkqeqE7F3dk0g46ZdzEqXiiXNTFtaXU2IOG', 'niranjan123@gmail.com', 'Niranjan GC', 'instructor', 1, 2, '2026-05-14 23:23:54'),
(2, 'Shubham', '$2y$10$gekl2Sm1lEmiPRa8KAh4CegX/NoLbDTLCBaTyIKeThdyCYO1aRO6K', 'shubham123@gmail.com', 'Shubham Sharma', 'instructor', 0, 2, '2026-05-15 00:04:40');

-- --------------------------------------------------------

--
-- Table structure for table `staff_modules`
--

CREATE TABLE `staff_modules` (
  `staff_id` int(10) UNSIGNED NOT NULL,
  `module_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_modules`
--

INSERT INTO `staff_modules` (`staff_id`, `module_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `staff_programmes`
--

CREATE TABLE `staff_programmes` (
  `staff_id` int(10) UNSIGNED NOT NULL,
  `programme_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff_programmes`
--

INSERT INTO `staff_programmes` (`staff_id`, `programme_id`) VALUES
(1, 3),
(2, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `interest_registrations`
--
ALTER TABLE `interest_registrations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `withdraw_token` (`withdraw_token`),
  ADD KEY `fk_ir_programme` (`programme_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programmes`
--
ALTER TABLE `programmes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programme_modules`
--
ALTER TABLE `programme_modules`
  ADD PRIMARY KEY (`programme_id`,`module_id`),
  ADD KEY `fk_pm_module` (`module_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_staff_creator` (`created_by`);

--
-- Indexes for table `staff_modules`
--
ALTER TABLE `staff_modules`
  ADD PRIMARY KEY (`staff_id`,`module_id`),
  ADD KEY `fk_sm_module` (`module_id`);

--
-- Indexes for table `staff_programmes`
--
ALTER TABLE `staff_programmes`
  ADD PRIMARY KEY (`staff_id`,`programme_id`),
  ADD KEY `fk_sp_programme` (`programme_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `interest_registrations`
--
ALTER TABLE `interest_registrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `programmes`
--
ALTER TABLE `programmes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `interest_registrations`
--
ALTER TABLE `interest_registrations`
  ADD CONSTRAINT `fk_ir_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programme_modules`
--
ALTER TABLE `programme_modules`
  ADD CONSTRAINT `fk_pm_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pm_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_creator` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`);

--
-- Constraints for table `staff_modules`
--
ALTER TABLE `staff_modules`
  ADD CONSTRAINT `fk_sm_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sm_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff_programmes`
--
ALTER TABLE `staff_programmes`
  ADD CONSTRAINT `fk_sp_programme` FOREIGN KEY (`programme_id`) REFERENCES `programmes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sp_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
