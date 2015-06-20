Feature: Get the list of the addresses
	This action should return the addresses.
	If a GET "id" parameter is sent it should return the requested address only (Legacy requirement).

	Background:
		Given The following addresses exist:
			| name | phone | street  |
			| name | 1234  | street1 |
			| name | 1234  | street2 |

	@legacy
	Scenario: When called with a nonexistent id, an empty array should be returned
		When I call the list addresses with the id "4"
		Then the http status of the response should be "404"


	@legacy
	Scenario: When called with an existent id, the corresponding address should be returned
		When I call the list addresses with the id "1"
		Then the result should be the address with the name "name" and without extra details


	Scenario: When called, it should list all the addresses in the database ordered by id
		When I call the list addresses
		Then the result of the call should be all the addresses
