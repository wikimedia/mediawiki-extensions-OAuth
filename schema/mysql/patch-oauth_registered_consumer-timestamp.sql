ALTER TABLE  /*_*/oauth_registered_consumer
CHANGE  oarc_email_authenticated oarc_email_authenticated BINARY(14) NULL,
CHANGE  oarc_registration oarc_registration BINARY(14) NOT NULL,
CHANGE  oarc_stage_timestamp oarc_stage_timestamp BINARY(14) NOT NULL;