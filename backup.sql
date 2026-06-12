-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: university-system
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `academic_structures`
--

DROP TABLE IF EXISTS `academic_structures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `academic_structures` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `department_id` bigint unsigned NOT NULL,
  `generation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `academic_level` enum('bachelor','master','phd') COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_progress` enum('foundation','year_1','year_2','year_3','year_4','graduated') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_structures_department_id_foreign` (`department_id`),
  CONSTRAINT `academic_structures_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `academic_structures`
--

LOCK TABLES `academic_structures` WRITE;
/*!40000 ALTER TABLE `academic_structures` DISABLE KEYS */;
INSERT INTO `academic_structures` VALUES (1,1,'Gen 1st','bachelor','foundation','2026-06-02 18:57:23','2026-06-02 18:57:23'),(10,10,'Gen 1st','bachelor','year_1','2026-06-08 09:20:32','2026-06-08 09:20:32'),(11,10,'Gen 2nd','bachelor','foundation','2026-06-08 09:20:32','2026-06-08 09:20:32'),(12,11,'Gen 1st','bachelor','foundation','2026-06-10 02:59:53','2026-06-10 02:59:53');
/*!40000 ALTER TABLE `academic_structures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `banner_image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_pinned_to_top` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_visible` tinyint(1) NOT NULL DEFAULT '1',
  `target_roles` json DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'Welcome','New Announcement','announcement-banners/Gantt_Chart_answer_OS_Final_Exam.jpg',1,'2026-06-04 09:02:07','2026-06-04 09:02:07',1,'[\"student\", \"faculty_manager\", \"study_office\", \"teacher\"]');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assessment_submissions`
--

DROP TABLE IF EXISTS `assessment_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assessment_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `task_assessment_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `secured_score` double NOT NULL DEFAULT '0',
  `grade_letter` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F',
  `teacher_feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submission_notes` text COLLATE utf8mb4_unicode_ci,
  `attachment_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_qcm_responses` json DEFAULT NULL,
  `is_locked_by_office` tinyint(1) NOT NULL DEFAULT '0',
  `manager_approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_submissions_task_assessment_id_foreign` (`task_assessment_id`),
  KEY `assessment_submissions_student_id_foreign` (`student_id`),
  CONSTRAINT `assessment_submissions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessment_submissions_task_assessment_id_foreign` FOREIGN KEY (`task_assessment_id`) REFERENCES `task_assessments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assessment_submissions`
--

LOCK TABLES `assessment_submissions` WRITE;
/*!40000 ALTER TABLE `assessment_submissions` DISABLE KEYS */;
INSERT INTO `assessment_submissions` VALUES (1,2,10,0,'F',NULL,NULL,NULL,'{\"Q1\": \"B\"}',0,'pending','2026-06-04 00:11:18','2026-06-04 00:11:18'),(2,8,10,70,'C',NULL,NULL,NULL,'{\"q1\": \"A\", \"q2\": \"B\"}',0,'pending','2026-06-04 00:16:06','2026-06-09 04:49:01'),(3,1,10,50,'E','Good',NULL,'student-submissions-vault/01KT8XXDP8YWP0H8GS4T4TDC87.png',NULL,0,'pending','2026-06-04 02:02:32','2026-06-04 02:33:01'),(5,9,10,66.666666666667,'D+','Good',NULL,NULL,'{\"q1\": \"A\", \"q2\": \"C\", \"q4\": \"C\"}',0,'pending','2026-06-04 06:53:14','2026-06-04 06:54:26'),(6,10,10,100,'A+','Excellent',NULL,NULL,'{\"q1\": \"A\", \"q2\": \"B\"}',0,'pending','2026-06-04 06:53:39','2026-06-04 06:54:46'),(7,11,10,100,'A+',NULL,NULL,NULL,'{\"q1\": \"A\", \"q2\": \"B\"}',0,'pending','2026-06-08 08:53:51','2026-06-08 08:53:51'),(8,10,4,50,'E',NULL,NULL,NULL,'{\"q1\": \"A\", \"q2\": \"A\"}',0,'pending','2026-06-11 01:50:37','2026-06-11 01:50:37'),(9,12,9,50,'E','Good',NULL,NULL,'{\"q1\": \"B\", \"q2\": \"B\"}',0,'pending','2026-06-11 01:59:15','2026-06-11 02:00:51'),(10,11,9,100,'A+',NULL,NULL,NULL,'{\"q1\": \"A\", \"q2\": \"B\"}',0,'pending','2026-06-11 02:15:10','2026-06-11 02:15:10'),(11,2,9,100,'A+',NULL,NULL,NULL,'{\"Q1\": \"B\"}',0,'pending','2026-06-11 02:17:36','2026-06-11 02:17:36'),(12,1,9,70,'C','very good',NULL,'student-submissions-vault/01KTTZM0DMTA87S2W92BDN9TKP.pdf',NULL,0,'pending','2026-06-11 02:18:41','2026-06-11 02:20:56');
/*!40000 ALTER TABLE `assessment_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_schedule_id` bigint unsigned NOT NULL,
  `student_id` bigint unsigned NOT NULL,
  `teaching_date` date NOT NULL,
  `status` enum('present','absent','late','permission') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_class_schedule_id_foreign` (`class_schedule_id`),
  KEY `attendances_student_id_foreign` (`student_id`),
  CONSTRAINT `attendances_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendances_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
INSERT INTO `attendances` VALUES (1,1,4,'2026-06-03','present','2026-06-03 09:26:41','2026-06-03 09:26:41'),(2,1,5,'2026-06-03','present','2026-06-03 10:45:02','2026-06-03 10:45:02'),(3,1,7,'2026-06-03','absent','2026-06-03 10:45:02','2026-06-03 10:45:02'),(5,1,9,'2026-06-03','present','2026-06-03 10:45:02','2026-06-03 10:45:02'),(6,1,10,'2026-06-03','present','2026-06-03 10:45:02','2026-06-03 10:45:02'),(7,1,5,'2026-06-04','present','2026-06-03 10:51:28','2026-06-03 10:51:28'),(9,1,7,'2026-06-04','absent','2026-06-03 10:51:28','2026-06-03 10:51:28'),(10,1,8,'2026-06-04','permission','2026-06-03 10:51:28','2026-06-03 10:51:28'),(11,1,9,'2026-06-04','present','2026-06-03 10:51:28','2026-06-03 10:51:28'),(12,1,10,'2026-06-04','present','2026-06-03 10:51:28','2026-06-03 10:51:28'),(13,1,5,'2026-06-06','absent','2026-06-03 11:06:04','2026-06-03 12:14:21'),(14,1,4,'2026-06-06','absent','2026-06-03 11:06:04','2026-06-03 11:06:04'),(17,1,9,'2026-06-06','absent','2026-06-03 11:06:04','2026-06-03 11:06:04'),(18,1,10,'2026-06-06','present','2026-06-03 11:06:04','2026-06-03 11:09:32'),(22,1,8,'2026-06-07','absent','2026-06-03 11:44:12','2026-06-03 11:44:12'),(23,1,9,'2026-06-07','present','2026-06-03 11:44:12','2026-06-03 11:44:12'),(24,1,10,'2026-06-07','permission','2026-06-03 11:44:12','2026-06-03 11:44:12'),(26,1,4,'2026-06-08','late','2026-06-03 11:46:13','2026-06-08 08:46:12'),(27,1,7,'2026-06-08','permission','2026-06-03 11:46:13','2026-06-08 08:46:12'),(28,1,8,'2026-06-08','present','2026-06-03 11:46:13','2026-06-03 11:46:13'),(29,1,9,'2026-06-08','present','2026-06-03 11:46:13','2026-06-03 11:46:13'),(30,1,10,'2026-06-08','absent','2026-06-03 11:46:13','2026-06-08 08:46:12'),(32,1,4,'2026-06-12','absent','2026-06-03 11:47:14','2026-06-03 11:47:14'),(33,1,7,'2026-06-12','absent','2026-06-03 11:47:14','2026-06-03 11:47:14'),(34,1,8,'2026-06-12','present','2026-06-03 11:47:14','2026-06-03 11:47:14'),(36,1,10,'2026-06-12','present','2026-06-03 11:47:14','2026-06-03 11:47:14'),(37,1,5,'2026-06-09','present','2026-06-03 11:48:44','2026-06-09 11:45:00'),(38,1,4,'2026-06-09','present','2026-06-03 11:48:44','2026-06-03 11:48:44'),(39,1,7,'2026-06-09','present','2026-06-03 11:48:44','2026-06-03 11:48:44'),(40,1,8,'2026-06-09','present','2026-06-03 11:48:44','2026-06-03 11:48:44'),(41,1,9,'2026-06-09','present','2026-06-03 11:48:44','2026-06-03 11:48:44'),(42,1,10,'2026-06-09','late','2026-06-03 11:48:44','2026-06-09 11:45:00'),(43,1,5,'2026-06-08','present','2026-06-08 08:46:12','2026-06-08 08:46:12'),(44,1,5,'2026-06-28','present','2026-06-08 08:50:06','2026-06-08 08:50:06'),(45,1,4,'2026-06-28','present','2026-06-08 08:50:06','2026-06-08 08:50:06'),(46,1,7,'2026-06-28','present','2026-06-08 08:50:06','2026-06-08 08:50:06'),(47,1,8,'2026-06-28','present','2026-06-08 08:50:06','2026-06-08 08:50:06'),(48,1,9,'2026-06-28','present','2026-06-08 08:50:06','2026-06-08 08:50:06'),(49,1,10,'2026-06-28','absent','2026-06-08 08:50:06','2026-06-08 08:50:06'),(50,1,6,'2026-06-09','present','2026-06-09 11:45:00','2026-06-09 11:45:00'),(51,1,12,'2026-06-09','present','2026-06-09 11:45:00','2026-06-09 11:45:00'),(52,1,5,'2026-07-01','present','2026-06-09 11:46:17','2026-06-09 11:46:17'),(53,1,4,'2026-07-01','present','2026-06-09 11:46:17','2026-06-09 11:46:17'),(54,1,7,'2026-07-01','present','2026-06-09 11:46:17','2026-06-09 11:46:17'),(55,1,8,'2026-07-01','present','2026-06-09 11:46:17','2026-06-09 11:46:17'),(56,1,9,'2026-07-01','present','2026-06-09 11:46:17','2026-06-09 11:46:17'),(57,1,10,'2026-07-01','late','2026-06-09 11:46:17','2026-06-09 11:46:17'),(58,1,6,'2026-07-01','present','2026-06-09 11:46:17','2026-06-09 11:46:17'),(59,1,12,'2026-07-01','present','2026-06-09 11:46:17','2026-06-09 11:46:17');
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('university-management-system-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897','i:1;',1781169578),('university-management-system-cache-0ade7c2cf97f75d009975f4d720d1fa6c19f4897:timer','i:1781169578;',1781169578),('university-management-system-cache-356a192b7913b04c54574d18c28d46e6395428ab','i:1;',1781190417),('university-management-system-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1781190417;',1781190417),('university-management-system-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6','i:2;',1781208930),('university-management-system-cache-livewire-rate-limiter:16d36dff9abd246c67dfac3e63b993a169af77e6:timer','i:1781208930;',1781208930);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_schedules`
--

DROP TABLE IF EXISTS `class_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_class_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `subject_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `day_of_week` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_schedules_school_class_id_foreign` (`school_class_id`),
  KEY `class_schedules_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `class_schedules_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_schedules_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_schedules`
--

LOCK TABLES `class_schedules` WRITE;
/*!40000 ALTER TABLE `class_schedules` DISABLE KEYS */;
INSERT INTO `class_schedules` VALUES (1,1,6,'NET','Network Design','Monday','09:14:00','11:14:00','2026-06-02 19:14:58','2026-06-02 19:14:58'),(2,1,11,'WEB-01','Website Development','Friday','08:00:00','10:00:00','2026-06-04 06:34:16','2026-06-04 06:34:16'),(3,1,12,'MAD','Mobile Application Development','Saturday','09:00:00','11:00:00','2026-06-04 06:35:03','2026-06-04 06:35:03'),(4,2,13,'WEB-01','Website Development','Friday','14:00:00','17:00:00','2026-06-05 00:45:16','2026-06-05 00:51:09'),(5,8,6,'Py-01','python-01','Friday','14:00:00','16:00:00','2026-06-08 09:28:27','2026-06-08 09:28:27'),(6,8,12,'Math-01','Mathematics','Tuesday','13:30:00','15:30:00','2026-06-08 09:29:21','2026-06-08 09:29:21'),(7,10,6,'Bio-01','Biology Theory 1','Wednesday','17:30:00','20:30:00','2026-06-10 03:12:53','2026-06-10 03:12:53'),(8,1,11,'CG-01','Computer Graphic','Sunday','08:00:00','11:00:00','2026-06-11 01:46:17','2026-06-11 01:46:17');
/*!40000 ALTER TABLE `class_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `class_user`
--

DROP TABLE IF EXISTS `class_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `class_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `school_class_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `enrollment_type` enum('paid','scholarship') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `scholarship_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_paid` decimal(10,2) NOT NULL DEFAULT '0.00',
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by_manager_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `class_user_school_class_id_foreign` (`school_class_id`),
  KEY `class_user_user_id_foreign` (`user_id`),
  KEY `class_user_approved_by_manager_id_foreign` (`approved_by_manager_id`),
  CONSTRAINT `class_user_approved_by_manager_id_foreign` FOREIGN KEY (`approved_by_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `class_user_school_class_id_foreign` FOREIGN KEY (`school_class_id`) REFERENCES `school_classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `class_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `class_user`
--

LOCK TABLES `class_user` WRITE;
/*!40000 ALTER TABLE `class_user` DISABLE KEYS */;
INSERT INTO `class_user` VALUES (2,1,5,'paid',NULL,600.00,'approved',2,'2026-06-02 20:43:00','2026-06-03 10:34:41'),(3,1,4,'scholarship','50%',0.00,'approved',2,'2026-06-02 21:02:40','2026-06-02 21:03:13'),(4,1,7,'scholarship','100%',0.00,'approved',2,'2026-06-03 08:21:22','2026-06-03 10:36:14'),(5,1,5,'paid',NULL,300.00,'approved',2,'2026-06-03 10:34:16','2026-06-03 10:36:15'),(6,1,8,'scholarship','50%',0.00,'approved',2,'2026-06-03 10:35:08','2026-06-03 10:36:17'),(7,1,9,'scholarship','50%',0.00,'approved',2,'2026-06-03 10:35:29','2026-06-03 10:36:21'),(8,1,10,'scholarship','100%',0.00,'approved',2,'2026-06-03 10:35:44','2026-06-03 10:36:19'),(20,9,4,'paid',NULL,500.00,'approved',2,'2026-06-10 11:27:28','2026-06-11 01:54:19'),(21,1,10,'scholarship','50%',0.00,'approved',2,'2026-06-11 01:45:01','2026-06-11 01:54:26');
/*!40000 ALTER TABLE `class_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custom_activity_logs`
--

DROP TABLE IF EXISTS `custom_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `custom_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `actor_role_context` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_performed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target_resource_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logged_payload_summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `custom_activity_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `custom_activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custom_activity_logs`
--

LOCK TABLES `custom_activity_logs` WRITE;
/*!40000 ALTER TABLE `custom_activity_logs` DISABLE KEYS */;
INSERT INTO `custom_activity_logs` VALUES (1,1,'admin','updated','User','User admin (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 4).','2026-06-04 09:22:35','2026-06-04 09:22:35'),(2,12,'teacher','created','LessonMaterial','User lolo (Teacher) successfully performed a baseline [created] structural change on target LessonMaterial entry (ID: 6).','2026-06-04 09:35:07','2026-06-04 09:35:07'),(3,3,'study_office','updated','User','User caca (Study office) successfully performed a baseline [updated] structural change on target User entry (ID: 3).','2026-06-04 11:12:48','2026-06-04 11:12:48'),(4,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 3).','2026-06-04 15:20:07','2026-06-04 15:20:07'),(5,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 3).','2026-06-04 15:20:07','2026-06-04 15:20:07'),(6,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 4).','2026-06-04 15:21:03','2026-06-04 15:21:03'),(7,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 4).','2026-06-04 15:21:03','2026-06-04 15:21:03'),(8,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-04 20:44:00','2026-06-04 20:44:00'),(9,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-04 20:44:03','2026-06-04 20:44:03'),(10,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-04 21:40:59','2026-06-04 21:40:59'),(11,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-04 21:41:01','2026-06-04 21:41:01'),(12,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-04 21:41:03','2026-06-04 21:41:03'),(13,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-04 23:17:17','2026-06-04 23:17:17'),(14,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-04 23:17:25','2026-06-04 23:17:25'),(15,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-05 00:22:22','2026-06-05 00:22:22'),(16,2,'faculty_manager','created','ClassSchedule','User nana (Faculty manager) successfully performed a baseline [created] structural change on target ClassSchedule entry (ID: 4).','2026-06-05 00:45:16','2026-06-05 00:45:16'),(17,2,'faculty_manager','created','Subject','User nana (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 4).','2026-06-05 00:46:02','2026-06-05 00:46:02'),(18,2,'faculty_manager','created','Subject','User nana (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 4).','2026-06-05 00:46:02','2026-06-05 00:46:02'),(19,10,'student','updated','User','User bobo (Student) successfully performed a baseline [updated] structural change on target User entry (ID: 10).','2026-06-05 00:48:03','2026-06-05 00:48:03'),(20,1,'admin','created','User','User admin (Admin) successfully performed a baseline [created] structural change on target User entry (ID: 13).','2026-06-05 00:50:45','2026-06-05 00:50:45'),(21,2,'faculty_manager','updated','ClassSchedule','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target ClassSchedule entry (ID: 4).','2026-06-05 00:51:09','2026-06-05 00:51:09'),(22,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 2).','2026-06-05 00:51:40','2026-06-05 00:51:40'),(23,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-05 00:52:00','2026-06-05 00:52:00'),(24,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-05 01:14:21','2026-06-05 01:14:21'),(25,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-05 01:14:42','2026-06-05 01:14:42'),(26,1,'admin','updated','User','User admin (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 1).','2026-06-05 02:46:18','2026-06-05 02:46:18'),(27,1,'admin','updated','User','User admin (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 1).','2026-06-05 05:00:59','2026-06-05 05:00:59'),(28,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 1).','2026-06-05 22:39:31','2026-06-05 22:39:31'),(29,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 43).','2026-06-08 08:46:12','2026-06-08 08:46:12'),(30,6,'teacher','updated','Attendance','User momo (Teacher) successfully performed a baseline [updated] structural change on target Attendance entry (ID: 26).','2026-06-08 08:46:12','2026-06-08 08:46:12'),(31,6,'teacher','updated','Attendance','User momo (Teacher) successfully performed a baseline [updated] structural change on target Attendance entry (ID: 27).','2026-06-08 08:46:12','2026-06-08 08:46:12'),(32,6,'teacher','updated','Attendance','User momo (Teacher) successfully performed a baseline [updated] structural change on target Attendance entry (ID: 43).','2026-06-08 08:46:12','2026-06-08 08:46:12'),(33,6,'teacher','updated','Attendance','User momo (Teacher) successfully performed a baseline [updated] structural change on target Attendance entry (ID: 30).','2026-06-08 08:46:12','2026-06-08 08:46:12'),(34,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 44).','2026-06-08 08:50:06','2026-06-08 08:50:06'),(35,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 45).','2026-06-08 08:50:06','2026-06-08 08:50:06'),(36,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 46).','2026-06-08 08:50:06','2026-06-08 08:50:06'),(37,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 47).','2026-06-08 08:50:06','2026-06-08 08:50:06'),(38,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 48).','2026-06-08 08:50:06','2026-06-08 08:50:06'),(39,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 49).','2026-06-08 08:50:06','2026-06-08 08:50:06'),(40,6,'teacher','created','TaskAssessment','User momo (Teacher) successfully performed a baseline [created] structural change on target TaskAssessment entry (ID: 11).','2026-06-08 08:52:58','2026-06-08 08:52:58'),(41,10,'student','created','AssessmentSubmission','User bobo (Student) successfully performed a baseline [created] structural change on target AssessmentSubmission entry (ID: 7).','2026-06-08 08:53:51','2026-06-08 08:53:51'),(42,6,'teacher','created','LessonMaterial','User momo (Teacher) successfully performed a baseline [created] structural change on target LessonMaterial entry (ID: 7).','2026-06-08 08:56:25','2026-06-08 08:56:25'),(43,1,'admin','created','User','User admi (Admin) successfully performed a baseline [created] structural change on target User entry (ID: 14).','2026-06-08 09:08:17','2026-06-08 09:08:17'),(44,1,'admin','created','User','User admi (Admin) successfully performed a baseline [created] structural change on target User entry (ID: 15).','2026-06-08 09:09:40','2026-06-08 09:09:40'),(45,1,'admin','created','Faculty','User admi (Admin) successfully performed a baseline [created] structural change on target Faculty entry (ID: 2).','2026-06-08 09:10:29','2026-06-08 09:10:29'),(46,1,'admin','created','Faculty','User admi (Admin) successfully performed a baseline [created] structural change on target Faculty entry (ID: 3).','2026-06-08 09:11:11','2026-06-08 09:11:11'),(47,1,'admin','created','Department','User admi (Admin) successfully performed a baseline [created] structural change on target Department entry (ID: 6).','2026-06-08 09:15:47','2026-06-08 09:15:47'),(48,1,'admin','created','AcademicStructure','User admi (Admin) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 6).','2026-06-08 09:15:47','2026-06-08 09:15:47'),(49,1,'admin','created','Department','User admi (Admin) successfully performed a baseline [created] structural change on target Department entry (ID: 7).','2026-06-08 09:16:02','2026-06-08 09:16:02'),(50,1,'admin','created','AcademicStructure','User admi (Admin) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 7).','2026-06-08 09:16:02','2026-06-08 09:16:02'),(51,1,'admin','created','Department','User admi (Admin) successfully performed a baseline [created] structural change on target Department entry (ID: 8).','2026-06-08 09:16:05','2026-06-08 09:16:05'),(52,1,'admin','created','AcademicStructure','User admi (Admin) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 8).','2026-06-08 09:16:05','2026-06-08 09:16:05'),(53,1,'admin','created','Department','User admi (Admin) successfully performed a baseline [created] structural change on target Department entry (ID: 9).','2026-06-08 09:16:31','2026-06-08 09:16:31'),(54,1,'admin','created','AcademicStructure','User admi (Admin) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 9).','2026-06-08 09:16:31','2026-06-08 09:16:31'),(55,14,'faculty_manager','updated','User','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target User entry (ID: 14).','2026-06-08 09:17:45','2026-06-08 09:17:45'),(56,14,'faculty_manager','created','Department','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target Department entry (ID: 10).','2026-06-08 09:20:32','2026-06-08 09:20:32'),(57,14,'faculty_manager','created','AcademicStructure','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 10).','2026-06-08 09:20:32','2026-06-08 09:20:32'),(58,14,'faculty_manager','created','SchoolClass','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target SchoolClass entry (ID: 8).','2026-06-08 09:20:32','2026-06-08 09:20:32'),(59,14,'faculty_manager','created','AcademicStructure','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 11).','2026-06-08 09:20:32','2026-06-08 09:20:32'),(60,14,'faculty_manager','created','SchoolClass','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target SchoolClass entry (ID: 9).','2026-06-08 09:20:32','2026-06-08 09:20:32'),(61,14,'faculty_manager','updated','Department','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target Department entry (ID: 10).','2026-06-08 09:20:56','2026-06-08 09:20:56'),(62,14,'faculty_manager','deleted','Department','User mimi (Faculty manager) successfully performed a baseline [deleted] structural change on target Department entry (ID: 7).','2026-06-08 09:21:11','2026-06-08 09:21:11'),(63,14,'faculty_manager','deleted','Department','User mimi (Faculty manager) successfully performed a baseline [deleted] structural change on target Department entry (ID: 8).','2026-06-08 09:21:19','2026-06-08 09:21:19'),(64,14,'faculty_manager','deleted','Department','User mimi (Faculty manager) successfully performed a baseline [deleted] structural change on target Department entry (ID: 9).','2026-06-08 09:21:26','2026-06-08 09:21:26'),(65,14,'faculty_manager','deleted','Department','User mimi (Faculty manager) successfully performed a baseline [deleted] structural change on target Department entry (ID: 6).','2026-06-08 09:21:34','2026-06-08 09:21:34'),(66,14,'faculty_manager','updated','ClassUser','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 9).','2026-06-08 09:22:08','2026-06-08 09:22:08'),(67,14,'faculty_manager','updated','ClassUser','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 9).','2026-06-08 09:22:08','2026-06-08 09:22:08'),(68,14,'faculty_manager','updated','ClassUser','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 10).','2026-06-08 09:22:10','2026-06-08 09:22:10'),(69,14,'faculty_manager','updated','ClassUser','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 10).','2026-06-08 09:22:10','2026-06-08 09:22:10'),(70,14,'faculty_manager','updated','SubjectFinalGrade','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 11).','2026-06-08 09:22:49','2026-06-08 09:22:49'),(71,14,'faculty_manager','updated','SubjectFinalGrade','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 11).','2026-06-08 09:22:49','2026-06-08 09:22:49'),(72,14,'faculty_manager','created','Subject','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 5).','2026-06-08 09:24:48','2026-06-08 09:24:48'),(73,14,'faculty_manager','created','Subject','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 5).','2026-06-08 09:24:48','2026-06-08 09:24:48'),(74,14,'faculty_manager','created','Subject','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 6).','2026-06-08 09:25:29','2026-06-08 09:25:29'),(75,14,'faculty_manager','created','Subject','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 6).','2026-06-08 09:25:29','2026-06-08 09:25:29'),(76,14,'faculty_manager','created','ClassSchedule','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target ClassSchedule entry (ID: 5).','2026-06-08 09:28:27','2026-06-08 09:28:27'),(77,14,'faculty_manager','created','ClassSchedule','User mimi (Faculty manager) successfully performed a baseline [created] structural change on target ClassSchedule entry (ID: 6).','2026-06-08 09:29:21','2026-06-08 09:29:21'),(78,14,'faculty_manager','updated','SchoolClass','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 8).','2026-06-08 09:29:42','2026-06-08 09:29:42'),(79,14,'faculty_manager','updated','SchoolClass','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 8).','2026-06-08 09:30:41','2026-06-08 09:30:41'),(80,14,'faculty_manager','updated','SchoolClass','User mimi (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 8).','2026-06-08 09:31:14','2026-06-08 09:31:14'),(81,6,'teacher','updated','User','User momo (Teacher) successfully performed a baseline [updated] structural change on target User entry (ID: 6).','2026-06-08 09:31:49','2026-06-08 09:31:49'),(82,11,'teacher','updated','User','User TaTa (Teacher) successfully performed a baseline [updated] structural change on target User entry (ID: 11).','2026-06-08 09:33:02','2026-06-08 09:33:02'),(83,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 13).','2026-06-08 09:35:33','2026-06-08 09:35:33'),(84,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 14).','2026-06-08 09:35:33','2026-06-08 09:35:33'),(85,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 15).','2026-06-08 09:35:33','2026-06-08 09:35:33'),(86,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 16).','2026-06-08 09:35:33','2026-06-08 09:35:33'),(87,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 17).','2026-06-08 09:35:33','2026-06-08 09:35:33'),(88,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 18).','2026-06-08 09:35:33','2026-06-08 09:35:33'),(89,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 19).','2026-06-08 09:35:33','2026-06-08 09:35:33'),(90,11,'teacher','created','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 20).','2026-06-08 09:35:34','2026-06-08 09:35:34'),(91,11,'teacher','updated','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 13).','2026-06-08 09:35:44','2026-06-08 09:35:44'),(92,11,'teacher','updated','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 13).','2026-06-08 09:35:50','2026-06-08 09:35:50'),(93,11,'teacher','updated','SubjectFinalGrade','User TaTa (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 14).','2026-06-08 09:36:02','2026-06-08 09:36:02'),(94,11,'teacher','updated','User','User TaTa (Teacher) successfully performed a baseline [updated] structural change on target User entry (ID: 11).','2026-06-08 09:38:18','2026-06-08 09:38:18'),(95,1,'admin','created','User','User admi (Admin) successfully performed a baseline [created] structural change on target User entry (ID: 16).','2026-06-08 09:40:56','2026-06-08 09:40:56'),(96,16,'study_office','updated','User','User hoho (Study office) successfully performed a baseline [updated] structural change on target User entry (ID: 16).','2026-06-08 09:41:33','2026-06-08 09:41:33'),(97,16,'study_office','updated','User','User hoho (Study office) successfully performed a baseline [updated] structural change on target User entry (ID: 16).','2026-06-08 09:52:52','2026-06-08 09:52:52'),(98,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-08 09:54:30','2026-06-08 09:54:30'),(99,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-08 09:54:35','2026-06-08 09:54:35'),(100,6,'teacher','created','LessonMaterial','User momo (Teacher) successfully performed a baseline [created] structural change on target LessonMaterial entry (ID: 8).','2026-06-09 04:21:48','2026-06-09 04:21:48'),(101,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-09 04:45:12','2026-06-09 04:45:12'),(102,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-09 04:45:12','2026-06-09 04:45:12'),(103,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 6).','2026-06-09 04:45:13','2026-06-09 04:45:13'),(104,6,'teacher','created','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 21).','2026-06-09 04:45:15','2026-06-09 04:45:15'),(105,6,'teacher','created','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 22).','2026-06-09 04:45:17','2026-06-09 04:45:17'),(106,6,'teacher','created','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 23).','2026-06-09 04:45:17','2026-06-09 04:45:17'),(107,6,'teacher','created','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [created] structural change on target SubjectFinalGrade entry (ID: 24).','2026-06-09 04:45:17','2026-06-09 04:45:17'),(108,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 24).','2026-06-09 04:45:53','2026-06-09 04:45:53'),(109,6,'teacher','updated','AssessmentSubmission','User momo (Teacher) successfully performed a baseline [updated] structural change on target AssessmentSubmission entry (ID: 2).','2026-06-09 04:48:22','2026-06-09 04:48:22'),(110,6,'teacher','updated','AssessmentSubmission','User momo (Teacher) successfully performed a baseline [updated] structural change on target AssessmentSubmission entry (ID: 2).','2026-06-09 04:48:51','2026-06-09 04:48:51'),(111,6,'teacher','updated','AssessmentSubmission','User momo (Teacher) successfully performed a baseline [updated] structural change on target AssessmentSubmission entry (ID: 2).','2026-06-09 04:49:01','2026-06-09 04:49:01'),(112,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 6).','2026-06-09 04:49:17','2026-06-09 04:49:17'),(113,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 24).','2026-06-09 04:49:17','2026-06-09 04:49:17'),(114,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 9).','2026-06-09 10:06:49','2026-06-09 10:06:49'),(115,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 2).','2026-06-09 10:07:21','2026-06-09 10:07:21'),(116,6,'teacher','created','LessonMaterial','User momo (Teacher) successfully performed a baseline [created] structural change on target LessonMaterial entry (ID: 9).','2026-06-09 10:47:46','2026-06-09 10:47:46'),(117,6,'teacher','created','LessonMaterial','User momo (Teacher) successfully performed a baseline [created] structural change on target LessonMaterial entry (ID: 10).','2026-06-09 10:58:04','2026-06-09 10:58:04'),(118,6,'teacher','updated','LessonMaterial','User momo (Teacher) successfully performed a baseline [updated] structural change on target LessonMaterial entry (ID: 10).','2026-06-09 11:05:47','2026-06-09 11:05:47'),(119,6,'teacher','updated','Attendance','User momo (Teacher) successfully performed a baseline [updated] structural change on target Attendance entry (ID: 37).','2026-06-09 11:45:00','2026-06-09 11:45:00'),(120,6,'teacher','updated','Attendance','User momo (Teacher) successfully performed a baseline [updated] structural change on target Attendance entry (ID: 42).','2026-06-09 11:45:00','2026-06-09 11:45:00'),(121,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 50).','2026-06-09 11:45:00','2026-06-09 11:45:00'),(122,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 51).','2026-06-09 11:45:00','2026-06-09 11:45:00'),(123,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 52).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(124,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 53).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(125,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 54).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(126,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 55).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(127,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 56).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(128,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 57).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(129,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 58).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(130,6,'teacher','created','Attendance','User momo (Teacher) successfully performed a baseline [created] structural change on target Attendance entry (ID: 59).','2026-06-09 11:46:17','2026-06-09 11:46:17'),(131,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 8).','2026-06-10 02:29:17','2026-06-10 02:29:17'),(132,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 8).','2026-06-10 02:29:38','2026-06-10 02:29:38'),(133,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 9).','2026-06-10 02:29:56','2026-06-10 02:29:56'),(134,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-10 02:55:11','2026-06-10 02:55:11'),(135,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 1).','2026-06-10 02:55:26','2026-06-10 02:55:26'),(136,2,'faculty_manager','created','Department','User nana (Faculty manager) successfully performed a baseline [created] structural change on target Department entry (ID: 11).','2026-06-10 02:59:53','2026-06-10 02:59:53'),(137,2,'faculty_manager','created','AcademicStructure','User nana (Faculty manager) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 12).','2026-06-10 02:59:53','2026-06-10 02:59:53'),(138,2,'faculty_manager','created','Department','User nana (Faculty manager) successfully performed a baseline [created] structural change on target Department entry (ID: 12).','2026-06-10 03:00:12','2026-06-10 03:00:12'),(139,2,'faculty_manager','created','AcademicStructure','User nana (Faculty manager) successfully performed a baseline [created] structural change on target AcademicStructure entry (ID: 13).','2026-06-10 03:00:13','2026-06-10 03:00:13'),(140,2,'faculty_manager','created','SchoolClass','User nana (Faculty manager) successfully performed a baseline [created] structural change on target SchoolClass entry (ID: 10).','2026-06-10 03:09:55','2026-06-10 03:09:55'),(141,2,'faculty_manager','created','SchoolClass','User nana (Faculty manager) successfully performed a baseline [created] structural change on target SchoolClass entry (ID: 11).','2026-06-10 03:09:55','2026-06-10 03:09:55'),(142,2,'faculty_manager','deleted','Department','User nana (Faculty manager) successfully performed a baseline [deleted] structural change on target Department entry (ID: 12).','2026-06-10 03:10:10','2026-06-10 03:10:10'),(143,2,'faculty_manager','created','Subject','User nana (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 7).','2026-06-10 03:11:08','2026-06-10 03:11:08'),(144,2,'faculty_manager','created','Subject','User nana (Faculty manager) successfully performed a baseline [created] structural change on target Subject entry (ID: 7).','2026-06-10 03:11:08','2026-06-10 03:11:08'),(145,2,'faculty_manager','created','ClassSchedule','User nana (Faculty manager) successfully performed a baseline [created] structural change on target ClassSchedule entry (ID: 7).','2026-06-10 03:12:53','2026-06-10 03:12:53'),(146,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 10).','2026-06-10 03:13:13','2026-06-10 03:13:13'),(147,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 03:24:20','2026-06-10 03:24:20'),(148,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 03:24:20','2026-06-10 03:24:20'),(149,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 11).','2026-06-10 03:24:25','2026-06-10 03:24:25'),(150,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 11).','2026-06-10 03:24:25','2026-06-10 03:24:25'),(151,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 03:24:35','2026-06-10 03:24:35'),(152,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 03:24:37','2026-06-10 03:24:37'),(153,16,'study_office','updated','SchoolClass','User hoho (Study office) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 2).','2026-06-10 03:53:29','2026-06-10 03:53:29'),(154,16,'study_office','updated','SchoolClass','User hoho (Study office) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 2).','2026-06-10 03:53:37','2026-06-10 03:53:37'),(155,16,'study_office','updated','SchoolClass','User hoho (Study office) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 2).','2026-06-10 04:07:24','2026-06-10 04:07:24'),(156,16,'study_office','updated','SubjectFinalGrade','User hoho (Study office) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 10:55:54','2026-06-10 10:55:54'),(157,16,'study_office','updated','SubjectFinalGrade','User hoho (Study office) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 11).','2026-06-10 10:55:57','2026-06-10 10:55:57'),(158,16,'study_office','updated','SubjectFinalGrade','User hoho (Study office) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 10:58:23','2026-06-10 10:58:23'),(159,16,'study_office','updated','SubjectFinalGrade','User hoho (Study office) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 10:58:26','2026-06-10 10:58:26'),(160,16,'study_office','updated','SubjectFinalGrade','User hoho (Study office) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 11:06:03','2026-06-10 11:06:03'),(161,16,'study_office','updated','SubjectFinalGrade','User hoho (Study office) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-10 11:06:10','2026-06-10 11:06:10'),(162,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 11).','2026-06-10 11:24:37','2026-06-10 11:24:37'),(163,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 13).','2026-06-10 11:24:53','2026-06-10 11:24:53'),(164,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 14).','2026-06-10 11:24:58','2026-06-10 11:24:58'),(165,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 15).','2026-06-10 11:25:05','2026-06-10 11:25:05'),(166,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 16).','2026-06-10 11:25:23','2026-06-10 11:25:23'),(167,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 17).','2026-06-10 11:25:29','2026-06-10 11:25:29'),(168,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 18).','2026-06-10 11:25:36','2026-06-10 11:25:36'),(169,16,'study_office','deleted','ClassUser','User hoho (Study office) successfully performed a baseline [deleted] structural change on target ClassUser entry (ID: 19).','2026-06-10 11:25:45','2026-06-10 11:25:45'),(170,16,'study_office','created','ClassUser','User hoho (Study office) successfully performed a baseline [created] structural change on target ClassUser entry (ID: 20).','2026-06-10 11:27:28','2026-06-10 11:27:28'),(171,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 5).','2026-06-10 11:40:03','2026-06-10 11:40:03'),(172,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 5).','2026-06-10 11:40:03','2026-06-10 11:40:03'),(173,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 6).','2026-06-10 11:40:47','2026-06-10 11:40:47'),(174,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 6).','2026-06-10 11:40:47','2026-06-10 11:40:47'),(175,16,'study_office','created','SystemNotification','User hoho (Study office) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 7).','2026-06-10 11:43:28','2026-06-10 11:43:28'),(176,16,'study_office','created','SystemNotification','User hoho (Study office) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 7).','2026-06-10 11:43:28','2026-06-10 11:43:28'),(177,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 8).','2026-06-10 11:44:06','2026-06-10 11:44:06'),(178,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 8).','2026-06-10 11:44:06','2026-06-10 11:44:06'),(179,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 9).','2026-06-10 12:38:16','2026-06-10 12:38:16'),(180,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 9).','2026-06-10 12:38:16','2026-06-10 12:38:16'),(181,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 10).','2026-06-10 12:39:39','2026-06-10 12:39:39'),(182,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 10).','2026-06-10 12:39:39','2026-06-10 12:39:39'),(183,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 11).','2026-06-10 12:40:49','2026-06-10 12:40:49'),(184,1,'admin','created','SystemNotification','User admi (Admin) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 11).','2026-06-10 12:40:49','2026-06-10 12:40:49'),(185,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 12).','2026-06-10 12:47:57','2026-06-10 12:47:57'),(186,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 12).','2026-06-10 12:47:57','2026-06-10 12:47:57'),(187,16,'study_office','created','SystemNotification','User hoho (Study office) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 13).','2026-06-10 12:48:35','2026-06-10 12:48:35'),(188,16,'study_office','created','SystemNotification','User hoho (Study office) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 13).','2026-06-10 12:48:35','2026-06-10 12:48:35'),(189,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 14).','2026-06-10 12:58:51','2026-06-10 12:58:51'),(190,10,'student','created','SystemNotification','User bobo (Student) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 14).','2026-06-10 12:58:51','2026-06-10 12:58:51'),(191,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 1).','2026-06-10 20:49:40','2026-06-10 20:49:40'),(192,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 2).','2026-06-10 20:50:46','2026-06-10 20:50:46'),(193,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 2).','2026-06-10 20:51:48','2026-06-10 20:51:48'),(194,10,'student','updated','User','User bobo (Student) successfully performed a baseline [updated] structural change on target User entry (ID: 10).','2026-06-10 22:28:29','2026-06-10 22:28:29'),(195,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 1).','2026-06-10 22:34:47','2026-06-10 22:34:47'),(196,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 10).','2026-06-10 23:19:07','2026-06-10 23:19:07'),(197,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 2).','2026-06-10 23:19:26','2026-06-10 23:19:26'),(198,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 10).','2026-06-10 23:19:32','2026-06-10 23:19:32'),(199,2,'faculty_manager','updated','SchoolClass','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SchoolClass entry (ID: 11).','2026-06-10 23:19:51','2026-06-10 23:19:51'),(200,6,'teacher','created','SystemNotification','User momo (Teacher) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 15).','2026-06-11 00:28:53','2026-06-11 00:28:53'),(201,6,'teacher','created','SystemNotification','User momo (Teacher) successfully performed a baseline [created] structural change on target SystemNotification entry (ID: 15).','2026-06-11 00:28:53','2026-06-11 00:28:53'),(202,16,'study_office','created','ClassUser','User hoho (Study office) successfully performed a baseline [created] structural change on target ClassUser entry (ID: 21).','2026-06-11 01:45:01','2026-06-11 01:45:01'),(203,2,'faculty_manager','created','ClassSchedule','User nana (Faculty manager) successfully performed a baseline [created] structural change on target ClassSchedule entry (ID: 8).','2026-06-11 01:46:17','2026-06-11 01:46:17'),(204,10,'student','updated','User','User bobo (Student) successfully performed a baseline [updated] structural change on target User entry (ID: 10).','2026-06-11 01:47:25','2026-06-11 01:47:25'),(205,4,'student','updated','User','User lala (Student) successfully performed a baseline [updated] structural change on target User entry (ID: 4).','2026-06-11 01:47:42','2026-06-11 01:47:42'),(206,4,'student','created','AssessmentSubmission','User lala (Student) successfully performed a baseline [created] structural change on target AssessmentSubmission entry (ID: 8).','2026-06-11 01:50:37','2026-06-11 01:50:37'),(207,4,'student','updated','User','User lala (Student) successfully performed a baseline [updated] structural change on target User entry (ID: 4).','2026-06-11 01:53:22','2026-06-11 01:53:22'),(208,9,'student','updated','User','User lulu (Student) successfully performed a baseline [updated] structural change on target User entry (ID: 9).','2026-06-11 01:53:39','2026-06-11 01:53:39'),(209,2,'faculty_manager','updated','ClassUser','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 20).','2026-06-11 01:54:19','2026-06-11 01:54:19'),(210,2,'faculty_manager','updated','ClassUser','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 20).','2026-06-11 01:54:19','2026-06-11 01:54:19'),(211,2,'faculty_manager','updated','ClassUser','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 21).','2026-06-11 01:54:26','2026-06-11 01:54:26'),(212,2,'faculty_manager','updated','ClassUser','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target ClassUser entry (ID: 21).','2026-06-11 01:54:26','2026-06-11 01:54:26'),(213,6,'teacher','created','TaskAssessment','User momo (Teacher) successfully performed a baseline [created] structural change on target TaskAssessment entry (ID: 12).','2026-06-11 01:58:36','2026-06-11 01:58:36'),(214,9,'student','created','AssessmentSubmission','User lulu (Student) successfully performed a baseline [created] structural change on target AssessmentSubmission entry (ID: 9).','2026-06-11 01:59:15','2026-06-11 01:59:15'),(215,6,'teacher','updated','AssessmentSubmission','User momo (Teacher) successfully performed a baseline [updated] structural change on target AssessmentSubmission entry (ID: 9).','2026-06-11 02:00:51','2026-06-11 02:00:51'),(216,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 5).','2026-06-11 02:03:14','2026-06-11 02:03:14'),(217,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 5).','2026-06-11 02:03:20','2026-06-11 02:03:20'),(218,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 5).','2026-06-11 02:03:21','2026-06-11 02:03:21'),(219,16,'study_office','updated','SubjectFinalGrade','User hoho (Study office) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 5).','2026-06-11 02:03:33','2026-06-11 02:03:33'),(220,6,'teacher','updated','TaskAssessment','User momo (Teacher) successfully performed a baseline [updated] structural change on target TaskAssessment entry (ID: 11).','2026-06-11 02:14:46','2026-06-11 02:14:46'),(221,9,'student','created','AssessmentSubmission','User lulu (Student) successfully performed a baseline [created] structural change on target AssessmentSubmission entry (ID: 10).','2026-06-11 02:15:10','2026-06-11 02:15:10'),(222,6,'teacher','deleted','TaskAssessment','User momo (Teacher) successfully performed a baseline [deleted] structural change on target TaskAssessment entry (ID: 7).','2026-06-11 02:15:56','2026-06-11 02:15:56'),(223,6,'teacher','deleted','TaskAssessment','User momo (Teacher) successfully performed a baseline [deleted] structural change on target TaskAssessment entry (ID: 6).','2026-06-11 02:16:03','2026-06-11 02:16:03'),(224,6,'teacher','deleted','TaskAssessment','User momo (Teacher) successfully performed a baseline [deleted] structural change on target TaskAssessment entry (ID: 4).','2026-06-11 02:16:09','2026-06-11 02:16:09'),(225,6,'teacher','deleted','TaskAssessment','User momo (Teacher) successfully performed a baseline [deleted] structural change on target TaskAssessment entry (ID: 5).','2026-06-11 02:16:14','2026-06-11 02:16:14'),(226,6,'teacher','deleted','TaskAssessment','User momo (Teacher) successfully performed a baseline [deleted] structural change on target TaskAssessment entry (ID: 3).','2026-06-11 02:16:20','2026-06-11 02:16:20'),(227,6,'teacher','updated','TaskAssessment','User momo (Teacher) successfully performed a baseline [updated] structural change on target TaskAssessment entry (ID: 2).','2026-06-11 02:17:06','2026-06-11 02:17:06'),(228,9,'student','created','AssessmentSubmission','User lulu (Student) successfully performed a baseline [created] structural change on target AssessmentSubmission entry (ID: 11).','2026-06-11 02:17:36','2026-06-11 02:17:36'),(229,6,'teacher','updated','TaskAssessment','User momo (Teacher) successfully performed a baseline [updated] structural change on target TaskAssessment entry (ID: 1).','2026-06-11 02:18:13','2026-06-11 02:18:13'),(230,9,'student','created','AssessmentSubmission','User lulu (Student) successfully performed a baseline [created] structural change on target AssessmentSubmission entry (ID: 12).','2026-06-11 02:18:41','2026-06-11 02:18:41'),(231,6,'teacher','updated','AssessmentSubmission','User momo (Teacher) successfully performed a baseline [updated] structural change on target AssessmentSubmission entry (ID: 12).','2026-06-11 02:20:43','2026-06-11 02:20:43'),(232,6,'teacher','updated','AssessmentSubmission','User momo (Teacher) successfully performed a baseline [updated] structural change on target AssessmentSubmission entry (ID: 12).','2026-06-11 02:20:56','2026-06-11 02:20:56'),(233,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:53:07','2026-06-11 02:53:07'),(234,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 02:57:48','2026-06-11 02:57:48'),(235,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 02:57:55','2026-06-11 02:57:55'),(236,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 02:57:55','2026-06-11 02:57:55'),(237,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 4).','2026-06-11 02:58:03','2026-06-11 02:58:03'),(238,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 4).','2026-06-11 02:58:03','2026-06-11 02:58:03'),(239,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:58:25','2026-06-11 02:58:25'),(240,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:58:25','2026-06-11 02:58:25'),(241,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:58:34','2026-06-11 02:58:34'),(242,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:58:39','2026-06-11 02:58:39'),(243,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:58:52','2026-06-11 02:58:52'),(244,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:58:52','2026-06-11 02:58:52'),(245,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:59:01','2026-06-11 02:59:01'),(246,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:59:13','2026-06-11 02:59:13'),(247,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:59:13','2026-06-11 02:59:13'),(248,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:59:23','2026-06-11 02:59:23'),(249,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 02:59:23','2026-06-11 02:59:23'),(250,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 03:00:19','2026-06-11 03:00:19'),(251,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 03:00:26','2026-06-11 03:00:26'),(252,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 03:00:33','2026-06-11 03:00:33'),(253,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 07:48:10','2026-06-11 07:48:10'),(254,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 07:51:23','2026-06-11 07:51:23'),(255,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 07:51:33','2026-06-11 07:51:33'),(256,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 07:51:33','2026-06-11 07:51:33'),(257,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 21).','2026-06-11 07:57:47','2026-06-11 07:57:47'),(258,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 21).','2026-06-11 07:57:47','2026-06-11 07:57:47'),(259,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 22).','2026-06-11 07:57:53','2026-06-11 07:57:53'),(260,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 23).','2026-06-11 07:58:10','2026-06-11 07:58:10'),(261,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 23).','2026-06-11 07:58:10','2026-06-11 07:58:10'),(262,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 24).','2026-06-11 07:58:25','2026-06-11 07:58:25'),(263,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 24).','2026-06-11 07:58:25','2026-06-11 07:58:25'),(264,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 24).','2026-06-11 07:58:32','2026-06-11 07:58:32'),(265,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 24).','2026-06-11 07:58:32','2026-06-11 07:58:32'),(266,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-11 07:59:10','2026-06-11 07:59:10'),(267,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 2).','2026-06-11 07:59:10','2026-06-11 07:59:10'),(268,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 5).','2026-06-11 07:59:13','2026-06-11 07:59:13'),(269,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 5).','2026-06-11 07:59:13','2026-06-11 07:59:13'),(270,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 6).','2026-06-11 07:59:15','2026-06-11 07:59:15'),(271,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 6).','2026-06-11 07:59:15','2026-06-11 07:59:15'),(272,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 11).','2026-06-11 07:59:15','2026-06-11 07:59:15'),(273,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 11).','2026-06-11 07:59:15','2026-06-11 07:59:15'),(274,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 12).','2026-06-11 07:59:18','2026-06-11 07:59:18'),(275,2,'faculty_manager','updated','SubjectFinalGrade','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 12).','2026-06-11 07:59:18','2026-06-11 07:59:18'),(276,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 1).','2026-06-11 08:01:25','2026-06-11 08:01:25'),(277,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 3).','2026-06-11 08:01:25','2026-06-11 08:01:25'),(278,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 4).','2026-06-11 08:01:25','2026-06-11 08:01:25'),(279,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 5).','2026-06-11 08:01:25','2026-06-11 08:01:25'),(280,6,'teacher','updated','SubjectFinalGrade','User momo (Teacher) successfully performed a baseline [updated] structural change on target SubjectFinalGrade entry (ID: 6).','2026-06-11 08:01:25','2026-06-11 08:01:25'),(281,16,'study_office','updated','User','User hoho (Study office) successfully performed a baseline [updated] structural change on target User entry (ID: 16).','2026-06-11 08:04:27','2026-06-11 08:04:27'),(282,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 3).','2026-06-11 08:06:05','2026-06-11 08:06:05'),(283,3,'study_office','updated','User','User caca (Study office) successfully performed a baseline [updated] structural change on target User entry (ID: 3).','2026-06-11 08:06:29','2026-06-11 08:06:29'),(284,1,'admin','updated','Department','User admi (Admin) successfully performed a baseline [updated] structural change on target Department entry (ID: 1).','2026-06-11 11:25:20','2026-06-11 11:25:20'),(285,1,'admin','updated','Department','User admi (Admin) successfully performed a baseline [updated] structural change on target Department entry (ID: 10).','2026-06-11 11:25:32','2026-06-11 11:25:32'),(286,1,'admin','updated','Department','User admi (Admin) successfully performed a baseline [updated] structural change on target Department entry (ID: 11).','2026-06-11 11:26:50','2026-06-11 11:26:50'),(287,9,'student','updated','User','User lulu (Student) successfully performed a baseline [updated] structural change on target User entry (ID: 9).','2026-06-11 13:12:41','2026-06-11 13:12:41'),(288,2,'faculty_manager','updated','User','User nana (Faculty manager) successfully performed a baseline [updated] structural change on target User entry (ID: 2).','2026-06-11 13:14:01','2026-06-11 13:14:01'),(289,3,'study_office','updated','User','User caca (Study office) successfully performed a baseline [updated] structural change on target User entry (ID: 3).','2026-06-11 13:14:23','2026-06-11 13:14:23'),(290,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 1).','2026-06-11 13:15:04','2026-06-11 13:15:04'),(291,3,'study_office','updated','User','User caca (Study office) successfully performed a baseline [updated] structural change on target User entry (ID: 3).','2026-06-11 13:19:57','2026-06-11 13:19:57'),(292,1,'admin','updated','User','User admi (Admin) successfully performed a baseline [updated] structural change on target User entry (ID: 1).','2026-06-12 04:06:35','2026-06-12 04:06:35');
/*!40000 ALTER TABLE `custom_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `faculty_id` bigint unsigned NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_faculty_id_foreign` (`faculty_id`),
  CONSTRAINT `departments_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,1,'ITE','វិស្វកម្មបច្ចេកវិទ្យាព័ត៍មាន','2026-06-02 18:57:23','2026-06-11 11:25:20'),(10,2,'Computer Science','វិទ្យាសាស្ត្រ Computer','2026-06-08 09:20:32','2026-06-11 11:25:32'),(11,1,'Biology Engineering','វិស្វកម្មជីវសាស្រ្ត','2026-06-10 02:59:53','2026-06-11 11:26:49');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faculties`
--

DROP TABLE IF EXISTS `faculties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faculties` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_kh` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `faculties_manager_id_foreign` (`manager_id`),
  CONSTRAINT `faculties_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faculties`
--

LOCK TABLES `faculties` WRITE;
/*!40000 ALTER TABLE `faculties` DISABLE KEYS */;
INSERT INTO `faculties` VALUES (1,'Engineering','វិស្វកម្ម',2,1,'2026-06-02 18:51:06','2026-06-02 18:51:06'),(2,'Science','វិទ្យាសាស្ត្រ',14,1,'2026-06-08 09:10:27','2026-06-08 09:10:27'),(3,'Foreign Language','ភាសាបរទេស',15,1,'2026-06-08 09:11:10','2026-06-08 09:11:10');
/*!40000 ALTER TABLE `faculties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` VALUES (1,'default','{\"uuid\":\"371dd08d-75fd-42cf-8335-d4cb6caa9374\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:12;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:27:\\\"From admi: Noted Replied...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:33:\\\"Re: welcome with New Notification\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"b69df2b1-bcfe-4242-b370-db6580252480\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781116803,\"delay\":null}',0,NULL,1781116803,1781116803),(2,'default','{\"uuid\":\"da393bde-a783-4bbf-844c-446ae2a170d0\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:24:\\\"From admi: Reply back...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:16:\\\"Re: Notification\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"6d07c8cc-0f89-4540-a97a-0d429f618f72\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781116847,\"delay\":null}',0,NULL,1781116847,1781116847),(3,'default','{\"uuid\":\"f5968777-fb4f-4244-88b7-9cd5aa562a1e\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:16;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:39:\\\"From admi: I got you, and reply back...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:27:\\\"Re: Notification need reply\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"087298d5-50eb-4f31-8be2-0a1f51dacca7\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781117046,\"delay\":null}',0,NULL,1781117046,1781117046),(4,'default','{\"uuid\":\"29d2f650-8a5f-4e98-802d-9de180791e1c\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:24:\\\"From bobo: REply back...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:10:\\\"Reply back\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"a4c9d3dc-0c38-4d8e-91e0-a17eb9506dcb\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781120296,\"delay\":null}',0,NULL,1781120296,1781120296),(5,'default','{\"uuid\":\"8d5ceb15-8c68-44ac-91a4-f2820c901df3\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:33:\\\"From bobo: reply back to admin...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:10:\\\"Reply back\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"20f85199-1e99-4da2-942a-8eec77bd4a8c\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781120379,\"delay\":null}',0,NULL,1781120379,1781120379),(6,'default','{\"uuid\":\"58b7c594-5bc2-42d2-b83e-524b0cf6d1a9\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:24:\\\"From admi: reply bobo...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:10:\\\"bobo reply\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"c70c5ba7-6156-4340-b651-78b0c060a39f\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781120449,\"delay\":null}',0,NULL,1781120449,1781120449),(7,'default','{\"uuid\":\"c1aa8f7a-ccb1-441b-a3ed-2bffb4e04bb8\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:16;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:27:\\\"From bobo: reply to hoho...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:10:\\\"Reply back\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"95fb9411-0a13-449b-b09d-dec2703d016d\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781120877,\"delay\":null}',0,NULL,1781120877,1781120877),(8,'default','{\"uuid\":\"b733036c-65dc-4751-84b8-77906fecbf8c\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:27:\\\"From hoho: I get it bobo...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:13:\\\"I get it bobo\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"46220492-07db-4f0e-9efa-153ed52b427f\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781120916,\"delay\":null}',0,NULL,1781120916,1781120916),(9,'default','{\"uuid\":\"cb0f95e2-05f7-4573-b9bc-753a4145f2af\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:16;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:30:\\\"From bobo: Reply hoho again...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:10:\\\"Reply back\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"3a4e749c-aa8e-4c04-ba8f-8391ee667dfb\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781121531,\"delay\":null}',0,NULL,1781121531,1781121531),(10,'default','{\"uuid\":\"b360c3b2-bff6-413f-b391-215034bcc522\",\"displayName\":\"Filament\\\\Notifications\\\\DatabaseNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:10;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:43:\\\"Filament\\\\Notifications\\\\DatabaseNotification\\\":2:{s:4:\\\"data\\\";a:11:{s:7:\\\"actions\\\";a:0:{}s:4:\\\"body\\\";s:32:\\\"From momo: reply to bobo back...\\\";s:5:\\\"color\\\";N;s:8:\\\"duration\\\";s:10:\\\"persistent\\\";s:4:\\\"icon\\\";s:33:\\\"heroicon-o-chat-bubble-left-right\\\";s:9:\\\"iconColor\\\";s:7:\\\"success\\\";s:6:\\\"status\\\";N;s:5:\\\"title\\\";s:13:\\\"reply to bobo\\\";s:4:\\\"view\\\";N;s:8:\\\"viewData\\\";a:0:{}s:6:\\\"format\\\";s:8:\\\"filament\\\";}s:2:\\\"id\\\";s:36:\\\"9fcda078-bcbc-4078-850d-4e87fe237a4c\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:8:\\\"database\\\";}}\",\"batchId\":null},\"createdAt\":1781162933,\"delay\":null}',0,NULL,1781162933,1781162933);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_materials`
--

DROP TABLE IF EXISTS `lesson_materials`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lesson_materials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_schedule_id` bigint unsigned NOT NULL,
  `lecture_title_topic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_visible_to_students` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lesson_materials_class_schedule_id_foreign` (`class_schedule_id`),
  CONSTRAINT `lesson_materials_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_materials`
--

LOCK TABLES `lesson_materials` WRITE;
/*!40000 ALTER TABLE `lesson_materials` DISABLE KEYS */;
INSERT INTO `lesson_materials` VALUES (1,1,'Chapter 1: Introduction to Network Design Connecting','\"university-lessons-vault\\/Review questions for final exam.pdf\"',1,'2026-06-03 21:27:24','2026-06-04 05:04:06'),(2,1,'Chapter 1: Introduction to Network Design','\"university-lessons-vault\\/Gantt_Chart_answer_OS_Final_Exam.jpg\"',1,'2026-06-03 21:41:22','2026-06-03 21:45:22'),(3,1,'Chapter 2: Introduction to Network Design','\"university-lessons-vault\\/app_logo.png\"',1,'2026-06-03 21:46:39','2026-06-03 21:46:39'),(4,1,'Chapter 4: Introduction to Network Design','\"university-lessons-vault\\/SJF_Preemptive_Scheduling_Solution.docx\"',1,'2026-06-03 22:11:02','2026-06-03 22:11:02'),(5,1,'Chapter 5: Introduction to Network Design','\"university-lessons-vault\\/Computer_Graphics_Final_Exam_Answers.docx\"',1,'2026-06-04 01:43:14','2026-06-04 01:43:14'),(6,3,'Chapter 5: Introduction to Mobile Application','\"university-lessons-vault\\/Review questions for final exam.pdf\"',1,'2026-06-04 09:35:07','2026-06-04 09:35:07'),(7,1,'Chapter 6: Network Design 6','\"university-lessons-vault\\/img_central_market.jpg\"',1,'2026-06-08 08:56:25','2026-06-08 08:56:25'),(8,1,'Chapter 7 : Network Design 7','\"university-lessons-vault\\/Official Academic Timetable - M1.pdf\"',1,'2026-06-09 04:21:48','2026-06-09 04:21:48'),(9,1,'Chapter 8: Introduction to Network Design 8','\"university-lessons-vault\\/Official Academic Timetable - M1.pdf\"',1,'2026-06-09 10:47:46','2026-06-09 10:47:46'),(10,1,'Chapter 10: Introduction to Network Design 10','\"university-lessons-vault\\/Official Academic Timetable - M1.pdf\"',1,'2026-06-09 10:58:04','2026-06-09 11:05:47');
/*!40000 ALTER TABLE `lesson_materials` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_05_30_193422_create_university_system_tables',1),(5,'2026_06_04_105453_create_subject_final_grades_table',2),(6,'2026_06_04_155148_create_notifications_table',3),(7,'2026_06_04_233754_add_timetable_status_to_school_classes_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_classes`
--

DROP TABLE IF EXISTS `school_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_structure_id` bigint unsigned NOT NULL,
  `class_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `semester` enum('semester_1','semester_2','summer','winter','autumn','spring','fall','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift` enum('morning','afternoon','evening','weekend','full_day','online','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_timetable_published` tinyint(1) NOT NULL DEFAULT '0',
  `timetable_published_at` timestamp NULL DEFAULT NULL,
  `teacher_id` int DEFAULT NULL,
  `is_teacher_timetable_published` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `school_classes_class_code_unique` (`class_code`),
  KEY `school_classes_academic_structure_id_foreign` (`academic_structure_id`),
  CONSTRAINT `school_classes_academic_structure_id_foreign` FOREIGN KEY (`academic_structure_id`) REFERENCES `academic_structures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_classes`
--

LOCK TABLES `school_classes` WRITE;
/*!40000 ALTER TABLE `school_classes` DISABLE KEYS */;
INSERT INTO `school_classes` VALUES (1,1,'M1','semester_1','morning','101','2026-06-02 18:57:23','2026-06-10 02:55:26',1,'2026-06-05 00:52:00',12,0),(2,1,'A1','semester_1','afternoon','102','2026-06-02 18:57:23','2026-06-10 23:19:26',1,'2026-06-10 23:19:26',6,0),(8,10,'A2','semester_1','afternoon','404','2026-06-08 09:20:32','2026-06-10 02:29:38',1,'2026-06-08 09:29:42',6,0),(9,11,'W2','semester_2','weekend','505','2026-06-08 09:20:32','2026-06-10 02:29:54',1,NULL,6,0),(10,12,'E1','semester_1','evening','606','2026-06-10 03:09:55','2026-06-10 23:19:32',1,'2026-06-10 23:19:32',NULL,0),(11,12,'F1','semester_1','full_day','707','2026-06-10 03:09:55','2026-06-10 23:19:51',1,NULL,NULL,0);
/*!40000 ALTER TABLE `school_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2Q710b2tIaRseKSSgLFZcGgZCGPQJT7qZjUIHZru',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJnamVwM3h6MjFnTnpRTlR4WGMzalVIYW9peEFwMnB1N1FlRDNqQ01ZIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbiIsInJvdXRlIjoiZmlsYW1lbnQuYWRtaW4ucGFnZXMuZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiYTRhNTlhMjJkMmE0ZjkyMmUwMDg3MjQ5YTU3MTRjNjk0ZWQ1Y2U4MjY1ZjY1OTVlODg5YTJiZGZkOWNiNmMzNSIsInRhYmxlcyI6eyJlNjQ0ODMzZjRlNGUwODcxMjMxNWRhNzFiMzNmYWNkMl9jb2x1bW5zIjpbeyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImF2YXRhcl91cmwiLCJsYWJlbCI6IlByb2ZpbGUiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoibmFtZSIsImxhYmVsIjoiTmFtZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJlbWFpbCIsImxhYmVsIjoiRW1haWwiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicm9sZSIsImxhYmVsIjoiUm9sZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJpc19hY3RpdmUiLCJsYWJlbCI6IkFjdGl2ZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJjcmVhdGVkX2F0IiwibGFiZWwiOiJDcmVhdGVkIEF0IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX1dfX0=',1781292226),('puItjMxkxmdXyWxIqidpYlcEAjQmInTrNA8yoaRl',10,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJhY3J0T3VsaUMzZEtrN2piRnNnem9RQWJ6OHFvdnFCR05rM1BxeGIyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9zdHVkZW50Iiwicm91dGUiOiJmaWxhbWVudC5zdHVkZW50LnBhZ2VzLmRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxMCwicGFzc3dvcmRfaGFzaF93ZWIiOiI5NTdmYTRhYzQwNzc2ODIyNmRkMGJlMjNiODY5NjFiMzcxMDU0OGUzOGQ2NmRkY2ZkMzc4NjZlMzQxNmMzNDVhIiwidGFibGVzIjp7ImIzM2I1YTg4NGYzMDk4Y2IxZmE2Y2EzZGNhMTUxZWVlX2NvbHVtbnMiOlt7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiYWNhZGVtaWNTdHJ1Y3R1cmUuZGVwYXJ0bWVudC5mYWN1bHR5Lm5hbWVfZW4iLCJsYWJlbCI6IkZhY3VsdHkiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiYWNhZGVtaWNTdHJ1Y3R1cmUuZGVwYXJ0bWVudC5uYW1lX2VuIiwibGFiZWwiOiJEZXBhcnRtZW50IiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImFjYWRlbWljU3RydWN0dXJlLmFjYWRlbWljX2xldmVsIiwibGFiZWwiOiJBY2FkZW1pYyBMZXZlbCIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJhY2FkZW1pY1N0cnVjdHVyZS5nZW5lcmF0aW9uIiwibGFiZWwiOiJHZW5lcmF0aW9uIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImFjYWRlbWljU3RydWN0dXJlLnllYXJfcHJvZ3Jlc3MiLCJsYWJlbCI6IlllYXIgUHJvZ3Jlc3MiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoiY2xhc3NfY29kZSIsImxhYmVsIjoiQ2xhc3MgQ29kZSIsImlzSGlkZGVuIjpmYWxzZSwiaXNUb2dnbGVkIjp0cnVlLCJpc1RvZ2dsZWFibGUiOmZhbHNlLCJpc1RvZ2dsZWRIaWRkZW5CeURlZmF1bHQiOm51bGx9LHsidHlwZSI6ImNvbHVtbiIsIm5hbWUiOiJzaGlmdCIsImxhYmVsIjoiU2hpZnQiLCJpc0hpZGRlbiI6ZmFsc2UsImlzVG9nZ2xlZCI6dHJ1ZSwiaXNUb2dnbGVhYmxlIjpmYWxzZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpudWxsfSx7InR5cGUiOiJjb2x1bW4iLCJuYW1lIjoicm9vbV9udW1iZXIiLCJsYWJlbCI6IlJvb20gTnVtYmVyIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6dHJ1ZSwiaXNUb2dnbGVkSGlkZGVuQnlEZWZhdWx0IjpmYWxzZX0seyJ0eXBlIjoiY29sdW1uIiwibmFtZSI6ImRvd25sb2FkX3RpbWV0YWJsZV9jZXJ0aWZpY2F0ZSIsImxhYmVsIjoiT2ZmaWNpYWwgVGltZXRhYmxlIENlcnRpZmljYXRlIiwiaXNIaWRkZW4iOmZhbHNlLCJpc1RvZ2dsZWQiOnRydWUsImlzVG9nZ2xlYWJsZSI6ZmFsc2UsImlzVG9nZ2xlZEhpZGRlbkJ5RGVmYXVsdCI6bnVsbH1dfX0=',1781292405);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_profiles`
--

DROP TABLE IF EXISTS `student_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `student_id_card` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `age` int NOT NULL,
  `date_of_birth` date NOT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_profiles_user_id_unique` (`user_id`),
  UNIQUE KEY `student_profiles_student_id_card_unique` (`student_id_card`),
  CONSTRAINT `student_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_profiles`
--

LOCK TABLES `student_profiles` WRITE;
/*!40000 ALTER TABLE `student_profiles` DISABLE KEYS */;
INSERT INTO `student_profiles` VALUES (1,4,'ID-00001',20,'2026-06-01','male','09999999','Phnom Penh','2026-06-02 19:16:36','2026-06-02 19:16:36'),(2,7,'ID-00002',22,'2026-06-03','female','0888888','Kandal','2026-06-02 21:00:42','2026-06-02 21:00:42'),(3,5,'ID-00003',21,'2005-06-20','female','077777777','Kampot','2026-06-02 21:01:54','2026-06-02 21:01:54');
/*!40000 ALTER TABLE `student_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subject_final_grades`
--

DROP TABLE IF EXISTS `subject_final_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subject_final_grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `class_schedule_id` bigint unsigned NOT NULL,
  `total_accumulated_score` decimal(5,2) NOT NULL DEFAULT '0.00',
  `final_grade_letter` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_submitted_to_office` tinyint(1) NOT NULL DEFAULT '0',
  `submitted_to_office_at` timestamp NULL DEFAULT NULL,
  `is_approved_by_manager` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by_manager_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_schedule_unique` (`student_id`,`class_schedule_id`),
  KEY `subject_final_grades_class_schedule_id_foreign` (`class_schedule_id`),
  CONSTRAINT `subject_final_grades_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_final_grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subject_final_grades`
--

LOCK TABLES `subject_final_grades` WRITE;
/*!40000 ALTER TABLE `subject_final_grades` DISABLE KEYS */;
INSERT INTO `subject_final_grades` VALUES (1,4,1,0.00,'F',0,NULL,0,NULL,'2026-06-04 06:16:26','2026-06-11 08:01:25'),(2,5,1,0.00,'F',1,'2026-06-08 09:54:35',0,NULL,'2026-06-04 06:16:26','2026-06-11 07:59:10'),(3,7,1,0.00,'F',0,NULL,0,NULL,'2026-06-04 06:16:26','2026-06-11 08:01:25'),(4,8,1,0.00,'F',0,NULL,0,NULL,'2026-06-04 06:16:26','2026-06-11 08:01:25'),(5,9,1,320.00,'A+',1,'2026-06-11 02:03:21',0,NULL,'2026-06-04 06:16:26','2026-06-11 08:01:25'),(6,10,1,220.00,'A+',1,'2026-06-04 06:18:52',0,NULL,'2026-06-04 06:16:26','2026-06-11 08:01:25'),(7,5,3,0.00,'F',0,NULL,0,NULL,'2026-06-04 06:54:55','2026-06-04 06:54:55'),(8,4,3,0.00,'F',0,NULL,0,NULL,'2026-06-04 06:54:55','2026-06-04 06:54:55'),(9,7,3,0.00,'F',0,NULL,0,NULL,'2026-06-04 06:54:55','2026-06-04 06:54:55'),(10,8,3,0.00,'F',0,NULL,0,NULL,'2026-06-04 06:54:55','2026-06-04 06:54:55'),(11,9,3,0.00,'F',1,'2026-06-04 07:06:49',0,NULL,'2026-06-04 06:54:55','2026-06-11 07:59:15'),(12,10,3,166.67,'A+',1,'2026-06-04 06:55:16',0,NULL,'2026-06-04 06:54:55','2026-06-11 07:59:18'),(13,5,2,90.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:33','2026-06-08 09:35:50'),(14,4,2,50.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:33','2026-06-08 09:36:02'),(15,7,2,0.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:33','2026-06-08 09:35:33'),(16,8,2,0.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:33','2026-06-08 09:35:33'),(17,9,2,0.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:33','2026-06-08 09:35:33'),(18,10,2,0.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:33','2026-06-08 09:35:33'),(19,6,2,0.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:33','2026-06-08 09:35:33'),(20,12,2,0.00,'F',0,NULL,0,NULL,'2026-06-08 09:35:34','2026-06-08 09:35:34'),(21,6,1,50.00,'E',0,NULL,0,NULL,'2026-06-09 04:45:14','2026-06-11 07:57:47'),(22,12,1,49.00,'F',0,NULL,0,NULL,'2026-06-09 04:45:17','2026-06-11 07:57:53'),(23,12,5,65.00,'D',0,NULL,0,NULL,'2026-06-09 04:45:17','2026-06-11 07:58:10'),(24,11,5,60.00,'E+',0,NULL,0,NULL,'2026-06-09 04:45:17','2026-06-11 07:58:32');
/*!40000 ALTER TABLE `subject_final_grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `department_id` bigint unsigned NOT NULL,
  `subject_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `credits` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subjects_subject_code_unique` (`subject_code`),
  KEY `subjects_department_id_foreign` (`department_id`),
  CONSTRAINT `subjects_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,1,'NET','Network Design',NULL,10,'2026-06-02 19:12:08','2026-06-02 19:12:08'),(2,1,'WEB-01','Website Development',NULL,15,'2026-06-04 06:32:34','2026-06-04 06:32:34'),(3,1,'MAD','Mobile Application Development',NULL,15,'2026-06-04 06:33:04','2026-06-04 06:33:04'),(4,1,'CG-01','Computer Graphic',NULL,15,'2026-06-05 00:46:02','2026-06-05 00:46:02'),(5,10,'Math-01','Mathematics',NULL,15,'2026-06-08 09:24:48','2026-06-08 09:24:48'),(6,10,'Py-01','python-01',NULL,15,'2026-06-08 09:25:29','2026-06-08 09:25:29'),(7,11,'Bio-01','Biology Theory 1',NULL,15,'2026-06-10 03:11:08','2026-06-10 03:11:08');
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_events`
--

DROP TABLE IF EXISTS `system_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_events` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `scope_restriction` enum('global','faculty_only','staff_only') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'global',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_events`
--

LOCK TABLES `system_events` WRITE;
/*!40000 ALTER TABLE `system_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `system_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `system_notifications`
--

DROP TABLE IF EXISTS `system_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `system_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `recipient_type` enum('role','individual') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message_subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message_body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `system_notifications_sender_id_foreign` (`sender_id`),
  CONSTRAINT `system_notifications_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `system_notifications`
--

LOCK TABLES `system_notifications` WRITE;
/*!40000 ALTER TABLE `system_notifications` DISABLE KEYS */;
INSERT INTO `system_notifications` VALUES (1,1,'individual','10','New Announcement','Welcome to our community','system-alerts-attachments/Official Academic Transcript - Certificate Record.pdf','2026-06-04 08:38:47','2026-06-04 09:00:00'),(2,12,'role','student','welcome with New Notification','Hello , How are you?','system-alerts-attachments/Official Academic Transcript - Certificate Record.pdf','2026-06-04 09:12:07','2026-06-04 09:12:07'),(3,10,'individual','6','Notification','Hello how are you?',NULL,'2026-06-04 15:20:07','2026-06-04 15:20:07'),(4,10,'individual','12','Notification','Hello how are you?',NULL,'2026-06-04 15:21:03','2026-06-04 15:21:03'),(5,1,'individual','12','Re: welcome with New Notification','Noted Replied',NULL,'2026-06-10 11:40:03','2026-06-10 11:40:03'),(6,1,'individual','10','Re: Notification','Reply back',NULL,'2026-06-10 11:40:47','2026-06-10 11:40:47'),(7,16,'role','student','Notification need reply',' Reply to me',NULL,'2026-06-10 11:43:28','2026-06-10 11:43:28'),(8,1,'individual','16','Re: Notification need reply','I got you, and reply back',NULL,'2026-06-10 11:44:06','2026-06-10 11:44:06'),(9,10,'individual','1','Reply back','REply back',NULL,'2026-06-10 12:38:16','2026-06-10 12:38:16'),(10,10,'individual','1','Reply back','reply back to admin',NULL,'2026-06-10 12:39:39','2026-06-10 12:39:39'),(11,1,'individual','10','bobo reply','reply bobo',NULL,'2026-06-10 12:40:49','2026-06-10 12:40:49'),(12,10,'individual','16','Reply back','reply to hoho',NULL,'2026-06-10 12:47:57','2026-06-10 12:47:57'),(13,16,'individual','10','I get it bobo','I get it bobo',NULL,'2026-06-10 12:48:35','2026-06-10 12:48:35'),(14,10,'individual','16','Reply back','Reply hoho again',NULL,'2026-06-10 12:58:51','2026-06-10 12:58:51'),(15,6,'individual','10','reply to bobo','reply to bobo back',NULL,'2026-06-11 00:28:53','2026-06-11 00:28:53');
/*!40000 ALTER TABLE `system_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_assessments`
--

DROP TABLE IF EXISTS `task_assessments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_assessments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_schedule_id` bigint unsigned NOT NULL,
  `task_type` enum('attendance_weight','quiz','assignment','homework','midterm','project','final_exam','re_exam') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attachment_file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `max_score_threshold` double NOT NULL DEFAULT '100',
  `deadline_cut_off` datetime NOT NULL,
  `qcm_blueprint` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_assessments_class_schedule_id_foreign` (`class_schedule_id`),
  CONSTRAINT `task_assessments_class_schedule_id_foreign` FOREIGN KEY (`class_schedule_id`) REFERENCES `class_schedules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_assessments`
--

LOCK TABLES `task_assessments` WRITE;
/*!40000 ALTER TABLE `task_assessments` DISABLE KEYS */;
INSERT INTO `task_assessments` VALUES (1,1,'assignment','New Assignment',NULL,100,'2026-06-14 17:00:00',NULL,'2026-06-03 20:48:42','2026-06-11 02:18:13'),(2,1,'re_exam','Remedial Re-Exam Task Evaluation Module for Student ID #5',NULL,100,'2026-06-16 05:00:00','[{\"options\": {\"A\": \"1\", \"B\": \"2\", \"C\": \"3\"}, \"question_id\": \"Q1\", \"question_text\": \"1+1=?\", \"correct_key_answer\": \"B\"}]','2026-06-03 20:49:07','2026-06-11 02:17:06'),(8,1,'quiz','New Quiz',NULL,100,'2026-06-05 14:13:15','[{\"options\": {\"A\": \"4\", \"B\": \"2\", \"C\": \"5\"}, \"question_id\": \"q1\", \"question_text\": \"2+2=?\", \"correct_key_answer\": \"A\"}, {\"options\": {\"A\": \"3\", \"B\": \"6\", \"C\": \"9\"}, \"question_id\": \"q2\", \"question_text\": \"3+3\", \"correct_key_answer\": \"B\"}]','2026-06-04 00:15:04','2026-06-04 00:15:04'),(9,3,'quiz','New Quiz of MAD',NULL,100,'2026-06-10 20:39:29','[{\"options\": {\"A\": \"14\", \"B\": \"12\", \"C\": \"11\"}, \"question_id\": \"q1\", \"question_text\": \"7+7\", \"correct_key_answer\": \"A\"}, {\"options\": {\"A\": \"9\", \"B\": \"7\", \"C\": \"8\"}, \"question_id\": \"q2\", \"question_text\": \"4+4\", \"correct_key_answer\": \"C\"}, {\"options\": {\"A\": \"10\", \"B\": \"14\", \"C\": \"12\"}, \"question_id\": \"q4\", \"question_text\": \"5+5\", \"correct_key_answer\": \"A\"}]','2026-06-04 06:42:11','2026-06-04 06:42:11'),(10,3,'homework','New Homework',NULL,100,'2026-06-13 08:42:50','[{\"options\": {\"A\": \"4\", \"B\": \"8\", \"C\": \"6\"}, \"question_id\": \"q1\", \"question_text\": \"2+2\", \"correct_key_answer\": \"A\"}, {\"options\": {\"A\": \"3\", \"B\": \"2\", \"C\": \"4\"}, \"question_id\": \"q2\", \"question_text\": \"1+1\", \"correct_key_answer\": \"B\"}]','2026-06-04 06:47:32','2026-06-04 06:47:32'),(11,1,'quiz','Quiz chapter 2',NULL,100,'2026-06-14 22:51:25','[{\"options\": {\"A\": \"2\", \"B\": \"3\", \"C\": \"4\"}, \"question_id\": \"q1\", \"question_text\": \"1+1\", \"correct_key_answer\": \"A\"}, {\"options\": {\"A\": \"7\", \"B\": \"4\", \"C\": \"5\"}, \"question_id\": \"q2\", \"question_text\": \"2+2\", \"correct_key_answer\": \"B\"}]','2026-06-08 08:52:58','2026-06-11 02:14:46'),(12,1,'quiz','Quiz test ',NULL,100,'2026-06-13 15:56:11','[{\"options\": {\"A\": \"12\", \"B\": \"10\", \"C\": \"14\"}, \"question_id\": \"q1\", \"question_text\": \"5+5\", \"correct_key_answer\": \"B\"}, {\"options\": {\"A\": \"2\", \"B\": \"4\", \"C\": \"1\"}, \"question_id\": \"q2\", \"question_text\": \"4-2\", \"correct_key_answer\": \"A\"}]','2026-06-11 01:58:36','2026-06-11 01:58:36');
/*!40000 ALTER TABLE `task_assessments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','faculty_manager','study_office','teacher','student') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `lang_preference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions_matrix` json DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admi','admin@gmail.com','$2y$12$g.dlMnepK2xXCbk/GJaDA.HUfmAE5mOdTH2zjdVxylqbov5g0Ix3O','admin',1,'en','profile-photos/01KTTCSJ6VJV04CDCMRRPBJGVH.jpg',NULL,'7t5oi3zWPW2DGheDSkFKZeEFZDEEIFxR4vyWV3GTTiU6QhOXU9HBaVUXB1ux','2026-06-02 18:45:22','2026-06-10 20:49:40'),(2,'nana','nana@gmail.com','$2y$12$SOvb1avhoXmUmZ5z/3lt.u.GuoQXjSE4KFOQa/NITr4X/S0.yc7Ae','faculty_manager',1,'en','profile-photos/01KTTCXF4HR8174XEK1PN3AVQ3.jpg',NULL,'hqCrOUDcwIq3E5t82kYFXWR3AI9YnSYV9vaXD80Fdi6QbIoXe3kQKWGaPqw6','2026-06-02 18:46:56','2026-06-10 20:51:48'),(3,'caca','caca@gmail.com','$2y$12$ZDG2ZAzJKpCYKP5efFtJLOG.mbZ.Ylc3Gjjo2YCyG0cJuHiwNXPkq','study_office',1,'en','profile-photos/01KTVKG3EYFNQ13TV79J01CTSC.jpg',NULL,'zMsyuBOQlBZgUqgHaZs4QyOdxE2pwD4bmfnU1NHOtdFBt0EBUqK8Bil0mYEw','2026-06-02 18:47:41','2026-06-11 08:06:05'),(4,'lala','lala@gmail.com','$2y$12$bINgfJp71MVENggiz6PtvORhaDR6DUEaucfR6Bd2RDQxqziaOejjq','student',1,'kh',NULL,NULL,'7kaRM3EOungCfKR6PGdwbMCqRSxTMr4B600n5XHz6l7iVhv0f28XotciEI04','2026-06-02 18:48:38','2026-06-04 09:22:35'),(5,'koko','koko@gmail.com','$2y$12$xT05JxxPt18e04LdSU9z6emcIHl8sPAJAuRNH96mn1xsvv/zGwVBu','student',1,'en',NULL,NULL,NULL,'2026-06-02 18:48:58','2026-06-02 18:48:58'),(6,'momo','momo@gmail.com','$2y$12$4uBdZbNTHBooZwnqZjk4n.IoEYMOXdMQfqNXvboRZMkzr6tkFD7NO','teacher',1,'en',NULL,NULL,'LVaN10mwfuDBtFBTaDpvetGxT3dBJlCqxIAlwheqPSI06b5sLylsojvaFUcb','2026-06-02 19:13:29','2026-06-02 19:13:29'),(7,'sasa','sasa@gmail.com','$2y$12$xveG8cVLyUs2POh8z6nLeey9inFNO036PF25.8rVzuKmgM9.bI0m2','student',1,'en',NULL,NULL,NULL,'2026-06-02 19:14:35','2026-06-02 19:14:35'),(8,'meme','meme@gmail.com','$2y$12$F01avPVxEUTH307LzT.Y5eN2pLj0Xhh8N2aIyEpkk1wiE2F2iAQPi','student',1,'en',NULL,NULL,NULL,'2026-06-03 10:31:28','2026-06-03 10:31:28'),(9,'lulu','lulu@gmail.com','$2y$12$npVvsXydRADBvqANEK82fu4Pa5eBJFgWR2Osk48uJxQqNtqL6JdnG','student',1,'en',NULL,NULL,'2aWypIbCIZjuyqqHinSzat9dafZIzdytHrpR4bkbMoQbXizRe6uxN5TPGhgS','2026-06-03 10:32:01','2026-06-03 10:32:01'),(10,'bobo','bobo@gmail.com','$2y$12$TWttpRJqq9ScL/se/E5jx.J2I.4mK4MPgZqc0ptN6BkNLkS0BVRNW','student',1,'en','profile-photos/01KTTJEGHSYZDCWDF0D8CK3QA6.jpg',NULL,'98Q4nqDcWcq2BasZ3ts5L3bcDi2VUiycVNvgVKohiTGBDgBCQ6v7jRDq8Qw5','2026-06-03 10:32:31','2026-06-10 22:28:29'),(11,'TaTa','tata@gmail.com','$2y$12$b4xH1lpENdts0VcNOo5WJe2SHQLv4EKjh99ffkA94JeXVniJnWhuO','teacher',1,'en',NULL,NULL,'QLSY73oi9zrTlplyHcXAWuNPcJ1aCuuPJ2gRjxgHpxQ5aWSVOnBLaGIAF3bp','2026-06-04 06:30:30','2026-06-04 06:30:30'),(12,'lolo','lolo@gmail.com','$2y$12$dpvC1Ugw81YTlKZMn82ET.MbU7818KnQr1Z5/pcUgglgZebJsVZ6q','teacher',1,'en',NULL,NULL,'V1TJ9XentAiwI5nO5SIsUGJONiimYZVdfK4KDqGHhgx4z0kyoQ5pOSHekxHO','2026-06-04 06:31:05','2026-06-04 06:31:05'),(13,'didi','didi@gmail.com','$2y$12$tgiOT.70z0nNxHTobqXTtOpKOIaytCYJKz4zgJ99rQNQxucEipw26','teacher',1,'en',NULL,NULL,NULL,'2026-06-05 00:50:45','2026-06-05 00:50:45'),(14,'mimi','mimi@gmail.com','$2y$12$QrYaWYDWo6Gl/I.3rmnJReLttJDbLmYqAS6Q9ucaxJgV0gtLk76fy','faculty_manager',1,'en',NULL,NULL,'z9HoDBwK2Y4ZYxokM0Q0Y8Sk4J8j42vXZRG8MD4JD9EJrrjPpQKPocfvzoIW','2026-06-08 09:08:17','2026-06-08 09:08:17'),(15,'nini','nini@gmail.com','$2y$12$/a7KxzNvjn/Mgqt.JK68Z.1cqCuv5eQhUjbBexyOiVz330W7AOJvG','faculty_manager',1,'kh',NULL,NULL,NULL,'2026-06-08 09:09:40','2026-06-08 09:09:40'),(16,'hoho','hoho@gmail.com','$2y$12$.wb.vFdf3k6wmzBtnES5uOuBK1oM0lQ8Ti.7MIDM/2IBlzOeL.aX.','study_office',1,'en',NULL,NULL,'OsBBGSkBq51spx92t1Vk8MWxuDOj0baPXLstcnS8k7DPo7fmb1DFmGmqL7te','2026-06-08 09:40:56','2026-06-08 09:40:56');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-13  2:27:41
