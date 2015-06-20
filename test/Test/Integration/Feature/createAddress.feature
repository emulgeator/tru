Feature: This action should store the given address in the db


	Scenario Outline: When called with invalid parameters
		When I call the create address with
			| name   | phone   | street   |
			| <name> | <phone> | <street> |
		Then the http status of the response should be "400"

		Examples:
			| name | phone   | street |
			|      | 12312   | test 1 |
			| test | invalid | test 1 |
			| test | 12312   |        |


	Scenario: When called with valid parameters
		When I call the create address with
			| name | phone  | street   |
			| name | 123456 | street 1 |
		Then the http status of the response should be "201"
			And a location header with the URI to the created resource should be returned
			And the created address should be the same as the sent
