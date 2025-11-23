CREATE TABLE claim_files(
    id SERIAL PRIMARY KEY,
    claim_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (claim_id) REFERENCES claims(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);