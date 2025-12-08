CREATE TABLE claims_results(
    id INT PRIMARY KEY AUTO_INCREMENT,
    claim_id INT NOT NULL,
    decision VARCHAR(100) NOT NULL,
    comments TEXT,
    resolution_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    resolved_by INT NOT NULL,
    FOREIGN KEY (claim_id) REFERENCES claims(id),
    FOREIGN KEY (resolved_by) REFERENCES users(id),
    FOREIGN KEY (decision) REFERENCES decisions(name)
);