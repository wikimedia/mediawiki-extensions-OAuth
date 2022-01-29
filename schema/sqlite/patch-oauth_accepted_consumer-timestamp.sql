DROP  INDEX oaac_access_token;
DROP  INDEX oaac_user_consumer_wiki;
DROP  INDEX oaac_consumer_user;
DROP  INDEX oaac_user_id;
CREATE TEMPORARY TABLE /*_*/__temp__oauth_accepted_consumer AS
SELECT  oaac_id,  oaac_wiki,  oaac_user_id,  oaac_consumer_id,  oaac_access_token,  oaac_access_secret,  oaac_grants,  oaac_accepted,  oaac_oauth_version
FROM  /*_*/oauth_accepted_consumer;
DROP  TABLE  /*_*/oauth_accepted_consumer;
CREATE TABLE  /*_*/oauth_accepted_consumer (    oaac_id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,    oaac_wiki BLOB NOT NULL, oaac_user_id INTEGER UNSIGNED NOT NULL,    oaac_consumer_id INTEGER UNSIGNED NOT NULL,    oaac_access_token BLOB NOT NULL, oaac_access_secret BLOB NOT NULL,    oaac_grants BLOB NOT NULL, oaac_accepted BLOB NOT NULL,    oaac_oauth_version SMALLINT DEFAULT 1 NOT NULL  );
INSERT INTO  /*_*/oauth_accepted_consumer (    oaac_id, oaac_wiki, oaac_user_id,    oaac_consumer_id, oaac_access_token,    oaac_access_secret, oaac_grants,    oaac_accepted, oaac_oauth_version  )
SELECT  oaac_id,  oaac_wiki,  oaac_user_id,  oaac_consumer_id,  oaac_access_token,  oaac_access_secret,  oaac_grants,  oaac_accepted,  oaac_oauth_version
FROM  /*_*/__temp__oauth_accepted_consumer;
DROP  TABLE /*_*/__temp__oauth_accepted_consumer;
CREATE UNIQUE INDEX oaac_access_token ON  /*_*/oauth_accepted_consumer (oaac_access_token);
CREATE UNIQUE INDEX oaac_user_consumer_wiki ON  /*_*/oauth_accepted_consumer (    oaac_user_id, oaac_consumer_id, oaac_wiki  );
CREATE INDEX oaac_consumer_user ON  /*_*/oauth_accepted_consumer (oaac_consumer_id, oaac_user_id);
CREATE INDEX oaac_user_id ON  /*_*/oauth_accepted_consumer (oaac_user_id, oaac_id);