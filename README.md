# zftest

Authentication test using Zend Framework 1.6.2

## Endpoints

**/index** -> redirects to **/dashboard**
**/dashboard** -> redirects to **/auth/login** if the user is not logged in

**/auth/login** -> allows user to log in (on success redirects to **/dashboard**)
**/auth/logout** -> ends user session and redirects to **/auth/login**

**/users/create** -> allows the creation of a new user
