Feature: This action should return an address with the given id.


	Scenario: When called with a nonexistent id 404 should be returned
		When I call the get address with the id "1"
		Then the http status of the response should be "404"


	Scenario: When called with an existent id, the corresponding address should be returned
		Given The following addresses exist:
			| name | phone | street  |
			| name | 1234  | street1 |
		When I call the get address with the id "1"
		Then the result should be the address with the name "name"
