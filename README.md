# zftest

Authentication test using Zend Framework 1.6.2

## Endpoints

+ **/index** -> redirects to **/dashboard**
+ **/dashboard** -> redirects to **/auth/login** if the user is not logged in

+ **/auth/login** -> allows user to log in (on success redirects to **/dashboard**)
+ **/auth/logout** -> ends user session and redirects to **/auth/login**

+ **/users/create** -> allows the creation of a new user

+ **/users** -> was suposed to list the users (does a soap request)

## Note
The soap server is working, but cannot perform requests anymore. It might have something to do with the
comunication between machines(the main problem is to fetch the wsdl, the second
problem is that the server can't interpret it).

Soap server might not be able to respond if it is in the same server as the
client. The solution wasto have 2 vms with a server each.

Use **vagrant ssh app** and **vagrant ssh soap** to start each machine. In the
"app" machine, the server 192.68.33.10 should be used and for the "soap", use
the .11