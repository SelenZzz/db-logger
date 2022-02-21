# db-logger

## Setup:

- Create new DB with `initDB.php`
- Create `ini.php` file accroding to `ini.php.example`

## Usage:

- Login via curl: `curl -c /tmp/cookies --user %USERNAME%:%PASSWORD% http://localhost:63342/Logger/auth.php`
- Add new log: `http://localhost:63342/Logger/reglog?server=%SERVERNAME%&base=%BASENAME%&event=%EVENTNAME%&status=%STATUS%`
