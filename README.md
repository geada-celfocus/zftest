# zftest

Authentication test using Zend Framework 1.6.2

## Endpoints

+ **/index** -> redirects to **/dashboard**
+ **/dashboard** -> redirects to **/auth/login** if the user is not logged in

+ **/auth/login** -> allows user to log in (on success redirects to **/dashboard**)
+ **/auth/logout** -> ends user session and redirects to **/auth/login**

+ **/users/create** -> allows the creation of a new user

## Notes
changelong.xml contains two properties not supported by sqlite: index and foreign key. If you choose to use sqlite, comment the lines (there are comments above both properties)

Don't use the data comming from the changelog. The database is create from the vagrant file and the user should be created by you throught the respective endpoint
