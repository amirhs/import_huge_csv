
# Import huge list of employee as CSV file

## Tech Stack

- **Language:** [PHP]
- **Framework:** [Symfony 7]
- **Database:** [MySQL]
- **Queue:** [RabbitMQ]
- **Other Tools:** [Docker]

---

### Run the App
```shell
docker compose -p importCSV up -d
```

### Stop and Remove the Container
```shell
docker compose -p importCSV up -d --build app
```

### Run Job to consume and import data to MySQL
```shell
docker exec -it app php bin/console messenger:consume async --limit=10 --memory-limit=512M
```


### To Upload File
```shell
curl -X POST -F "file=@/home/amir/Downloads/import.csv" http://localhost:8002/api/employee
```

### To Import the File
```shell
curl -X POST http://localhost:8002/api/employee/import/1
```

**Endpoints:**
```http
GET /api/employee/{employeeId}
DELETE /api/employee/{employeeId}
```

### Get Employee
```shell
curl -X GET http://localhost:8002/api/employee/470143
```

```json 
{
    "id": 12,
    "employeeId": "198429",
    "email": "serafina.bumgarner@exxonmobil.com"
}
```

### Delete Employee
```shell
curl -X DELETE http://localhost:8002/api/employee/470143
```

```json 
{
  "message": "Employee with EmployeeID 470143 has been deleted."
}
```