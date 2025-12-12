CREATE TABLE IF NOT EXISTS strategic_recommendations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    strategic_theme LONGTEXT,
    alignment_with_position LONGTEXT,
    short_term_actions JSON,
    long_term_actions JSON,
    resource_implications JSON,
    risk_mitigation JSON,
    ife_score DECIMAL(5, 2),
    efe_score DECIMAL(5, 2),
    quadrant VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    INDEX idx_project_id (project_id),
    INDEX idx_created_at (created_at)
);
