CREATE TABLE claims(
<<<<<<< HEAD
    id INT AUTO_INCREMENT PRIMARY KEY,
    claim_number VARCHAR(100) NOT NULL UNIQUE,
    policy_id INT NOT NULL,
    category_id INT NOT NULL,
    status_id INT NOT NULL,
=======
    id INT PRIMARY KEY AUTO_INCREMENT,
    claim_number VARCHAR(100) NOT NULL,
>>>>>>> df864e76dfd7e0a1c1abd64b75681027cf799a15
    insured_name VARCHAR(100) NOT NULL,
    insured_phone VARCHAR(20),
    insured_email VARCHAR(100),
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT,
    analyst_id INT,
    supervisor_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (policy_id) REFERENCES policies(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (status_id) REFERENCES statuses(id),
    FOREIGN KEY (analyst_id) REFERENCES users(id),
    FOREIGN KEY (supervisor_id) REFERENCES users(id)
);