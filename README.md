FindMyMac module
======
Reports on FindMy status. These values are being pulled from `nvram` on macOS 14 and lower so this script will determine the correct status even if a machine was reimaged. On macOS 15 and higher, it pulls the data from on disk files.


Table Schema
-----
* status - varchar(255) - If FindMy is enabled/disabled
* ownerdisplayname - varchar(255) - The Owner's Display Name of the associated email account
* email - varchar(255) - The email account associated with FindMy
* personid  - varchar(255) - Unique iCloud ID of account
* hostname - varchar(255) - iCloud server used to host associated iCloud account
* add_time - bigint - Timestamp of when FindMy was enabled