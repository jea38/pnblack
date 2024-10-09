<?php
// database hostname, you don't usually need to change this
define('db_host','localhost');
// database username
define('db_user','root');
// database password
define('db_pass','');
// database name
define('db_name','opnblack');
// database charset, change this only if utf8 is not supported by your language
define('db_charset','utf8');
// Authentication required for guests
define('authentication_required', false);
// Max number of messages
define('max_messages', 30);
// Emoji list
define('emoji_list', '1F600,1F601,1F602,1F603,1F604,1F605,1F606,1F607,1F608,1F609,1F60A,1F60B,1F60C,1F60D,1F60E,1F60F,1F610,1F611,1F612,1F613,1F614,1F615,1F616,1F617,1F618,1F619,1F61A,1F61B,1F61C,1F61D,1F61E,1F61F,1F620,1F621,1F622,1F623,1F624,1F625,1F626,1F627,1F628,1F629,1F62A,1F62B,1F62C,1F62D,1F62E,1F62F,1F630,1F631,1F632,1F633,1F634,1F635,1F636,1F637,1F641,1F642,1F643,1F644,1F910,1F911,1F912,1F913,1F914,1F915,1F920,1F921,1F922,1F923,1F924,1F925,1F927,1F928,1F929,1F92A,1F92B,1F92C,1F92D,1F92E,1F92F,1F9D0');
/* Attachments */
// Enabled?
define('attachments_enabled', true);
// Upload directory
define('file_upload_directory', 'attachments/');
// Maximum allowed upload file size (500KB)
define('max_allowed_upload_file_size', 512000);
// File extension whitelist
define('file_types_allowed', '.png,.jpg,.jpeg,.webp,.gif,.bmp');
/* Performance */
// Measured in miliseconds
define('conversation_refresh_rate', 5000);
define('requests_refresh_rate', 10000);
define('users_online_refresh_rate', 10000);
define('general_info_refresh_rate', 10000);
?>