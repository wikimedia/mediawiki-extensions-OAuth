ALTER TABLE /*_*/oauth_registered_consumer
	ADD oarc_oauth2_is_confidential TINYINT NOT NULL DEFAULT 1;
