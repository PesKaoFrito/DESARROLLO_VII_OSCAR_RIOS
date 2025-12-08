CREATE TABLE policies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    policy_number VARCHAR(100) NOT NULL UNIQUE,
    insured_name VARCHAR(100) NOT NULL,
    insured_email VARCHAR(100),
    insured_phone VARCHAR(20),
    insured_address TEXT,
    policy_type VARCHAR(100) NOT NULL,
    coverage_amount DECIMAL(12, 2) NOT NULL,
    premium_amount DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
