-- (c) Aaron Schulz, 2013

-- Replace /*_*/ with the proper prefix

-- These tables should belong in one central DB per wiki-farm

-- Client consumers (proposed as well as and accepted)
CREATE TABLE IF NOT EXISTS /*_*/oauth_registered_consumer (
    -- Immutable fields below:
    -- Consumer ID (1:1 with oarc_consumer_key)
    oarc_id integer unsigned NOT NULL auto_increment PRIMARY KEY,
    -- OAuth consumer key and secret (or RSA key)
    oarc_consumer_key varbinary(32) NOT NULL,
    oarc_secret_key varbinary(32) NULL,
    oarc_rsa_key blob NULL,
    -- Callback URL
    oarc_callback_url blob NOT NULL,
    -- Name of the application
    oarc_name varchar(255) binary NOT NULL,
    -- Key to the user who proposed the application
    oarc_user_id integer unsigned NOT NULL,
    -- Version of the application
    oarc_version varbinary(32) NOT NULL,
    -- Application description
    oarc_description blob NOT NULL,
    -- Confirmed contact email address
    oarc_email varchar(255) binary NOT NULL,
    -- What wiki this is allowed on (a single wiki or '*' for all)
    oarc_wiki varbinary(32) NOT NULL,
    -- JSON blob of allowed IP ranges
    oarc_origin_restrictions blob NOT NULL,
    -- Timestamp of consumer proposal
    oarc_registration varbinary(14) NOT NULL,

    -- Mutable fields below:
    -- Stage in registration pipeline (0=new, 1=held, 2=approved, 3=rejected, 4=expired, 5=disabled)
    oarc_stage tinyint unsigned NOT NULL DEFAULT 0,
    -- Timestamp of the last stage change
    oarc_stage_timestamp varbinary(14) NOT NULL,
    -- Whether this consumer is suppressed (hidden)
    oarc_deleted tinyint unsigned NOT NULL DEFAULT 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/oarc_consumer_key ON /*_*/oauth_registered_consumer (oarc_consumer_key);
CREATE UNIQUE INDEX /*i*/oarc_name_version_user
    ON /*_*/oauth_registered_consumer (oarc_name,oarc_user_id,oarc_version);
CREATE INDEX /*i*/oarc_user_id ON /*_*/oauth_registered_consumer (oarc_user_id);
CREATE INDEX /*i*/oarc_stage_timestamp
    ON /*i*/ oauth_registered_consumer (oarc_stage,oarc_stage_timestamp);

-- Grants needed for client consumers
CREATE TABLE IF NOT EXISTS /*_*/oauth_required_grant (
  -- Key to the consumer
  oarg_consumer_id integer unsigned NOT NULL,
  --  Name of the grant defined by configuration
  oarg_grant varchar(32) binary
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/oarg_consumer_grant
    ON /*_*/oauth_required_grant (oarg_consumer_id,oarg_grant);
CREATE INDEX /*i*/oarg_grant ON /*_*/oauth_required_grant (oarg_grant);

-- Grant approvals by users for consumers
CREATE TABLE IF NOT EXISTS /*_*/oauth_accepted_consumer (
    -- The name of a wiki or "*"
    oaac_wiki varchar(255) binary NOT NULL,
    -- Key to the user who approved the consumer (on the central wiki)
    oaac_user_id integer unsigned NOT NULL,
    -- Key to the consumer
    oaac_consumer_id integer unsigned NOT NULL,
    -- Tokens for the consumer to act on behave of the user
    oaac_access_token varbinary(32) NOT NULL,
    oaac_access_secret varbinary(32) NOT NULL,
    --- JSON blob of actually accepted grants
    oaac_grants blob NOT NULL,
    -- Timestamp of grant approval by the user
    oaac_accepted varbinary(14) NOT NULL
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/oaac_access_token
	ON /*_*/oauth_accepted_consumer (oaac_access_token);
CREATE UNIQUE INDEX /*i*/oaac_user_consumer_wiki
    ON /*_*/oauth_accepted_consumer (oaac_user_id,oaac_consumer_id,oaac_wiki);
CREATE INDEX /*i*/oaac_consumer_user
    ON /*_*/oauth_accepted_consumer (oaac_consumer_id,oaac_user_id);
