-- Access tokens used on OAuth2 requests
CREATE TABLE IF NOT EXISTS /*_*/oauth2_access_tokens (
    oaat_id integer unsigned NOT NULL PRIMARY KEY auto_increment,
    -- Access token
    oaat_identifier varchar(255) NOT NULL,
    -- Expiration timestamp
    oaat_expires varbinary(14) NOT NULL,
	-- Identifier of the acceptance that allows this access token to be created
	oaat_acceptance_id integer unsigned NOT NULL,
    -- Indicates if the access token has been revoked
    oaat_revoked tinyint NOT NULL DEFAULT 0
) /*$wgDBTableOptions*/;

CREATE UNIQUE INDEX /*i*/oaat_identifier
    ON /*_*/oauth2_access_tokens (oaat_identifier);

CREATE INDEX /*i*/oaat_acceptance_id
	ON /*_*/oauth2_access_tokens (oaat_acceptance_id);
