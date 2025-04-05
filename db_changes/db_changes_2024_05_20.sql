CREATE TABLE st_voucher_merchant (
         voucher_id INT NOT NULL,
         merchant_id INT NOT NULL,
         PRIMARY KEY (voucher_id, merchant_id),
         FOREIGN KEY (voucher_id) REFERENCES st_voucher_new(voucher_id) ON DELETE CASCADE,
         FOREIGN KEY (merchant_id) REFERENCES st_merchant(merchant_id) ON DELETE CASCADE
);


CREATE TABLE st_offers_merchant (
        offers_id INT NOT NULL,
        child_merchant_id INT NOT NULL,
        PRIMARY KEY (offers_id, child_merchant_id),
        FOREIGN KEY (offers_id) REFERENCES st_offers(offers_id) ON DELETE CASCADE,
        FOREIGN KEY (child_merchant_id) REFERENCES st_merchant(merchant_id) ON DELETE CASCADE
);


CREATE TABLE st_user_shared_merchant (
        merchant_user_id INT NOT NULL,
        child_merchant_id INT NOT NULL,
        PRIMARY KEY (merchant_user_id, child_merchant_id),
        FOREIGN KEY (merchant_user_id) REFERENCES st_merchant_user(merchant_user_id) ON DELETE CASCADE,
        FOREIGN KEY (child_merchant_id) REFERENCES st_merchant(merchant_id) ON DELETE CASCADE
);

