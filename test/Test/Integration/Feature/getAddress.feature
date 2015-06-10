Feature: Get address by id
	It should return the address for a given id as a json


	Scenario: When called with a nonexistent id, an empty array should be returned
		When I call the get address with the id "4"
		Then the result of the call should be
		"""
		[]
		"""

	Scenario Outline: When called with an existent id, the corresponding address should be returned
		When I call the get address with the id "<id>"
		Then the result of the call should be
		"""
		{
			"name"  : "<name>",
			"phone" : "<phone>",
			"street": "<street>"
		}
		"""

		Examples:
			| id | name   | phone     | street             |
			| 0  | Michal | 506088156 | Michalowskiego 41  |
			| 1  | Marcin | 502145785 | Opata Rybickiego 1 |
			| 2  | Piotr  | 504212369 | Horacego 23        |
			| 3  | Albert | 605458547 | Jan Pawła 67       |