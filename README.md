FindMyMac module
======
Reports on FindMyMac status. These values are being pulled from `nvram` so this script will determine the correct status even if a machine was reimaged.


Table Schema
-----
* status - varchar(255) - If FindMyMac is enabled/disabled
* ownerdisplayname - varchar(255) - The Owner's Display Name of the associated email account
* email - varchar(255) - The email account associated with FindMyMac
* personid  - varchar(255) - Unique iCloud ID of account
* hostname - varchar(255) - iCloud server used to host associated iCloud account
