CREATE TABLE claims(
    id INT PRIMARY KEY,
    claim_number VARCHAR(100) NOT NULL,
    insured_name VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(100) NOT NULL,
    analyst_id INT NOT NULL REFERENCES users(id),
    supervisor_id INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category) REFERENCES categories(name),
    FOREIGN KEY (status) REFERENCES statuses(name)
);