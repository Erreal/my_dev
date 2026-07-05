-- ============================================
-- Portfolio CMS — Initial Database Schema
-- ============================================

CREATE TABLE IF NOT EXISTS profile (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    name_ru         VARCHAR(255) NOT NULL,
    name_en         VARCHAR(255) NOT NULL,
    position_ru     VARCHAR(255) NOT NULL,
    position_en     VARCHAR(255) NOT NULL,
    summary_ru      TEXT NOT NULL,
    summary_en      TEXT NOT NULL,
    photo           VARCHAR(255) DEFAULT NULL,
    email           VARCHAR(255) NOT NULL,
    github_url      VARCHAR(255) DEFAULT NULL,
    linkedin_url    VARCHAR(255) DEFAULT NULL,
    telegram_url    VARCHAR(255) DEFAULT NULL,
    resume_file     VARCHAR(255) DEFAULT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS technologies (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    name            VARCHAR(100) NOT NULL,
    category        ENUM('frontend', 'backend', 'tools', 'other') NOT NULL,
    icon            VARCHAR(255) DEFAULT NULL,
    sort_order      INT DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS experience (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    company_ru      VARCHAR(255) NOT NULL,
    company_en      VARCHAR(255) NOT NULL,
    position_ru     VARCHAR(255) NOT NULL,
    position_en     VARCHAR(255) NOT NULL,
    description_ru  TEXT DEFAULT NULL,
    description_en  TEXT DEFAULT NULL,
    start_date      DATE NOT NULL,
    end_date        DATE DEFAULT NULL,
    sort_order      INT DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projects (
    id                  INT PRIMARY KEY AUTO_INCREMENT,
    slug_ru             VARCHAR(255) NOT NULL UNIQUE,
    slug_en             VARCHAR(255) NOT NULL UNIQUE,
    title_ru            VARCHAR(255) NOT NULL,
    title_en            VARCHAR(255) NOT NULL,
    short_description_ru TEXT DEFAULT NULL,
    short_description_en TEXT DEFAULT NULL,
    full_description_ru  TEXT DEFAULT NULL,
    full_description_en  TEXT DEFAULT NULL,
    role_ru             VARCHAR(255) DEFAULT NULL,
    role_en             VARCHAR(255) DEFAULT NULL,
    responsibilities_ru  TEXT DEFAULT NULL,
    responsibilities_en  TEXT DEFAULT NULL,
    external_url        VARCHAR(255) DEFAULT NULL,
    featured            BOOLEAN DEFAULT FALSE,
    status              ENUM('active', 'archived', 'completed') DEFAULT 'completed',
    sort_order          INT DEFAULT 0,
    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_technologies (
    project_id      INT NOT NULL,
    technology_id   INT NOT NULL,
    PRIMARY KEY (project_id, technology_id),
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (technology_id) REFERENCES technologies(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_screenshots (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    project_id      INT NOT NULL,
    filename        VARCHAR(255) NOT NULL,
    thumbnail       VARCHAR(255) NOT NULL,
    title_ru        VARCHAR(255) DEFAULT NULL,
    title_en        VARCHAR(255) DEFAULT NULL,
    sort_order      INT DEFAULT 0,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_users (
    id              INT PRIMARY KEY AUTO_INCREMENT,
    username        VARCHAR(100) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Seed data: default admin user
-- Password: admin123 (change immediately!)
-- ============================================
INSERT INTO admin_users (username, password_hash)
VALUES ('admin', '$2y$12$LJ3m4ys3Lk0TSwHnbfOMiOXPm1Qlq5GzGq5X5Y5Z5a5b5c5d5e5f5g')
ON DUPLICATE KEY UPDATE username = username;

-- ============================================
-- Seed data: initial profile
-- ============================================
INSERT INTO profile (id, name_ru, name_en, position_ru, position_en, summary_ru, summary_en, email)
VALUES (
    1,
    'Михаил Захаров',
    'Mikhail Zakharov',
    'Senior Frontend Developer',
    'Senior Frontend Developer',
    'Frontend-разработчик с 15+ годами коммерческого опыта. Специализируюсь на React, TypeScript и архитектуре фронтенда. Работал над крупными GIS-приложениями, интернет-магазинами и медицинскими системами.',
    'Frontend developer with 15+ years of commercial experience. Specializing in React, TypeScript, and Frontend Architecture. Worked on large GIS applications, e-commerce platforms, and medical systems.',
    'mikhail@zakharov.dev'
)
ON DUPLICATE KEY UPDATE email = email;